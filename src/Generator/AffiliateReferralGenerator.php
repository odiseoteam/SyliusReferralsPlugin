<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Generator;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralInterface;
use Odiseo\SyliusReferralsPlugin\Event\AffiliateReferralEvent;
use Odiseo\SyliusReferralsPlugin\Factory\AffiliateReferralFactoryInterface;
use Odiseo\SyliusReferralsPlugin\Repository\AffiliateReferralRepositoryInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class AffiliateReferralGenerator implements AffiliateReferralGeneratorInterface
{
    private AffiliateReferralFactoryInterface $affiliateReferralFactory;
    private AffiliateReferralRepositoryInterface $affiliateReferralRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        AffiliateReferralFactoryInterface $affiliateReferralFactory,
        AffiliateReferralRepositoryInterface $affiliateReferralRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->affiliateReferralFactory = $affiliateReferralFactory;
        $this->affiliateReferralRepository = $affiliateReferralRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function generate(
        AffiliateInterface $affiliate,
        ?ProductInterface $product = null
    ): AffiliateReferralInterface {
        $this->eventDispatcher->dispatch(
            new AffiliateReferralEvent(),
            AffiliateReferralEvent::PRE_GENERATE
        );

        $affiliateReferral = $this->affiliateReferralFactory->createForAffiliate($affiliate);
        $affiliateReferral->setRewardType(AffiliateReferralInterface::REWARD_TYPE_PROMOTION);
        if ($product instanceof ProductInterface) {
            $affiliateReferral->setProduct($product);
            $affiliateReferral->setExpiresAt(new \DateTime('+15 day'));
        }

        $this->eventDispatcher->dispatch(
            new AffiliateReferralEvent(),
            AffiliateReferralEvent::GENERATE
        );

        $this->affiliateReferralRepository->add($affiliateReferral);

        $this->eventDispatcher->dispatch(
            new AffiliateReferralEvent(),
            AffiliateReferralEvent::POST_GENERATE
        );

        return $affiliateReferral;
    }
}
