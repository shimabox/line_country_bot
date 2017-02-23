<?php

namespace App\Builders\Templates;

interface TemplateBuilder
{
    /**
     * Build a template.
     *
     * @return \LINE\LINEBot\MessageBuilder\TemplateBuilder
     */
    public function build();
}
