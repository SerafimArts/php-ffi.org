<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Controller;

use App\Packagist\Application\GetRepositoryInfo\GetRepositoryInfoQuery;
use App\Packagist\Application\GetRepositoryInfo\RepositoryInfo;
use App\Packagist\Presentation\Controller\RepositoryController\RepositoryResponseDTO;
use App\Packagist\Presentation\Controller\RepositoryController\RepositoryResponseTransformer;
use App\Shared\Domain\Bus\QueryBusInterface;
use App\Shared\Presentation\Exception\Http\HttpPresentationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Returns main metadata for all packages.
 */
#[AsController]
#[Route('/packages.json', name: 'repository', methods: Request::METHOD_GET, stateless: true)]
final readonly class RepositoryController
{
    public function __construct(
        private RepositoryResponseTransformer $response,
        private QueryBusInterface $queries,
    ) {}

    public function __invoke(Request $request): RepositoryResponseDTO
    {
        $result = $this->queries->get(new GetRepositoryInfoQuery());

        if (!$result instanceof RepositoryInfo) {
            throw new HttpPresentationException(
                message: 'An internal error occurred while fetching packages info',
            );
        }

        return $this->response->transform($result);
    }
}
