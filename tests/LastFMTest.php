<?php

namespace LuizPedone\LastFM\Tests;

use GuzzleHttp\Client;
use LuizPedone\LastFM\ApiRequestBuilder;
use LuizPedone\LastFM\LastFM;
use LuizPedone\LastFM\Period;
use LuizPedone\LastFM\User;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class LastFMTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @test
     */
    public function shouldReturnUserWhenItsCalled()
    {
        Mockery::mock('overload:' . User::class);
        Mockery::mock('overload:' . Client::class);

        $lastFm = new LastFM('api-key');

        $this->assertInstanceOf(User::class, $lastFm->user());
    }
}
