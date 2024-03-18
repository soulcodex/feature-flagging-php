<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Infrastructure\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final readonly class IndexController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $response->getBody()->write('Feature Flagging v1');

        return $response;
    }
}