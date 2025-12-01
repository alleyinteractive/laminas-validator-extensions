<?php

/*
 * This file is part of the laminas-validator-extensions package.
 *
 * (c) Alley <info@alley.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Alley\Validator\Tests\Unit;

use Alley\Validator\AlwaysValid;
use Alley\Validator\AnyValidator;
use Laminas\Validator\NumberComparison;
use PHPUnit\Framework\TestCase;

final class AnyValidatorTest extends TestCase
{
    public function testNoValidators()
    {
        $validator = new AnyValidator([]);
        $this->assertTrue($validator->isValid(42));
    }

    public function testValidValidator()
    {
        $validator = new AnyValidator([new AlwaysValid()]);
        $this->assertTrue($validator->isValid(42));
    }

    public function testInvalidValidator()
    {
        $validator = new AnyValidator([new NumberComparison(['min' => 43])]);
        $this->assertFalse($validator->isValid(42));
    }

    public function testFirstValidValidator()
    {
        $validator = new AnyValidator([
            new AlwaysValid(),
            new NumberComparison(['min' => 43]),
        ]);
        $this->assertTrue($validator->isValid(42));
    }
}
