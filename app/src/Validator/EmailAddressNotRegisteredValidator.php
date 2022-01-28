<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class EmailAddressNotRegisteredValidator extends ConstraintValidator
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (false === ($constraint instanceof EmailAddressNotRegistered)) {
            throw new UnexpectedValueException($constraint, EmailAddressNotRegistered::class);
        }

        if (!is_string($value) || empty($value)) {
            return;
        }

        $emailAddressWithoutAlias = preg_replace('/\+.*?@/', '@', $value);

        $allUsersByEmail = $this->userRepository->findAllByEmail($emailAddressWithoutAlias);

        $usersDeletedExcluded = array_filter(
            $allUsersByEmail,
            fn (User $user): bool => false === $user->isDeleted(),
        );

        if (count($usersDeletedExcluded) > 0) {
            $this->context->addViolation($constraint->message);
        }
    }
}
