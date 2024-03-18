<?php
declare(strict_types=1);

namespace Soulcodex\App\User\Domain;

use DomainException;
use Soulcodex\App\Shared\Domain\User\UserId;
use Throwable;

final class ErrorStoringUser extends DomainException
{
    private const MESSAGE = 'Error storing user';

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, private readonly array $context = [])
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withUserId(UserId $id): self
    {
        return new self(self::MESSAGE, 0, null, ['user_id' => $id->id]);
    }

    public static function withUserIdAndPrevious(UserId $id, Throwable $previous): self
    {
        return new self(self::MESSAGE, 0, $previous, ['user_id' => $id->id]);
    }

    public function context(): array
    {
        return $this->context;
    }
}