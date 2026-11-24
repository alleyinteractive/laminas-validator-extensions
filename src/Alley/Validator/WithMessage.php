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

use Laminas\Validator\ValidatorInterface;

final readonly class WithMessage implements ValidatorInterface
{
    public function __construct(
        private string $code,
        private string $message,
        private ValidatorInterface $origin
    ) {
    }

    public function isValid(mixed $value): bool
    {
        return $this->origin->isValid($value);
    }

    public function getMessages()
    {
        $messages = [];

        if (\count($this->origin->getMessages()) > 0) {
            $messages[$this->code] = $this->message;
        }

        return $messages;
    }
}
