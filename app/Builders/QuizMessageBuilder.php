<?php

namespace App\Builders;

use App\Builders\MessageBuilder;
use App\Services\Finder\CountryFinder;
use App\Services\Record\Country;
use App\Services\Generator\RandomKey;
use App\Services\GlueParameter;

use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;

class QuizMessageBuilder implements MessageBuilder
{
    /** @var \App\Services\Finder\CountryFinder */
    private $countryFinder = null;

    /** @var string */
    private $title = 'クイズです';

    /** @var string */
    private $message = 'この国はどこでしょうか？';

    /** @var int */
    private $templateCnt = 4;

    /**
     * csv検索モデル setter
     *
     * @param \App\Services\Finder\CountryFinder $finder
     * @return \App\Builders\AnswerMessageBuilder
     */
    public function setCountryFinder(CountryFinder $finder)
    {
        $this->countryFinder = $finder;
        return $this;
    }

    /**
     * Build a message.
     *
     * @return \LINE\LINEBot\MessageBuilder
     */
    public function build()
    {
        $keys = $this->generateRandomKey();
        $rowNoOfAnswer = $keys[mt_rand(0, count($keys) - 1)];
        $rowOfAnswer = $this->find($rowNoOfAnswer);

        $templates = $this->createExaminationTemplates($keys, $rowNoOfAnswer, $rowOfAnswer);

        $buttonTemplateBuilder = new ButtonTemplateBuilder(
            $this->title,
            $this->message,
            $rowOfAnswer->getImageUrl(),
            $templates
        );

        return new TemplateMessageBuilder($this->message, $buttonTemplateBuilder);
    }

    /**
     * ランダムなキー(Csvの行番号)を生成
     *
     * @return array
     */
    protected function generateRandomKey()
    {
        return (new RandomKey())
                ->setNecessaryNumber($this->templateCnt)
                ->setRangeMin(1) // header
                ->setRangeMax(GlueParameter::$numberOfRowsInCsv - 1) // csvは配列
                ->get();
    }

    /**
     * 質問生成
     * 
     * @param array $rowNos
     * @param string $rowNoOfAnswer
     * @param \App\Services\Record\Country $rowOfAnswer
     * @return array [\LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder]
     */
    protected function createExaminationTemplates($rowNos, $rowNoOfAnswer, Country $rowOfAnswer)
    {
        $ret = [];
        foreach ($rowNos as $rowNo) {
            if ($rowNo === $rowNoOfAnswer) {
                continue;
            }

            $_row = $this->find($rowNo);
            $ret[] = $this->createPostbackTemplate($_row->getNamejps());
        }

        $ret[] = $this->createPostbackTemplate($rowOfAnswer->getNamejps(), '1');

        shuffle($ret);

        return $ret;
    }

    /**
     * create PostbackTemplateActionBuilder
     *
     * @param string $name
     * @param string $result '0' | '1'
     * @return \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder
     */
    protected function createPostbackTemplate($name, $result='0')
    {
        $postback = GlueParameter::$answerKeyForReply . '=' . $name
                  .'&' . GlueParameter::$resultKeyForReply . '=' . $result;

        return new PostbackTemplateActionBuilder($name, $postback);
    }

    /**
     * 検索
     * 
     * @param int $rowNo
     * @return \App\Services\Record\Country
     */
    protected function find($rowNo)
    {
        return $this->countryFinder->findByRow($rowNo);
    }
}
