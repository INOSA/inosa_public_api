<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use Inosa\Arrays\ArrayList;

enum DocumentType: string
{
    case INOSA = 'inosa';
    case QUIZ = 'quiz';
    case MESSAGE = 'message';
    case FILE = 'file';
    case URL = 'url';

    /**
     * @return string[]
     */
    public static function getDocumentTypes(): array
    {
        /** @var string[] $cases */
        $cases = ArrayList::create(self::cases())
            ->transform(fn(DocumentType $unitEnum): string => $unitEnum->value)
            ->toArray();

        return $cases;
    }
}
