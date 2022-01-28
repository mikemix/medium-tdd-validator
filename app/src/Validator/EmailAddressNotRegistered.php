<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
final class EmailAddressNotRegistered extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This e-mail address is already registered';

    /**
     * @return class-string<ConstraintValidator>
     */
    public function validatedBy(): string
    {
        return EmailAddressNotRegisteredValidator::class;
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
