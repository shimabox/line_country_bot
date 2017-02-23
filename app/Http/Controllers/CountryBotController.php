<?php

namespace App\Http\Controllers;

use App\Handlers\MessageEventHandler;
use App\Handlers\PostbackEventHandler;
use Illuminate\Http\Request;

use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent as LINEBotMessageEvent;
use LINE\LINEBot\Event\PostbackEvent as LINEBotPostbackEvent;

class CountryBotController extends Controller
{
    /**
     * call
     *
     * @param \LINE\LINEBot $bot
     * @param \Illuminate\Http\Request $req
     */
    public function call(LINEBot $bot, Request $req)
    {
        $events = $req->botevents;
        foreach ($events as $event) {

            /** @var \LINE\LINEBot\Response */
            $res = null;

            if ($event instanceof LINEBotMessageEvent) {
                $res = (new MessageEventHandler($bot, $event))->handle();
            }

            if ($event instanceof LINEBotPostbackEvent) {
                $res = (new PostbackEventHandler($bot, $event))->handle();
            }

            if ($res !== null && ! $res->isSucceeded()) {
                app('log')->error($res->getHTTPStatus() . ': ' . $res->getRawBody());
            }
        }
    }
}
