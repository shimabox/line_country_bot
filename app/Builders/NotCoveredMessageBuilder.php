<?php

namespace App\Builders;

use App\Builders\MessageBuilder;

use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;

/**
 * 対象外
 */
class NotCoveredMessageBuilder implements MessageBuilder
{
    /** @var string */
    private $message = 'テキストメッセージ以外わからないです';

    /** @var string */
    private $packageId = '1';

    /** @var string */
    private $stickerId = '13';

    /**
     * Build a message.
     *
     * @return \LINE\LINEBot\MessageBuilder
     */
    public function build()
    {
        $t = new TextMessageBuilder($this->message);
        $s = new StickerMessageBuilder($this->packageId, $this->stickerId);

        return (new MultiMessageBuilder())
                ->add($t)
                ->add($s)
                ;
    }
}
