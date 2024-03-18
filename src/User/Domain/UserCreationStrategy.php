<?php
declare(strict_types=1);

namespace Soulcodex\App\User\Domain;

use Soulcodex\App\Shared\Domain\Flag\FlagName;
use Soulcodex\App\Shared\Domain\Flag\FlagRetriever;
use Soulcodex\App\Shared\Domain\User\UserId;

final readonly class UserCreationStrategy
{
    private const FLAG_NAME = 'new_user_persistence_activated';

    public function __construct(
        private UserRepository $currentPersistence,
        private UserRepository $newPersistence,
        private FlagRetriever $fetcher
    )
    {
    }

    public function create(string $id, string $email): void
    {
        [$userId, $userEmail] = [UserId::create($id), UserEmail::create($email)];

        $user = new User($userId, $userEmail);

        if ($this->isNewPersistenceActivated()) {
            $this->newPersistence->save($user);
            return;
        }

        $this->currentPersistence->save($user);
    }

    private function isNewPersistenceActivated(): bool
    {
        $flagByName = $this->fetcher->flagByName(new FlagName(self::FLAG_NAME));
        $flag = $flagByName->default()->value();

        if (!is_null($flagByName->value()?->value())) {
            $flag = $flagByName->value()?->value();
        }

        return $flag === true;
    }
}