<?php

namespace App\Handlers;

interface EventHandler
{
    /**
     * Handle the event.
     * 
     * @return \LINE\LINEBot\Response
     */
    public function handle();
}
