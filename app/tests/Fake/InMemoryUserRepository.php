<?php

declare(strict_types=1);

namespace Test\App\Fake;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;

final class InMemoryUserRepository implements UserRepositoryInterface
{
    /** @var array<array-key, User> */
    private array $users;

    /**
     * @param array<array-key, User> $users
     */
    public function __construct(array $users)
    {
        $this->users = $users;
    }

    /**
     * {@inheritDoc}
     */
    public function findAllByEmail(string $email): array
    {
        $users = [];

        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                $users[] = $user;
            }
        }

        return $users;
    }
}
