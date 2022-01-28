<?php

declare(strict_types=1);

namespace App\UseCase\Registration;

use App\Validator\EmailAddressNotRegistered;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-immutable
 */
final class RegisterDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(max=255)
     * @EmailAddressNotRegistered()
     */
    public string $email;
}
