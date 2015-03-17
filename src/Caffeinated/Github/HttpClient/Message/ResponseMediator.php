<?php
namespace Caffeinated\Github\HttpClient\Message;

use Guzzle\Http\Message\Response;
use Guzzle\Exception\ApiLimitExceedException;

class ResponseMediator
{
    public static function getContent(Response $response)
    {
        $body    = $response->getBody(true);
        $content = json_decode($body, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return $body;
        }

        return $content;
    }

    public static function getApiLimit(Response $response)
    {
        $remainingCalls = $response->getHeader('X-RateLimit-Remaining');

        if (! is_null($remainingCalls) and 1 > $remainingCalls) {
            throw new ApiLimitExceedException($remainingCalls);
        }
    }
}
