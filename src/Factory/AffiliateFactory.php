<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Factory;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class AffiliateFactory implements AffiliateFactoryInterface
{
    private FactoryInterface $decoratedFactory;

    public function __construct(FactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }

    public function createNew(): object
    {
        return $this->decoratedFactory->createNew();
    }

    public function createForCustomer(CustomerInterface $customer): AffiliateInterface
    {
        /** @var AffiliateInterface $affiliate */
        $affiliate = $this->decoratedFactory->createNew();
        $affiliate->setCustomer($customer);
        $affiliate->setTokenValue($this->generateTokenValue());

        return $affiliate;
    }

    private function generateTokenValue(): string
    {
        return uniqid('ap_', true);
    }
}
