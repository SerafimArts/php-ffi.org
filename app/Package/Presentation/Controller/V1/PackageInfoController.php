<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\V1;

use App\Package\Application\PackageInfo\PackageInfoFinder;
use App\Package\Presentation\Controller\V1\PackageInfoController\PackageInfoResponseDTO;
use App\Package\Presentation\Controller\V1\PackageInfoController\PackageInfoResponseTransformer;
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
#[Route('/package/v1/{package}.json', name: 'package.v1', methods: Request::METHOD_GET, stateless: true)]
#[Route('/package/v1/{package}{hash}.json', name: 'package.v1.hashed', methods: Request::METHOD_GET, stateless: true)]
final readonly class PackageInfoController
{
    public function __construct(
        private PackageInfoResponseTransformer $response,
        private PackageInfoFinder $finder,
    ) {}

    /**
     * @param non-empty-string $package
     * @param non-empty-string|null $_route A builtin (by Symfony) parameter
     *        containing the name of the current route.
     */
    public function __invoke(string $package, ?string $_route = null): PackageInfoResponseDTO
    {
        try {
            $instance = $this->finder->getByNameString($package);
        } catch (DomainException $e) {
            throw PresentationException::fromDomainException($e);
        }

        if ($instance->package === null) {
            throw (new HttpPresentationException('404 not found, no packages here'))
                ->setHttpStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->response->transform(
            entry: $instance->package,
        );
    }
}
