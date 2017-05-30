<?php

/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 30.05.17
 * Time: 15:53
 */

namespace RapidAPI\Service;

use RapidAPI\Exception\PackageException;
use Slim\Http\Request;

class RequestParser
{

    /** @var Request */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getParams(): array
    {
        $data = $this->request->getBody();

        if ($data == '') {
            $result = $this->request->getParsedBody();
        } else {
            $data = $this->normalizeJson($data);
            $data = str_replace('\"', '"', $data);
            $result = json_decode($data, true);
        }

        if (json_last_error() != 0) {
            throw new PackageException(json_last_error_msg() . '. Incorrect input JSON. Please, check fields with JSON input.');
        }

        return $result;
    }

    private function normalizeJson($data)
    {
        return preg_replace_callback('~"([\[{].*?[}\]])"~s', function ($match) {
            return preg_replace('~\s*"\s*~', "\"", $match[1]);
        }, $data);
    }
}