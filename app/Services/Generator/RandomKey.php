<?php

namespace App\Services\Generator;

/**
 * It generates a random key
 */
class RandomKey
{
    /**
     * 必要な数
     * @var int
     */
    private $necessaryNumber = 1;

    /**
     * 範囲の最小値
     * @var int
     */
    private $rangeMin = 0;

    /**
     * 範囲の最大値
     * @var int
     */
    private $rangeMax = 0;

    /**
     * @param int $in
     * @return App\Services\Generator\RandomKey
     */
    public function setNecessaryNumber($in)
    {
        $this->necessaryNumber = (int)$in;
        return $this;
    }

    /**
     * @param int $in
     * @return App\Services\Generator\RandomKey
     */
    public function setRangeMin($in)
    {
        $this->rangeMin = (int)$in;
        return $this;
    }

    /**
     * @param int $in
     * @return App\Services\Generator\RandomKey
     */
    public function setRangeMax($in)
    {
        $this->rangeMax = (int)$in;
        return $this;
    }

    /**
     * 取得
     *
     * @return array
     */
    public function get()
    {
        $this->normalize();

        return $this->generate();
    }

    /**
     * 初期化
     *
     * @return App\Services\Generator\RandomKey
     */
    public function init()
    {
        $this->necessaryNumber = 1;
        $this->rangeMin = 0;
        $this->rangeMax = 0;

        return $this;
    }

    /**
     * 正規化
     */
    protected function normalize()
    {
        if ($this->necessaryNumber < 1) {
            return;
        }

        if ($this->rangeMin < 0) {
            $this->rangeMin = 0;
        }

        if ($this->rangeMax < 0) {
            $this->rangeMax = 0;
        }

        if ($this->rangeMax < $this->rangeMin) {
            $this->rangeMin = $this->rangeMax;
        }

        // min:0, max:0 => 1つまで返せる
        // min:2, max:5 => 4つまで返せる
        $n = ($this->rangeMax - $this->rangeMin) + 1;
        if ($n < $this->necessaryNumber) {
            $this->necessaryNumber = $n;
        }
    }

    /**
     * 生成
     * 
     * @return array
     */
    protected function generate()
    {
        static $keys = [];

        if (count($keys) < $this->necessaryNumber) {

            $k = mt_rand($this->rangeMin, $this->rangeMax);

            if (isset($keys[$k])) {
                $this->generate();
            }

            $keys[$k] = true;
            $this->generate();
        }

        return array_keys($keys);
    }
}
