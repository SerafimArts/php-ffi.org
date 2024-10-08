<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Response\Transformer;

use App\Packagist\Domain\Release\Reference\DistReference;
use App\Packagist\Presentation\Response\DTO\DistReferenceResponseDTO;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @template-extends ResponseTransformer<DistReference, DistReferenceResponseDTO>
 */
final readonly class DistReferenceResponseTransformer extends ResponseTransformer
{
    public function transform(mixed $entry): DistReferenceResponseDTO
    {
        return new DistReferenceResponseDTO(
            type: $entry->type,
            url: $entry->url,
            hash: $entry->hash,
        );
    }
}
