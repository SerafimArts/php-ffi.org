<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Response\DTO;

use JMS\Serializer\Annotation\SerializedName;

final readonly class DistReferenceResponseDTO extends ReferenceResponseDTO
{
    /**
     * @param non-empty-string $type
     * @param non-empty-string $url
     * @param non-empty-string|null $hash
     */
    public function __construct(
        string $type,
        string $url,
        #[SerializedName(name: 'reference')]
        public ?string $hash,
    ) {
        parent::__construct($type, $url);
    }
}
