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

namespace Alley\Validator;

use Laminas\Validator\Exception\InvalidArgumentException;

final class DivisibleBy extends ExtendedAbstractValidator
{
    public const NOT_DIVISIBLE_BY = 'notDivisibleBy';

    protected array $messageTemplates = [
        self::NOT_DIVISIBLE_BY => 'Must be evenly divisible by %divisor% but %value% is not.',
    ];

    protected array $messageVariables = [
        'divisor' => 'divisor',
    ];

    protected readonly int $divisor;

    public function __construct(array $options = [])
    {
        $check = isset($options['divisor']);

        if (!$check) {
            throw new InvalidArgumentException("'divisor' is required.");
        }

        $check = is_scalar($options['divisor']) && ((int) $options['divisor'] !== 0);

        if (!$check) {
            throw new InvalidArgumentException("Invalid 'divisor': {$options['divisor']}");
        }

        $this->divisor = (int) $options['divisor'];

        parent::__construct($options);
    }

    protected function testValue(mixed $value): void
    {
        $value = (int) $value;
        $actual = $value % $this->divisor;

        if ($actual !== 0) {
            $this->error(self::NOT_DIVISIBLE_BY);
        }
    }
}
