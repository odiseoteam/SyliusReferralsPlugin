<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Generator;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Event\AffiliateEvent;
use Odiseo\SyliusReferralsPlugin\Factory\AffiliateFactoryInterface;
use Odiseo\SyliusReferralsPlugin\Repository\AffiliateRepositoryInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class AffiliateGenerator implements AffiliateGeneratorInterface
{
    private AffiliateFactoryInterface $affiliateFactory;
    private AffiliateRepositoryInterface $affiliateRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        AffiliateFactoryInterface $affiliateFactory,
        AffiliateRepositoryInterface $affiliateRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->affiliateFactory = $affiliateFactory;
        $this->affiliateRepository = $affiliateRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function generate(CustomerInterface $customer, ?ProductInterface $product = null): AffiliateInterface
    {
        $this->eventDispatcher->dispatch(
            new AffiliateEvent(),
            AffiliateEvent::PRE_GENERATE
        );

        $affiliate = $this->affiliateFactory->createForCustomer($customer);
        $affiliate->setType(AffiliateInterface::REWARD_TYPE_PROMOTION);
        if ($product instanceof ProductInterface) {
            $affiliate->setProduct($product);
            $affiliate->setExpiresAt(new \DateTime('+15 day'));
        }

        $this->eventDispatcher->dispatch(
            new AffiliateEvent(),
            AffiliateEvent::GENERATE
        );

        $this->affiliateRepository->add($affiliate);

        $this->eventDispatcher->dispatch(
            new AffiliateEvent(),
            AffiliateEvent::POST_GENERATE
        );

        return $affiliate;
    }
}
