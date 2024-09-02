<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\PackageDownloadsController;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Presentation\Controller
 */
final readonly class DownloadedPackageInfoRequestDTO
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $version
     */
    public function __construct(
        public string $name,
        public string $version,
    ) {}
}
