<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface AffiliateViewInterface extends
    ResourceInterface,
    TimestampableInterface
{
    public function setIp(?string $ip): void;

    public function getIp(): ?string;

    public function setCustomer(?CustomerInterface $customer): void;

    public function getCustomer(): ?CustomerInterface;

    public function setAffiliate(?AffiliateInterface $affiliate): void;

    public function getAffiliate(): ?AffiliateInterface;
}
