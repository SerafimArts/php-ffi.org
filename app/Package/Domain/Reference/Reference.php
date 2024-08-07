<?php

declare(strict_types=1);

namespace App\Package\Domain\Reference;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
abstract readonly class Reference
{
    /**
     * @param non-empty-string $type
     * @param non-empty-string $url
     */
    public function __construct(
        #[ORM\Column(type: 'string')]
        public string $type,
        #[ORM\Column(type: 'string')]
        public string $url,
    ) {}

    abstract public static function createEmpty(): self;

    public function isValid(): bool
    {
        return $this->type !== '' && $this->url !== '';
    }
}
