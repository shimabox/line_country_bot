<?php

namespace App\Services\Record;

/**
 * 国 モデル
 */
class Country
{
    /**
     * 国コード
     *
     * @var string
     */
    private $code = '';

    /**
     * 国名(日本語)
     *
     * @var string
     */
    private $namejps = '';

    /**
     * 首都名(日本語)
     *
     * @var string
     */
    private $capitaljp = '';

    /**
     * 首都名(英語)
     *
     * @var string
     */
    private $capitalen = '';

    /**
     * 緯度
     *
     * @var string
     */
    private $lat = '';

    /**
     * 経度
     *
     * @var string
     */
    private $lon = '';

    /**
     * 画像URL
     *
     * @var string
     */
    private $imageUrl = '';

    /**
     * 地図用(Google Maps) URL
     *
     * @var string
     */
    private $mapUrl = '';

    /**
     * Wikipedia用 URL
     *
     * @var string
     */
    private $wikiUrl = '';

    /**
     * 国コード セッター
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * 国コード ゲッター
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * 国名(日本語) セッター
     *
     * @param string $name
     */
    public function setNamejps($name)
    {
        $this->namejps = $name;
    }

    /**
     * 国名(日本語) ゲッター
     *
     * @return string
     */
    public function getNamejps()
    {
        return $this->namejps;
    }

    /**
     * 首都名(日本語) セッター
     *
     * @param string $name
     */
    public function setCapitaljp($name)
    {
        $this->capitaljp = $name;
    }

    /**
     * 首都名(日本語) ゲッター
     *
     * @return string
     */
    public function getCapitaljp()
    {
        return $this->capitaljp;
    }

    /**
     * 首都名(英語) セッター
     *
     * @param string $name
     */
    public function setCapitalen($name)
    {
        $this->capitalen = $name;
    }

    /**
     * 首都名(英語) ゲッター
     *
     * @return string
     */
    public function getCapitalen()
    {
        return $this->capitalen;
    }

    /**
     * 座標 セッター
     *
     * @param string $lat 緯度
     * @param string $lon 経度
     */
    public function settingCoordinate($lat, $lon)
    {
        $this->lat = $lat;
        $this->log = $lon;
    }

    /**
     * 緯度 ゲッター
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * 経度 ゲッター
     *
     * @return string
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * 画像URL 設定
     *
     * @param string $code
     */
    public function configurationImageUrl($code, $extension='.png')
    {
        $this->imageUrl = url(
            env('NATIONAL_FLAG_IMG_PATH') . strtolower($code) . $extension
        );
    }

    /**
     * 画像URL ゲッター
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * 地図用(Google Maps) URL 設定
     *
     * @param string $lat 緯度
     * @param string $lon 経度
     */
    public function configurationMapUrl($lat, $lon)
    {
        $this->mapUrl = $this->createMapUrl($lat, $lon);
    }

    /**
     * 地図用(Google Maps) URL ゲッター
     *
     * @return string
     */
    public function getMapUrl()
    {
        return $this->mapUrl;
    }

    /**
     * Wikipedia用 URL 設定
     *
     * @param string $name wikiで調べる名前
     */
    public function configurationWikiUrl($name)
    {
        $this->wikiUrl = $this->createWikiUrl($name);
    }

    /**
     * Wikipedia用 URL ゲッター
     *
     * @return string
     */
    public function getWikiUrl()
    {
        return $this->wikiUrl;
    }

    /**
     * 地図用URL 生成
     * 
     * @param string $lat 緯度
     * @param string $lon 経度
     * @param string $z   縮尺：2(最小ズーム)～14(最大ズーム)
     * @return string
     */
    protected function createMapUrl($lat, $lon, $z = 8)
    {
        return "https://maps.google.co.jp/maps?ll={$lat},{$lon}&q={$lat},{$lon}&z={$z}";
    }

    /**
     * wiki用URL 生成
     * 
     * @param string $q
     * @return string
     */
    protected function createWikiUrl($q)
    {
        return "https://ja.wikipedia.org/wiki/" . urlencode($q);
    }
}
