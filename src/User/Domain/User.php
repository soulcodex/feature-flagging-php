<?php
declare(strict_types=1);

namespace Soulcodex\App\User\Domain;

use Soulcodex\App\Shared\Domain\User\UserId;

final class User
{
    public function __construct(private readonly UserId $id, private UserEmail $email)
    {
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function changeEmail(UserEmail $email): void
    {
        $this->email = $email;
    }
}