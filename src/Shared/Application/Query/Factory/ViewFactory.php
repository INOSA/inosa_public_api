<?php

declare(strict_types=1);

namespace App\Shared\Application\Query\Factory;

use App\Shared\Application\Json\JsonEncoderInterface;
use App\Shared\Application\Query\InternalServerErrorView;

final class ViewFactory
{
    public function __construct(private JsonEncoderInterface $jsonEncoder)
    {
    }

    public function internalServerError(): InternalServerErrorView
    {
        return new InternalServerErrorView(
            $this->jsonEncoder->encode('Internal server error'),
            500
        );
    }
}
