<?php

namespace App\Builders\Templates;

use App\Builders\Templates\TemplateBuilder;
use App\Services\Record\Country;

use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;

/**
 * 国の詳細情報を描画するテンプレートを作成
 */
class CountryDetailTemplateBuilder implements TemplateBuilder
{
    /** @var string */
    private $title = '';

    /** @var \App\Services\Record\Country */
    private $country = null;

    /**
     * 画像を表示するかどうか
     *
     * @var boolean falseで非表示
     */
    private $displayImg = true;

    /**
     * タイトル セッター
     *
     * @param string $title
     * @return \App\Builders\Templates\CountryDetailTemplateBuilder
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Finder setter
     *
     * @param \App\Services\Record\Country $country
     * @return \App\Builders\Templates\CountryDetailTemplateBuilder
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * 画像を表示しない
     * @return \App\Builders\Templates\CountryDetailTemplateBuilder
     */
    public function toInvisibleImg()
    {
        $this->displayImg = false;
        return $this;
    }

    /**
     * Build a template.
     *
     * @return \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder
     */
    public function build()
    {
        $text = $this->country->getCapitaljp() . ' / ' . $this->country->getCapitalen();
        $imageUrl = $this->displayImg ? $this->country->getImageUrl() : null;

        $t = new ButtonTemplateBuilder(
            $this->title,
            $text,
            $imageUrl,
            [
                new UriTemplateActionBuilder('Wikipedia', $this->country->getWikiUrl()),
                new UriTemplateActionBuilder('Google Maps', $this->country->getMapUrl())
            ]
        );

        return new TemplateMessageBuilder($this->title, $t);
    }
}
