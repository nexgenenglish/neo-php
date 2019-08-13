<?php

use Neo\Content\Content;
use Neo\HttpClients\GuzzleHttpClient;
use PHPUnit\Framework\TestCase;

class ContentTest extends TestCase
{
    protected $content;

    public function setUp(): void
    {
        parent::setUp();

        $this->content = new Content(new GuzzleHttpClient([
            'base_uri' => getenv('NEO_JCT_BASE_URI'),
            'timeout'  => 120,
        ]));
    }

    public function testCertificationPlans()
    {
        $cp = $this->content->getCertificationPlans();

        $this->assertNotNull($cp);
    }
}
