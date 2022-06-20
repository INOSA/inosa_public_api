<?php

declare(strict_types=1);

namespace App\Tests\Shared\Domain;

use App\Shared\Domain\Identifier\UserIdentifier;
use App\Shared\Domain\Stringify;
use App\Tests\UnitTestCase;
use Inosa\Arrays\ArrayList;

final class StringifyTest extends UnitTestCase
{
    public function testStringifyWillCreateCorrectObjectFromString(): void
    {
        $string = 'ThisIsAwesomeString';

        $stringify = Stringify::fromString($string);

        self::assertEquals($string, $stringify->toString());
    }

    public function testStringifyWillCreateCorrectObjectFromStringable(): void
    {
        $stringable = new UserIdentifier('19125c9b-6b15-4f59-afdc-264788ae582f');

        $stringify = Stringify::fromStringable($stringable);

        self::assertEquals($stringable->toString(), $stringify->toString());
    }

    public function testStringifyWillCreateCorrectWithEmptyString(): void
    {
        $string = '';

        $stringify = Stringify::createEmpty();

        self::assertEquals($string, $stringify->toString());
    }

    public function testStringifyWillConcatenateTwoStrings(): void
    {
        $will = Stringify::fromString('Will');
        $smith = Stringify::fromString('Smith');

        $concatenated = $will->concat($smith);

        self::assertEquals('WillSmith', $concatenated->toString());
    }

    public function testStringifyRemoveLastCharacterWillRemoveLastCharacterFromString(): void
    {
        $stringify = Stringify::fromString('LastLetterIsGoingToBeDeletedX');

        $withRemovedLetter = $stringify->removeLastCharacter();

        self::assertEquals('LastLetterIsGoingToBeDeleted', $withRemovedLetter->toString());
    }

    public function testStringifyWillReturnTrueWithEqualsObjects(): void
    {
        $string1 = Stringify::fromString('Will');
        $string2 = Stringify::fromString('Smith');
        $string3 = Stringify::fromString('Will');

        self::assertTrue($string1->equals($string3));
        self::assertTrue($string3->equals($string1));
        self::assertFalse($string1->equals($string2));
        self::assertFalse($string2->equals($string1));
    }

    public function testStringifyWillReturnConcatendatedMultipleString(): void
    {
        /** @var ArrayList<Stringify> */
        $strings = ArrayList::create(
            [
                Stringify::fromString('will'),
                Stringify::fromString('smith'),
            ]
        );

        $result = Stringify::createEmpty()->concatMultiple(...$strings->toArray());

        self::assertEquals('willsmith', $result->toString());
    }
}
