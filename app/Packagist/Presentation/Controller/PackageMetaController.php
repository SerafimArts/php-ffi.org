<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Controller;

use App\Packagist\Application\GetPackageInfo\GetPackageByNameStringQuery;
use App\Packagist\Application\GetPackageInfo\PackageInfo;
use App\Packagist\Presentation\Controller\PackageMetaController\PackageInfoResponseDTO;
use App\Packagist\Presentation\Controller\PackageMetaController\PackageInfoResponseTransformer;
use App\Shared\Domain\Bus\QueryBusInterface;
use App\Shared\Domain\DomainException;
use App\Shared\Presentation\Exception\Http\HttpPresentationException;
use App\Shared\Presentation\Exception\PresentationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Return versions list for a package.
 */
#[AsController]
#[Route('/package/meta/{package}~dev.json', name: 'package.meta.dev', methods: Request::METHOD_GET, priority: 2, stateless: true)]
#[Route('/package/meta/{package}.json', name: 'package.meta', methods: Request::METHOD_GET, priority: 1, stateless: true)]
final readonly class PackageMetaController
{
    public function __construct(
        private PackageInfoResponseTransformer $response,
        private QueryBusInterface $queries,
    ) {}

    private static function expectsDevPackages(?string $route): ?bool
    {
        if ($route === null) {
            return null;
        }

        return \str_ends_with($route, '.dev');
    }

    /**
     * @param non-empty-string $package
     * @param non-empty-string|null $_route A builtin (by Symfony) parameter
     *        containing the name of the current route.
     */
    public function __invoke(string $package, ?string $_route = null): PackageInfoResponseDTO
    {
        try {
            $info = $this->queries->get(new GetPackageByNameStringQuery(
                name: $package,
                dev: self::expectsDevPackages($_route),
            ));

            if (!$info instanceof PackageInfo) {
                throw new HttpPresentationException(
                    message: 'An internal error occurred while fetching package info',
                );
            }
        } catch (DomainException $e) {
            throw PresentationException::fromDomainException($e);
        }

        if ($info->packages === []) {
            throw (new HttpPresentationException('404 not found, no packages here'))
                ->setHttpStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->response->transform($info);
    }
}
