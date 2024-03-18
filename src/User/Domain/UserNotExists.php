<?php
declare(strict_types=1);

namespace Soulcodex\App\User\Domain;

use DomainException;
use Throwable;

final class UserNotExists extends DomainException
{
    private const MESSAGE = 'User not exists';

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, private readonly array $context = [])
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withUserEmail(UserEmail $email): self
    {
        return new self(self::MESSAGE, 0, null, ['user_email' => $email->email]);
    }

    public function context(): array
    {
        return $this->context;
    }
}