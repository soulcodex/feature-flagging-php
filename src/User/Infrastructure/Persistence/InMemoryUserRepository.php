<?php
declare(strict_types=1);

namespace Soulcodex\App\User\Infrastructure\Persistence;

use Soulcodex\App\User\Domain\User;
use Soulcodex\App\User\Domain\UserEmail;
use Soulcodex\App\User\Domain\UserNotExists;
use Soulcodex\App\User\Domain\UserRepository;

final class InMemoryUserRepository implements UserRepository
{
    private array $users = [];

    public function save(User $user): void
    {
        $this->users[$user->id()->id] = $user;
        $this->users[$user->email()->email] = $user;
    }

    public function findByEmail(UserEmail $email): User
    {
        $user = $this->users[$email->email] ?? null;

        if (null !== $user) {
            return $user;
        }

        throw UserNotExists::withUserEmail($email);
    }
}