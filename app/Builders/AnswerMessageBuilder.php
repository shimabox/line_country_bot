<?php

namespace App\Builders;

use App\Builders\MessageBuilder;
use App\Builders\Templates\CountryDetailTemplateBuilder;
use App\Services\Finder\CountryFinder;
use App\Services\Record\Country;
use App\Services\Emoji;

use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;

class AnswerMessageBuilder implements MessageBuilder
{
    /** @var string */
    private $question = '';

    /** @var \App\Services\Finder\CountryFinder */
    private $countryFinder = null;

    /**
     * Finder setter
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
     * 質問セッター
     *
     * @param type $q
     * @return \App\Builders\AnswerMessageBuilder
     */
    public function setQuestion($q)
    {
        $this->question = $q;
        return $this;
    }

    /**
     * Build a message.
     *
     * @return \LINE\LINEBot\MessageBuilder
     */
    public function build()
    {
        $country = $this->find();

        $tf = Emoji::thinkingFace();

        if ($country === null) {
            $t1  = "[" . $this->question . "] ですね{$tf}";

            $t2  = "む、むむっ、、";
            $t2 .= "わかりませんでした" . Emoji::sadnessToRandom();

            return (new MultiMessageBuilder())
                ->add(new TextMessageBuilder($t1))
                ->add(new TextMessageBuilder($t2))
                ;
        }

        $t1 = "[" . $this->question . "] ですね{$tf}";
        $t2 = "わかります" . Emoji::smileToRandom();

        return (new MultiMessageBuilder())
                ->add(new TextMessageBuilder($t1))
                ->add(new TextMessageBuilder($t2))
                ->add($this->createCountryDetailTemplateBuilder($country))
                ;
    }

    /**
     * 検索
     *
     * @return \App\Services\Record\Country | null
     */
    protected function find()
    {
        return $this->countryFinder->find($this->question);
    }

    /**
     * 国情報テンプレート作成
     * 
     * @param Country $coutry
     * @return \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder
     */
    protected function createCountryDetailTemplateBuilder(Country $coutry)
    {
        $builder = (new CountryDetailTemplateBuilder())
                    ->setTitle($this->question)
                    ->setCountry($coutry)
                    ;

        return $builder->build();
    }
}
