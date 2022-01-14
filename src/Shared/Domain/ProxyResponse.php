<?php

declare(strict_types=1);

namespace App\Shared\Domain;

final class ProxyResponse
{
    public function __construct(private ResponseContent $responseContent, private ResponseCode $responseCode)
    {
    }

    public function getResponseContent(): ResponseContent
    {
        return $this->responseContent;
    }

    public function getResponseCode(): ResponseCode
    {
        return $this->responseCode;
    }
}
