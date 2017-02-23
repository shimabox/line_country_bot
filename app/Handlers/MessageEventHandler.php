<?php

namespace App\Handlers;

use App\Handlers\EventHandler;
use App\Builders\NotCoveredMessageBuilder;
use App\Builders\SelfIntroductionMessageBuilder;
use App\Builders\QuizMessageBuilder;
use App\Builders\AnswerMessageBuilder;
use App\Services\Finder\Csv;
use App\Services\Finder\CountryFinder;
use App\Services\GlueParameter;

use LINE\LINEBot;
use LINE\LINEBot\Response;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;

class MessageEventHandler implements EventHandler
{
    /** @var \LINE\LINEBot */
    protected $bot;

    /** @var \LINE\LINEBot\Event\MessageEvent */
    protected $event;

    /** @var array */
    protected $triggerWordOfQuiz = ['クイズ', 'quiz'];

    /** @var array */
    protected $triggerWordOfSelfIntroduction = ['君の名は', 'who'];
    
    /**
     * Create a new event instance.
     *
     * @param \LINE\LINEBot $bot
     * @param \LINE\LINEBot\Event\MessageEvent $event
     */
    public function __construct(LINEBot $bot, MessageEvent $event)
    {
        $this->bot = $bot;
        $this->event = $event;
    }

    /**
     * Handle the event.
     *
     * @return \LINE\LINEBot\Response
     */
    public function handle()
    {
        // テキストメッセージ以外
        if (! $this->event instanceof TextMessage) {
            return $this->notCoverd();
        }

        // 入力文字の正規化
        $text = $this->_trim($this->event->getText());
        $lowerText = mb_strtolower($text);

        // 自己紹介
        if (
            in_array($lowerText, $this->triggerWordOfSelfIntroduction)
            && env('PROFILE_IMG') !== ''
        ) {
            return $this->selfIntroduction();
        }

        // クイズ
        if (in_array($lowerText, $this->triggerWordOfQuiz)) {
            return $this->quiz();
        }
        
        // クイズのキャンセル
        if ($text === GlueParameter::$cancelWord) {
            return new Response(200, json_encode(['cancel' => true]));
        }
        
        // 解答
        return $this->answer($text);
    }

    /**
     * テキストメッセージ以外
     *
     * @return \LINE\LINEBot\Response
     */
    protected function notCoverd()
    {
        $builder = (new NotCoveredMessageBuilder)->build();

        return $this->bot->replyMessage(
            $this->event->getReplyToken(),
            $builder
        );
    }

    /**
     * 自己紹介
     *
     * @return \LINE\LINEBot\Response
     */
    protected function selfIntroduction()
    {
        $builder = (new SelfIntroductionMessageBuilder())->build();

        return $this->bot->replyMessage(
            $this->event->getReplyToken(),
            $builder
        );
    }

    /**
     * クイズ
     *
     * @return \LINE\LINEBot\Response
     */
    protected function quiz()
    {
        $csv = new Csv(resource_path(env('COUNTRY_DATA_CSV')));
        $countryFinder = new CountryFinder($csv);

        $builder = (new QuizMessageBuilder())
                    ->setCountryFinder($countryFinder)
                    ->build()
                    ;

        return $this->bot->replyMessage(
            $this->event->getReplyToken(),
            $builder
        );
    }

    /**
     * 回答
     *
     * @param string $text
     * @return \LINE\LINEBot\Response
     */
    protected function answer($text)
    {
        $csv = new Csv(resource_path(env('COUNTRY_DATA_CSV')));
        $countryFinder = new CountryFinder($csv);
        
        $builder = (new AnswerMessageBuilder())
                    ->setCountryFinder($countryFinder)
                    ->setQuestion($text)
                    ->build()
                    ;

        return $this->bot->replyMessage(
            $this->event->getReplyToken(),
            $builder
        );
    }

    /**
     * トリム(半角・全角スペース・改行コード)
     *
     * @param string $value
     * @return string
     */
    protected function _trim($value)
    {
        return preg_replace('/(\s|　|\n)/u', '', $value);
    }
}
