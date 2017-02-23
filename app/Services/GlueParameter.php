<?php

namespace App\Services;

/**
 * クイズと回答を繋げるためのパラメータ設定
 */
class GlueParameter
{
    /**
     * 返答の解答用キー
     *
     * @var string
     */
    public static $answerKeyForReply = 'answer';

    /**
     * 返答の結果用キー
     *
     * @var string
     */
    public static $resultKeyForReply = 'result';

    /**
     * クイズのキャンセル用文言
     *
     * @var string
     */
    public static $cancelWord = 'いいえ';

    /**
     * CSVの行数
     * 
     * @var int
     */
    public static $numberOfRowsInCsv = 205;

    /**
     * 国コードのCSVにおける列の位置
     * 
     * @var int
     */
    public static $countryCodePosition = 0;
    /**
     * 国コードのキー名(CSVのヘッダー名)
     * 
     * @var int
     */
    public static $countryCodeKey = 'code';
    
    /**
     * 国名(日本語)のCSVにおける列の位置<br>
     * 検索対象
     * 
     * @var int
     */
    public static $countryNamejpPosition = 1;
    
    /**
     * 国名(日本語)のCSVにおける列の位置 こっちのほうがカジュアル<br>
     * 検索対象
     * 
     * @var int
     */
    public static $countryNamejpsPosition = 2;
    /**
     * 国名(日本語)のキー名(CSVのヘッダー名) こっちのほうがカジュアル
     * 
     * @var int
     */
    public static $countryNamejpsKey = 'namejps';
    
    /**
     * 首都名(日本語)のCSVにおける列の位置
     * 
     * @var int
     */
    public static $countryCapitaljpPosition = 5;
    /**
     * 首都名(日本語)のキー名(CSVのヘッダー名)
     * 
     * @var int
     */
    public static $countryCapitaljpKey = 'capitaljp';
    
    /**
     * 首都名(英語)のCSVにおける列の位置
     * 
     * @var int
     */
    public static $countryCapitalenPosition = 6;
    /**
     * 首都名(英語)のキー名(CSVのヘッダー名)
     * 
     * @var int
     */
    public static $countryCapitalenKey = 'capitalen';
    
    /**
     * 緯度のCSVにおける列の位置
     * 
     * @var int
     */
    public static $countryLatPosition = 7;
    /**
     * 緯度のキー名(CSVのヘッダー名)
     * 
     * @var int
     */
    public static $countryLatKey = 'lat';
    
    /**
     * 経度のCSVにおける列の位置
     * 
     * @var int
     */
    public static $countryLonPosition = 8;
    /**
     * 経度のキー名(CSVのヘッダー名)
     * 
     * @var int
     */
    public static $countryLonKey = 'lon';
}
