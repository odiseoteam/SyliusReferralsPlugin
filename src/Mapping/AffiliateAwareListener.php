<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Mapping;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateAwareInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;

final class AffiliateAwareListener implements EventSubscriber
{
    private RegistryInterface $resourceMetadataRegistry;
    private string $affiliateClass;

    public function __construct(
        RegistryInterface $resourceMetadataRegistry,
        string $affiliateClass
    ) {
        $this->resourceMetadataRegistry = $resourceMetadataRegistry;
        $this->affiliateClass = $affiliateClass;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $reflection = $classMetadata->reflClass;

        /**
         * @phpstan-ignore-next-line
         */
        if ($reflection === null || $reflection->isAbstract()) {
            return;
        }

        if (
            $reflection->implementsInterface(OrderInterface::class) &&
            $reflection->implementsInterface(AffiliateAwareInterface::class)
        ) {
            $this->mapAffiliatesAware($classMetadata, 'affiliate_id');
        }
    }

    private function mapAffiliatesAware(ClassMetadata $metadata, string $joinColumn, ?string $inversedBy = null): void
    {
        try {
            $affiliateMetadata = $this->resourceMetadataRegistry->getByClass($this->affiliateClass);
        } catch (\InvalidArgumentException $exception) {
            return;
        }

        if (!$metadata->hasAssociation('affiliates')) {
            $mapping = [
                'fieldName' => 'affiliate',
                'targetEntity' => $affiliateMetadata->getClass('model'),
                'joinColumns' => [
                    [
                        'name' => $joinColumn,
                        'referencedColumnName' => 'id',
                    ]
                ],
                'cascade' => ['persist']
            ];

            if (null !== $inversedBy) {
                $mapping['inversedBy'] = $inversedBy;
            }

            $metadata->mapManyToOne($mapping);
        }
    }
}
