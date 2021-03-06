<?php

/*
 * This file is part of the laminas-validator-extensions package.
 *
 * (c) Alley <info@alley.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Alley\Validator;

use Laminas\Validator\Callback;
use Laminas\Validator\Exception\InvalidArgumentException;
use Laminas\Validator\Explode;
use Laminas\Validator\ValidatorInterface;

final class OneOf extends BaseValidator
{
    public const NOT_ONE_OF = 'notOneOf';

    protected $messageTemplates = [
        self::NOT_ONE_OF => "Must be one of %haystack% but is %value%.",
    ];

    protected $messageVariables = [
        'haystack' => ['options' => 'haystack'],
    ];

    protected $options = [
        'haystack' => [],
    ];

    private ValidatorInterface $haystackOptionValidator;

    public function __construct($options = null)
    {
        $this->haystackOptionValidator = new Explode([
            'validator' => new Callback('is_scalar'),
            'breakOnFirstFailure' => true,
        ]);

        parent::__construct($options);
    }

    protected function testValue($value): void
    {
        if (! \in_array($value, $this->options['haystack'], true)) {
            $this->error(self::NOT_ONE_OF);
        }
    }

    protected function setHaystack(array $haystack)
    {
        $valid = $this->haystackOptionValidator->isValid($haystack);

        if (! $valid) {
            throw new InvalidArgumentException('Haystack must contain only scalar values.');
        }

        $this->options['haystack'] = $haystack;
    }
}
