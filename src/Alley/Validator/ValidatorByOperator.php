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

use Laminas\Validator\Regex;
use Laminas\Validator\ValidatorInterface;

final readonly class ValidatorByOperator implements ValidatorInterface
{
    private ValidatorInterface $final;

    public function __construct(string $operator, mixed $param)
    {
        // Build validator now so that its constructor runs, just as if the validator had been instantiated directly.
        $this->final = $this->validator($operator, $param);
    }

    public function isValid(mixed $value): bool
    {
        return $this->final->isValid($value);
    }

    public function getMessages()
    {
        return $this->final->getMessages();
    }

    private function validator(string $operator, mixed $param): ValidatorInterface
    {
        $validator = match ($operator) {
            'CONTAINS', 'NOT CONTAINS' => new ContainsString([
                'needle'     => $param,
                'ignoreCase' => false,
            ]),
            'IN', 'NOT IN' => new OneOf([
                'haystack' => $param,
            ]),
            'LIKE', 'NOT LIKE' => new ContainsString([
                'needle'     => $param,
                'ignoreCase' => true,
            ]),
            'REGEX', 'NOT REGEX' => new Regex([
                'pattern' => $param,
            ]),
            default => new Comparison([
                'operator' => $operator,
                'target' => $param,
            ]),
        };

        if (str_starts_with($operator, 'NOT ')) {
            $validator = new Not($validator, 'Invalid comparison.');
        }

        return $validator;
    }
}
