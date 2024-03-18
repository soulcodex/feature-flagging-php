<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Infrastructure\Controller\Flag;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Soulcodex\App\Shared\Domain\Flag\FlagName;
use Soulcodex\App\Shared\Domain\Flag\FlagNotFound;
use Soulcodex\App\Shared\Domain\Flag\FlagUpdater;
use Soulcodex\App\Shared\Domain\Flag\FlagValue;
use Ulid\Ulid;
use function Lambdish\Phunctional\get;

final readonly class UpdateParameterByNameController
{
    public function __construct(private FlagUpdater $updater)
    {
    }

    public function __invoke(string $flagName, Request $request, Response $response): Response
    {
        $body = $request->getParsedBody() ?? [];

        [$flagName, $flagValue] = [new FlagName($flagName), new FlagValue(get('value', $body))];

        try {
            $this->updater->update($flagName, $flagValue);

            return $response->withStatus(200);
        } catch (FlagNotFound $e) {
            $response->getBody()->write(json_encode([
                'errors' => [
                    [
                        'id' => (new Ulid())->generate(),
                        'details' => $e->getMessage(),
                        'metadata' => $e->context()
                    ]
                ]
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
    }
}