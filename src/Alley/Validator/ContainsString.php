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

final class ContainsString extends ExtendedAbstractValidator
{
    public const NOT_CONTAINS_STRING = 'notContainsString';

    protected array $messageTemplates = [
        self::NOT_CONTAINS_STRING => 'Must contain string "%needle%".',
    ];

    protected array $messageVariables = [
        'needle' => 'needle',
    ];

    protected readonly ?string $needle;

    private readonly bool $ignoreCase;

    public function __construct(array $options = [])
    {
        $check = array_key_exists('needle', $options);

        if (!$check) {
            throw new InvalidArgumentException("'needle' is required.");
        }

        $check = (
            \is_string($options['needle'])
            || \is_null($options['needle'])
            || $options['needle'] instanceof \Stringable
        );

        if (!$check) {
            throw new InvalidArgumentException("'needle' must be string or instance of \Stringable");
        }

        $this->needle = $options['needle'];
        $this->ignoreCase = isset($options['ignoreCase']) && is_bool($options['ignoreCase']) && $options['ignoreCase'];

        parent::__construct($options);
    }

    protected function testValue(mixed $value): void
    {
        if (\is_scalar($value)) {
            $haystack = (string) $value;
            $needle = (string) $this->needle;

            if ($this->ignoreCase) {
                $haystack = strtolower($haystack);
                $needle = strtolower($needle);
            }

            if (str_contains($haystack, $needle)) {
                return;
            }
        }

        $this->error(self::NOT_CONTAINS_STRING);
    }
}
