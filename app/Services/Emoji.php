<?php

namespace App\Services;

/**
 * emoji helper
 */
class Emoji
{
    /**
     * 名前とunicodeのペア
     *
     * @var array
     */
    private static $pair = [
        'smiling-face-with-smiling-eyes'        => '1F60A',
        'smiling-face-with-heart-shaped-eyes'   => '1F60D',
        'glowing-star'                          => '1F31F',
        'sparkles'                              => '2728',
        'party-popper'                          => '1F389',
        'confetti-ball'                         => '1F38A',

        'face-with-stuck-out-tongue'                            => '1F61B',
        'face-with-stuck-out-tongue-and-winking-eye'            => '1F61D',
        'face-with-stuck-out-tongue-and-tightly-closed-eyes'    => '1F61D',
        'cross-mark'                                            => '274C',

        'grinning-face'                         => '1F600',
        'smiling-face-with-sunglasses'          => '1F60E',
        'hugging-face'                          => '1F917',
        'smirking-face'                         => '1F60F',
        'winking-face'                          => '1F609',
        
        'crying-face'                           => '1F622',
        'loudly-crying-face'                    => '1F62D',
        'face-screaming-in-fear'                => '1F631',
        'dizzy-face'                            => '1F635',
        'confounded-face'                       => '1F616',

        'thinking-face'                         => '1F914',
    ];

    /**
     * 祝福系
     *
     * @var array
     */
    private static $groupOfCongrats = [
        'smiling-face-with-smiling-eyes',
        'smiling-face-with-heart-shaped-eyes',
        'glowing-star',
        'sparkles',
        'party-popper',
        'confetti-ball',
    ];

    /**
     * 残念系
     *
     * @var array
     */
    private static $groupOfRegret = [
        'face-with-stuck-out-tongue',
        'face-with-stuck-out-tongue-and-winking-eye',
        'face-with-stuck-out-tongue-and-tightly-closed-eyes',
        'cross-mark',
    ];

    /**
     * 笑顔系
     *
     * @var array
     */
    private static $groupOfSmile = [
        'grinning-face',
        'smiling-face-with-sunglasses',
        'hugging-face',
        'smirking-face',
        'winking-face',
    ];

    /**
     * 悲しみ系
     *
     * @var array
     */
    private static $groupOfSadness = [
        'crying-face',
        'loudly-crying-face',
        'face-screaming-in-fear',
        'dizzy-face',
        'confounded-face',
    ];

    /**
     * 祝福系の絵文字をランダムで返す
     *
     * @return string
     */
    public static function congratsToRandom()
    {
        $name = self::$groupOfCongrats[array_rand(self::$groupOfCongrats)];
        return self::toConvert(self::$pair[$name]);
    }

    /**
     * 残念系の絵文字をランダムで返す
     *
     * @return string
     */
    public static function regretToRandom()
    {
        $name = self::$groupOfRegret[array_rand(self::$groupOfRegret)];
        return self::toConvert(self::$pair[$name]);
    }
    
    /**
     * 笑顔系の絵文字をランダムで返す
     *
     * @return string
     */
    public static function smileToRandom()
    {
        $name = self::$groupOfSmile[array_rand(self::$groupOfSmile)];
        return self::toConvert(self::$pair[$name]);
    }

    /**
     * 悲しみ系の絵文字をランダムで返す
     *
     * @return string
     */
    public static function sadnessToRandom()
    {
        $name = self::$groupOfSadness[array_rand(self::$groupOfSadness)];
        return self::toConvert(self::$pair[$name]);
    }

    /**
     * thinking-face
     *
     * @return string
     */
    public static function thinkingFace()
    {
        return self::toConvert(self::$pair['thinking-face']);
    }

    /**
     * UnicodeからUTF-8文字列に変換
     *
     * @param string $unicode Unicode文字番号の16進数 ( "U+" は不要 ) e.g.) '1F914'
     */
    protected static function toConvert($unicode)
    {
        return mb_convert_encoding(hex2bin(sprintf("%08s", $unicode)), 'UTF-8', 'UTF-32');
    }
}
