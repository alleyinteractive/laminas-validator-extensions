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

final class OneOf extends ExtendedAbstractValidator
{
    public const NOT_ONE_OF = 'notOneOf';

    protected array $messageTemplates = [
        self::NOT_ONE_OF => "Must be one of %haystack% but is %value%.",
    ];

    protected array $messageVariables = [
        'haystack' => 'haystackString',
    ];

    protected readonly array $haystack;

    protected readonly string $haystackString;

    public function __construct(array $options = [])
    {
        $check = isset($options['haystack']);

        if (!$check) {
            throw new InvalidArgumentException("'haystack' is required.");
        }

        $check = (
            is_array($options['haystack'])
            && $options['haystack'] === array_filter($options['haystack'], 'is_scalar')
        );

        if (!$check) {
            throw new InvalidArgumentException("'haystack' must be an array of scalar values.");
        }

        $this->haystack = $options['haystack'];
        $this->haystackString = json_encode($this->haystack);

        parent::__construct($options);
    }

    protected function testValue(mixed $value): void
    {
        if (!\in_array($value, $this->haystack, true)) {
            $this->error(self::NOT_ONE_OF);
        }
    }
}
