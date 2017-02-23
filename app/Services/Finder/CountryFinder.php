<?php

namespace App\Services\Finder;

use App\Services\Record\Country;
use App\Services\Finder\Csv;
use App\Services\GlueParameter;

/**
 * 国検索モデル
 */
class CountryFinder
{
    /**
     * @param \App\Services\Finder\Csv $csv
     */
    public function __construct(Csv $csv)
    {
        $this->csv = $csv;
    }

    /**
     * 検索
     * @param string $needle
     * @return \App\Services\Record\Country | null
     */
    public function find($needle)
    {
        $ret = $this->csv
                    ->setNeedle($needle)
                    ->setSearchColumns([
                        GlueParameter::$countryNamejpPosition,
                        GlueParameter::$countryNamejpsPosition
                    ])
                    ->setRequiredColumns([
                        GlueParameter::$countryCodePosition => GlueParameter::$countryCodeKey,
                        GlueParameter::$countryNamejpsPosition => GlueParameter::$countryNamejpsKey,
                        GlueParameter::$countryCapitaljpPosition => GlueParameter::$countryCapitaljpKey,
                        GlueParameter::$countryCapitalenPosition => GlueParameter::$countryCapitalenKey,
                        GlueParameter::$countryLatPosition => GlueParameter::$countryLatKey,
                        GlueParameter::$countryLonPosition => GlueParameter::$countryLonKey
                    ])
                    ->find()
                    ;

        if (count($ret) === 0) {
            return null;
        }

        return $this->createCountryRecord($ret);
    }

    /**
     * 行指定での検索
     * @param int $rowNo
     * @return \App\Services\Record\Country | null
     */
    public function findByRow($rowNo)
    {
        $ret = $this->csv
                    ->setRequiredColumns([
                        GlueParameter::$countryCodePosition => GlueParameter::$countryCodeKey,
                        GlueParameter::$countryNamejpsPosition => GlueParameter::$countryNamejpsKey,
                        GlueParameter::$countryCapitaljpPosition => GlueParameter::$countryCapitaljpKey,
                        GlueParameter::$countryCapitalenPosition => GlueParameter::$countryCapitalenKey,
                        GlueParameter::$countryLatPosition => GlueParameter::$countryLatKey,
                        GlueParameter::$countryLonPosition => GlueParameter::$countryLonKey
                    ])
                    ->findByRow($rowNo)
                    ;

        if (count($ret) === 0) {
            return null;
        }

        return $this->createCountryRecord($ret);
    }

    /**
     * 国モデル生成
     * 
     * @param array $ret
     * @return \App\Services\Record\Country
     */
    protected function createCountryRecord(array $ret)
    {
        $code       = $ret[GlueParameter::$countryCodeKey];
        $namejps    = $ret[GlueParameter::$countryNamejpsKey];
        $capitaljp  = $ret[GlueParameter::$countryCapitaljpKey];
        $capitalen  = $ret[GlueParameter::$countryCapitalenKey];
        $lat        = $ret[GlueParameter::$countryLatKey];
        $lon        = $ret[GlueParameter::$countryLonKey];

        $record = new Country();
        $record->setCode($code);
        $record->setNamejps($namejps);
        $record->setCapitaljp($capitaljp);
        $record->setCapitalen($capitalen);
        $record->settingCoordinate($lat, $lon);
        $record->configurationImageUrl($code);
        $record->configurationMapUrl($lat, $lon);
        $record->configurationWikiUrl($namejps);

        return $record;
    }
}
