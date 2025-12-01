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

final class Comparison extends ExtendedAbstractValidator
{
    private const SUPPORTED_OPERATORS = [
        '==',
        '===',
        '!=',
        '<>',
        '!==',
        '<',
        '>',
        '<=',
        '>=',
    ];

    private const OPERATOR_ERROR_CODES = [
        '==' => 'notEqual',
        '===' => 'notIdentical',
        '!=' => 'isEqual',
        '<>' => 'isEqual',
        '!==' => 'isIdentical',
        '<' => 'notLessThan',
        '>' => 'notGreaterThan',
        '<=' => 'notLessThanOrEqualTo',
        '>=' => 'notGreaterThanOrEqualTo',
    ];

    protected array $messageTemplates = [
        'notEqual' => 'Must be equal to %target% but is %value%.',
        'notIdentical' => 'Must be identical to %target% but is %value%.',
        'isEqual' => 'Must not be equal to %target% but is %value%.',
        'isIdentical' => 'Must not be identical to %target%.',
        'notLessThan' => 'Must be less than %target% but is %value%.',
        'notGreaterThan' => 'Must be greater than %target% but is %value%.',
        'notLessThanOrEqualTo' => 'Must be less than or equal to %target% but is %value%.',
        'notGreaterThanOrEqualTo' => 'Must be greater than or equal to %target% but is %value%.',
    ];

    protected array $messageVariables = [
        'target' => 'target',
    ];

    protected readonly mixed $target;

    private readonly string $operator;

    public function __construct(array $options = [])
    {
        $check = array_key_exists('target', $options);

        if (!$check) {
            throw new InvalidArgumentException("'target' option is required.");
        }

        $this->target = $options['target'];

        $check = isset($options['operator']);

        if (!$check) {
            throw new InvalidArgumentException("'operator' option is required.");
        }

        $check = \in_array($options['operator'], self::SUPPORTED_OPERATORS, true);

        if (!$check) {
            throw new InvalidArgumentException(
                sprintf(
                    "'operator' must be one of %s, got %s.",
                    implode(', ', self::SUPPORTED_OPERATORS),
                    $options['operator'],
                ),
            );
        }

        $this->operator = $options['operator'];

        parent::__construct($options);
    }

    protected function testValue(mixed $value): void
    {
        $result = match ($this->operator) {
            '==' => $value == $this->target,
            '!=', '<>' => $value != $this->target,
            '!==' => $value !== $this->target,
            '<' => $value < $this->target,
            '>' => $value > $this->target,
            '<=' => $value <= $this->target,
            '>=' => $value >= $this->target,
            default => $value === $this->target,
        };

        if (!$result) {
            $this->error(self::OPERATOR_ERROR_CODES[$this->operator]);
        }
    }
}
