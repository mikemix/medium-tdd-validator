<?php

declare(strict_types=1);

namespace Test\App\Validator;

use App\Entity\User;
use App\Validator\EmailAddressNotRegistered;
use App\Validator\EmailAddressNotRegisteredValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Test\App\Fake\InMemoryUserRepository;

final class EmailAddressNotRegisteredValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * This method is required and needs to return the test subject.
     */
    protected function createValidator(): EmailAddressNotRegisteredValidator
    {
        $userFactory = function (string $email, bool $deleted): User {
            $user = new User();
            $user->setEmail($email);

            if ($deleted) {
                $user->setDeleted();
            }

            return $user;
        };

        // mock registered users
        // I prefer real objects over mocks whenever I can
        $users = new InMemoryUserRepository([
            $userFactory('jane@email.local', false),
            $userFactory('bob@corporate.local', false),
            $userFactory('deleted@private.local', true),
        ]);

        // finally return the test subject with mocked database access
        return new EmailAddressNotRegisteredValidator($users);
    }

    public function test_it_does_not_allow_already_registered_user(): void
    {
        $constraint = new EmailAddressNotRegistered();

        $this->validator->validate('bob@corporate.local', $constraint);

        $this->buildViolation($constraint->message)->assertRaised();
    }

    public function test_it_does_not_allow_already_registered_user_using_alias(): void
    {
        $constraint = new EmailAddressNotRegistered();

        $this->validator->validate('jane+office@email.local', $constraint);

        $this->buildViolation($constraint->message)->assertRaised();
    }

    public function test_it_allows_unregistered_users(): void
    {
        $constraint = new EmailAddressNotRegistered();

        $this->validator->validate('monty@corporate.local', $constraint);

        $this->assertNoViolation();
    }

    public function test_it_allows_soft_deleted_users_to_register_again(): void
    {
        $constraint = new EmailAddressNotRegistered();

        $this->validator->validate('deleted@corporate.local', $constraint);

        $this->assertNoViolation();
    }
}
