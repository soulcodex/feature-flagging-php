<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Infrastructure\Controller\Flag;

use Psr\Http\Message\ResponseInterface as Response;
use Soulcodex\App\Shared\Domain\Flag\FlagName;
use Soulcodex\App\Shared\Domain\Flag\FlagNotFound;
use Soulcodex\App\Shared\Domain\Flag\FlagRetriever;
use Ulid\Ulid;

final readonly class SearchFlagByNameController
{
    public function __construct(private FlagRetriever $fetcher)
    {
    }

    public function __invoke(string $flagName, Response $response): Response
    {
        try {
            $flag = $this->fetcher->flagByName(new FlagName($flagName));

            $response->getBody()->write(json_encode([
                'data' => [
                    'id' => $flag->name()->value(),
                    'type' => 'feature_flag',
                    'attributes' => [
                        'default_value' => $flag->default()->value(),
                        'value' => $flag->value()?->value()
                    ]
                ]
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
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