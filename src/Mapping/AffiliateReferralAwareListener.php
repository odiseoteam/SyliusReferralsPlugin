<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Mapping;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralAwareInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;

final class AffiliateReferralAwareListener implements EventSubscriber
{
    public function __construct(
        private RegistryInterface $resourceMetadataRegistry,
        private string $affiliateReferralClass,
    ) {
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
            $reflection->implementsInterface(AffiliateReferralAwareInterface::class)
        ) {
            $this->mapAffiliateReferralAware($classMetadata, 'affiliate_referral_id');
        }
    }

    private function mapAffiliateReferralAware(
        ClassMetadata $metadata,
        string $joinColumn,
        ?string $inversedBy = null,
    ): void {
        try {
            $affiliateReferralMetadata = $this->resourceMetadataRegistry->getByClass($this->affiliateReferralClass);
        } catch (\InvalidArgumentException $exception) {
            return;
        }

        if (!$metadata->hasAssociation('affiliateReferral')) {
            $mapping = [
                'fieldName' => 'affiliateReferral',
                'targetEntity' => $affiliateReferralMetadata->getClass('model'),
                'joinColumns' => [
                    [
                        'name' => $joinColumn,
                        'referencedColumnName' => 'id',
                    ],
                ],
                'cascade' => ['persist'],
            ];

            if (null !== $inversedBy) {
                $mapping['inversedBy'] = $inversedBy;
            }

            $metadata->mapManyToOne($mapping);
        }
    }
}
