<?php

use LINE\LINEBot;
use LINE\LINEBot\Exception\InvalidSignatureException;

class ExampleTest extends TestCase
{
    public function testUndefinedRouteReturnsNotFound(): void
    {
        $this->get('/missing');
        $this->assertResponseStatus(404);
    }

    public function testWebhookRejectsInvalidSignature(): void
    {
        $bot = Mockery::mock(LINEBot::class);
        $bot->shouldReceive('parseEventRequest')->once()
            ->with('{"events":[]}', '')
            ->andThrow(InvalidSignatureException::class);
        $this->app->instance(LINEBot::class, $bot);

        $this->call('POST', '/webhook', [], [], [],
            ['CONTENT_TYPE' => 'application/json'], '{"events":[]}');

        $this->assertResponseStatus(400);
        $this->assertSame('Invalid signature.', $this->response->getContent());
    }

    public function testWebhookAcceptsParsedEmptyEvents(): void
    {
        $bot = Mockery::mock(LINEBot::class);
        $bot->shouldReceive('parseEventRequest')->once()
            ->with('{"events":[]}', '')->andReturn([]);
        $this->app->instance(LINEBot::class, $bot);

        $this->call('POST', '/webhook', [], [], [],
            ['CONTENT_TYPE' => 'application/json'], '{"events":[]}');

        $this->assertResponseStatus(200);
    }
}
