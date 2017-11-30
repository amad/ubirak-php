<?php

/*
 * This file is part of the Ubirak package.
 *
 * (c) Ubirak team <team@ubirak.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ubirak\Component\Healthcheck\Tests\Units;

use atoum;

class HttpHealthcheck extends atoum
{
    /**
     * @dataProvider reachability
     */
    public function test reachability(string $destination, int $statusCode, bool $expectedReachability)
    {
        $this
            ->given(
                $httpClient = new \mock\Http\Client\HttpClient(),
                $response = new \mock\GuzzleHttp\Psr7\Response(),
                $this->calling($httpClient)->sendRequest = $response,
                $logger = new \mock\Psr\Log\LoggerInterface(),
                $this->newTestedInstance($httpClient, $logger),
                $this->calling($response)->getStatusCode = $statusCode
            )
            ->when(
                $reachable = $this->testedInstance->isReachable($destination)
            )
            ->then
                ->boolean($reachable)->isIdenticalTo($expectedReachability)
                ->mock($logger)->call('info')->twice()
                ->mock($httpClient)->call('sendRequest')->once()
                ->mock($response)->call('getStatusCode')->once()
        ;
    }

    public function test it requires complete uri()
    {
        $this
            ->given(
                $httpClient = new \mock\Http\Client\HttpClient(),
                $this->newTestedInstance($httpClient)
            )
            ->exception(function () {
                $this->testedInstance->isReachable('localhost');
            })
            ->hasMessage('Destination must be a valid http uri.')
        ;
    }

    protected function reachability(): array
    {
        return [
            'reachable on 200' => ['http://localhost:9000/foo', 200, true],
            'unreachable on 201' => ['http://localhost:9000/foo', 201, false],
            'unreachable on 400' => ['http://localhost:9000/foo', 400, false],
            'unreachable on 500' => ['http://localhost:9000/foo', 500, false],
        ];
    }
}
