<?php

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    /**
     * @return array<array-key, User>
     */
    public function findAllByEmail(string $email): array;
}
