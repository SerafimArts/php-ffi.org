<?php

declare(strict_types=1);

namespace App\Package\Application\PackageInfo;

use App\Package\Domain\Package;

final readonly class PackageInfo
{
    /**
     * @param list<Package> $packages
     */
    public function __construct(
        public array $packages = [],
    ) {}
}
