<?php

declare(strict_types=1);

namespace App\Entity;

class User
{
    private string $email = '';
    private bool $deleted = false;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(): void
    {
        $this->deleted = true;
    }
}
