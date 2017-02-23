<?php

namespace App\Services\Finder;

/**
 * CSV検索モデル
 */
class Csv
{
    /**
     * 探す値
     *
     * @var string
     */
    protected $needle = '';

    /**
     * csvのパス
     *
     * @var string
     */
    protected $path = '';

    /**
     * csvが存在するか
     *
     * @var boolean
     */
    protected $fileExists = false;

    /**
     * デフォルトで検索をするカラムの列番号<br>
     * 複数あれば or で検索<br>
     * 1列目は0としてください<br>
     * デフォルト [0]
     *
     * @var array
     */
    protected $searchColumns = [0];

    /**
     * (検索で見つかったら)値を返して欲しいカラムの列番号とキー名<br>
     * 1列目は0としてください<br>
     * ['列番号' => 'キー名', '列番号' => 'キー名', ...]<br>
     * 設定しなければ検索で見つかった行をそのまま返します
     *
     * @var array
     */
    protected $requiredColumns = [];

    /**
     * ヘッダー行があるか
     *
     * @var boolean
     */
    protected $existsHeader = true;

    /**
     * コンストラクタ
     *
     * @param string $path CSVのパス
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->fileExists = file_exists($this->path);
    }

    /**
     * 探す値 setter
     *
     * @param string $needle
     * @return \App\Services\Finder\Csv
     */
    public function setNeedle($needle)
    {
        $this->needle = $needle;
        return $this;
    }

    /**
     * デフォルトで検索をするカラムの列番号 setter
     *
     * @param array $in
     * @return \App\Services\Finder\Csv
     */
    public function setSearchColumns(array $in)
    {
        $this->searchColumns = $in;
        return $this;
    }

    /**
     * (検索で見つかったら)値を返して欲しいカラムの列番号とヘッダーの名前 setter<br>
     * 1列目は0としてください
     *
     * @param array $in
     * @return \App\Services\Finder\Csv
     */
    public function setRequiredColumns(array $in)
    {
        $this->requiredColumns = $in;
        return $this;
    }

    /**
     * 検索
     * 
     * @return array
     */
    public function find()
    {
        $ret = [];

        if (! $this->fileExists || ! $this->needle) {
            return $ret;
        }

        $this->setAutoDetectLineEndings(1);

        $file = new \SplFileObject($this->path);
        $file->setFlags(\SplFileObject::READ_CSV);
        foreach ($file as $index => $row) {
            if (
                ($this->existsHeader && $index === 0) // ヘッダー
                || $row[0] === null // 空行
            ) {
                continue;
            }

            foreach ($this->searchColumns as $index) {
                if (
                    isset($row[$index])
                    && $this->toUTF8($row[$index]) === $this->needle
                ) {
                    $ret = $this->pick($row);
                    break;
                }
            }
        }

        $this->setAutoDetectLineEndings(0);

        return $ret;
    }

    /**
     * 行指定で検索
     *
     * @paran int $specifiedRow 行番号
     * @return array
     */
    public function findByRow($specifiedRow)
    {
        $ret = [];
        
        if (! $this->fileExists) {
            return $ret;
        }

        $this->setAutoDetectLineEndings(1);

        $file = new \SplFileObject($this->path);
        $file->setFlags(\SplFileObject::READ_CSV);
        foreach ($file as $index => $row) {
            if (
                ($this->existsHeader && $index === 0) // ヘッダー
                || $row[0] === null // 空行
                || $index !== (int)$specifiedRow
            ) {
                continue;
            }

            $ret = $this->pick($row);
        }

        $this->setAutoDetectLineEndings(0);

        return $ret;
    }

    /**
     * CSVの値(SJIS)をUTF-8に変換
     * @param [string|array] $val
     * @return [string|array]
     */
    protected function toUTF8($val)
    {
        if (is_string($val)) {
            return $this->_toUTF8($val, 'UTF-8', 'SJIS');
        }

        if (is_array($val)) {
            return array_map(array($this, 'toUTF8'), $val);
        }
    }

    /**
     * CSVの値(SJIS)をUTF-8に変換
     * @param string $str
     * @return string
     */
    protected function _toUTF8($str)
    {
        return mb_convert_encoding($str, 'UTF-8', 'SJIS');
    }

    /**
     * 選別
     * @param array $row
     * @return array
     */
    protected function pick($row)
    {
        if (count($this->requiredColumns) < 1) {
            return $this->toUTF8($row);
        }

        $ret = [];
        foreach ($this->requiredColumns as $index => $name) {
            if (!isset($row[$index])) {
                continue;
            }
            $ret[$name] = $row[$index];
        }

        return $this->toUTF8($ret);
    }

    /**
     * auto_detect_line_endings の切り替え<br>
     * MACで作成されたCSVだと改行コードがCRになってしまい認識できないので
     * @param int $val 0 => 'OFF' | 1 => 'ON'
     */
    protected function setAutoDetectLineEndings($val)
    {
        ini_set('auto_detect_line_endings', $val);
    }
}
