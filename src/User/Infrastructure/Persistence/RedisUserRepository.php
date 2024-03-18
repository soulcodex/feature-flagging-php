<?php

declare(strict_types=1);

namespace Soulcodex\App\User\Infrastructure\Persistence;

use Predis\ClientInterface as RedisClient;
use Predis\PredisException;
use Predis\Transaction\MultiExec;
use Soulcodex\App\Shared\Domain\User\UserId;
use Soulcodex\App\User\Domain\ErrorStoringUser;
use Soulcodex\App\User\Domain\User;
use Soulcodex\App\User\Domain\UserEmail;
use Soulcodex\App\User\Domain\UserNotExists;
use Soulcodex\App\User\Domain\UserRepository;
use function Lambdish\phunctional\get;

final readonly class RedisUserRepository implements UserRepository
{
    private const PREFIX = 'user::';
    private const ERROR_STORING = 0;

    public function __construct(private RedisClient $client)
    {
    }

    public function save(User $user): void
    {
        try {
            $transactions = $this->client->transaction(function (MultiExec $tx) use ($user) {
                foreach ($this->fromDomain($user) as $userAttribute => $value) {
                    $tx->hset($this->keyByEmail($user->email()), $userAttribute, $value);
                }
            });

            if (in_array(self::ERROR_STORING, $transactions)) {
                throw ErrorStoringUser::withUserId($user->id());
            }
        } catch (PredisException $e) {
            throw ErrorStoringUser::withUserIdAndPrevious($user->id(), $e);
        }
    }

    public function findByEmail(UserEmail $email): User
    {
        $rawUser = $this->client->hgetall($this->keyByEmail($email));

        if (!empty($rawUser)) {
            return $this->toDomain($rawUser);
        }

        throw UserNotExists::withUserEmail($email);
    }

    private function keyByEmail(UserEmail $email): string
    {
        return sprintf("%s%s", self::PREFIX, $email->email);
    }

    private function fromDomain(User $user): array
    {
        return [
            'id' => $user->id()->id,
            'email' => $user->email()->email
        ];
    }

    private function toDomain(array $user): User
    {
        [$id, $email] = [get('id', $user), get('email', $user)];

        return new User(UserId::create($id), UserEmail::create($email));
    }
}