<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Controller\PackageProviderController;

use App\Packagist\Presentation\Response\DTO\PackageReleaseResponseDTO;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Packagist\Presentation\Controller
 */
final readonly class PackageProviderResponseDTO
{
    /**
     * @param PackageReleaseResponseDTO $packages
     */
    public function __construct(
        public iterable $packages,
    ) {}
}
