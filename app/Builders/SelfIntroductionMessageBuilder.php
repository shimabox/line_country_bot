<?php

namespace App\Builders;

use App\Builders\MessageBuilder;

use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;

/**
 * 自己紹介
 */
class SelfIntroductionMessageBuilder implements MessageBuilder
{
    /**
     * Build a message.
     *
     * @return \LINE\LINEBot\MessageBuilder
     */
    public function build()
    {
        $buttonTemplateBuilder = new ButtonTemplateBuilder(
            env('PROFILE_TITLE'),
            env('PROFILE_TEXT'),
            url(env('PROFILE_IMG')),
            [
                new UriTemplateActionBuilder(env('PROFILE_LINK_TEXT'), env('PROFILE_LINK_URL'))
            ]
        );

        return new TemplateMessageBuilder(env('PROFILE_TITLE'), $buttonTemplateBuilder);
    }
}
