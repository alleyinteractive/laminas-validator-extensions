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

final class Type extends ExtendedAbstractValidator
{
    public const NOT_OF_TYPE = 'notOfType';

    private const SUPPORTED_TYPES = [
        'array',
        'bool',
        'boolean',
        'callable',
        'double',
        'float',
        'int',
        'integer',
        'iterable',
        'null',
        'numeric',
        'object',
        'real',
        'resource',
        'scalar',
        'string',
    ];

    protected array $messageTemplates = [
        self::NOT_OF_TYPE => "Must be of PHP type '%type%' but %value% is not.",
    ];

    protected array $messageVariables = [
        'type' => 'type',
    ];

    protected readonly string $type;

    public function __construct(array $options = [])
    {
        $check = isset($options['type']);

        if (!$check) {
            throw new InvalidArgumentException("'type' is required.");
        }

        $check = \in_array($options['type'], self::SUPPORTED_TYPES, true);

        if (!$check) {
            throw new InvalidArgumentException(
                sprintf(
                    "'type' must be one of %s, got %s.",
                    implode(', ', self::SUPPORTED_TYPES),
                    $options['type'],
                ),
            );
        }

        $this->type = $options['type'];

        parent::__construct($options);
    }

    protected function testValue(mixed $value): void
    {
        $result = match ($this->type) {
            'array' => \is_array($value),
            'bool', 'boolean' => \is_bool($value),
            'int', 'integer' => \is_int($value),
            'double', 'float', 'real' => \is_float($value),
            'numeric' => is_numeric($value),
            'object' => \is_object($value),
            'resource' => \is_resource($value),
            'string' => \is_string($value),
            'scalar' => \is_scalar($value),
            'callable' => \is_callable($value),
            'iterable' => is_iterable($value),
            default => \is_null($value),
        };

        if (!$result) {
            $this->error(self::NOT_OF_TYPE);
        }
    }
}
