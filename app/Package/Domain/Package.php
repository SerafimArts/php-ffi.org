<?php

declare(strict_types=1);

namespace App\Package\Domain;

use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\CreatedDateProviderInterface;
use App\Shared\Domain\Date\UpdatedDateProvider;
use App\Shared\Domain\Date\UpdatedDateProviderInterface;
use App\Shared\Domain\Id\IdentifiableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @final impossible to specify "final" attribute natively due
 *        to a Doctrine bug https://github.com/doctrine/orm/issues/7598
 */
#[ORM\Entity]
#[ORM\Table(name: 'packages')]
#[ORM\UniqueConstraint(name: 'package_name_idx', columns: ['name'])]
class Package implements
    IdentifiableInterface,
    CreatedDateProviderInterface,
    UpdatedDateProviderInterface
{
    use CreatedDateProvider;
    use UpdatedDateProvider;

    /**
     * @readonly impossible to specify "readonly" attribute natively due
     *           to a Doctrine bug https://github.com/doctrine/orm/issues/9863
     */
    #[ORM\Id]
    #[ORM\Column(type: PackageId::class)]
    public PackageId $id;

    #[ORM\Embedded(class: Credentials::class, columnPrefix: false)]
    public Credentials $credentials;

    /**
     * @var Collection<array-key, PackageVersion>
     * @readonly
     */
    #[ORM\OneToMany(targetEntity: PackageVersion::class, mappedBy: 'package', cascade: ['ALL'], orphanRemoval: true)]
    #[ORM\OrderBy(['version' => 'DESC', 'createdAt' => 'ASC'])]
    public Collection $versions;

    public function __construct(
        Credentials $credentials,
        ?PackageId $id = null,
    ) {
        $this->credentials = $credentials;
        $this->versions = new ArrayCollection();
        $this->id = $id ?? PackageId::new();
    }
}
