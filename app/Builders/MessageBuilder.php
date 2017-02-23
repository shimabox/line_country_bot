<?php

namespace App\Builders;

interface MessageBuilder
{
    /**
     * Build a message.
     *
     * @return \LINE\LINEBot\MessageBuilder
     */
    public function build();
}
