<?php

/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 30.05.17
 * Time: 16:00
 */

namespace RapidAPI\Tests;

use PHPUnit\Framework\TestCase;
use RapidAPI\Service\RequestParser;
use Slim\Http\Environment;
use Slim\Http\Request;

class RequestParserTest extends TestCase
{
    /** @var RequestParser */
    private $parser;

    public function setUp()
    {
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => "POST",
                'REQUEST_URI' => "http://localhost/api/testBlock1"
            ]
        );
        $request = Request::createFromEnvironment($environment);
        $data = $this->getData();
        $request = $request->withParsedBody($data);
        $this->parser = new RequestParser($request);
    }

    public function test() {
        $data = $this->parser->getParams();
        $this->assertTrue($this->getData() == $data);
    }

    private function getData()
    {
        return [
            "accessToken" => "asd",
            "json" => [
                [
                    "test" => "test"
                ],
                [
                    "test3" => [
                        "test4" => "test5"
                    ]
                ]
            ]
        ];
    }
}