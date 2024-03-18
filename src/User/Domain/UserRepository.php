<?php
declare(strict_types=1);

namespace Soulcodex\App\User\Domain;

interface UserRepository
{
    public function save(User $user): void;

    public function findByEmail(UserEmail $email): User;
}