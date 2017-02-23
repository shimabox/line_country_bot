<?php

namespace App\Handlers;

use App\Builders\Templates\CountryDetailTemplateBuilder;
use App\Services\GlueParameter;
use App\Services\Finder\Csv;
use App\Services\Finder\CountryFinder as CountryFinder;
use App\Services\Emoji;

use LINE\LINEBot;
use LINE\LINEBot\Event\PostbackEvent as LINEBotPostbackEvent;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;

class PostbackEventHandler
{
    /** @var \LINE\LINEBot */
    protected $bot;

    /** @var \LINE\LINEBot\Event\PostbackEvent */
    protected $event;

    /**
     * Create a new event instance.
     *
     * @param \LINE\LINEBot $bot
     * @param \LINE\LINEBot\Event\PostbackEvent $event
     */
    public function __construct(LINEBot $bot, LINEBotPostbackEvent $event)
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
        parse_str($this->event->getPostbackData(), $reply);

        $answer = $reply[GlueParameter::$answerKeyForReply];
        $isCorrect = (bool) $reply[GlueParameter::$resultKeyForReply];

        $m1 = "[". $answer . "]" . " ですね?";

        $m2  = "む、むむっ、、\r";
        $m2 .= $isCorrect
                ? "正解です" . Emoji::congratsToRandom()
                : "残念！はずれです" . Emoji::regretToRandom();

        $builder = (new MultiMessageBuilder())
                    ->add(new TextMessageBuilder($m1))
                    ->add(new TextMessageBuilder($m2))
                    ;

        if ($isCorrect) {
            $builder->add($this->createTemplateBuilderBasedOnAnswer($answer));
            $builder->add($this->createConfirmTemplateBuilder());
        }

        return $this->bot->replyMessage(
            $this->event->getReplyToken(),
            $builder
        );
    }
    
    /**
     * 正解の回答を元にテンプレートを作成
     * 
     * @param string $answer
     * @return \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder
     */
    protected function createTemplateBuilderBasedOnAnswer($answer)
    {
        $csv = new Csv(resource_path(env('COUNTRY_DATA_CSV')));
        $country = (new CountryFinder($csv))->find($answer);

        $builder = (new CountryDetailTemplateBuilder())
                    ->setTitle($answer)
                    ->setCountry($country)
                    ->toInvisibleImg()
                    ;

        return $builder->build();
    }
    
    /**
     * 再実行確認用テンプレート作成
     * 
     * @return \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder
     */
    protected function createConfirmTemplateBuilder()
    {
        return new TemplateMessageBuilder(
            'もう一度やりますか？',
            new ConfirmTemplateBuilder('もう一度やりますか？', [
                new MessageTemplateActionBuilder('いいえ', GlueParameter::$cancelWord),
                new MessageTemplateActionBuilder('はい', 'クイズ')
            ])
        );
    }
}
