<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface ReferralsProgramViewInterface extends ResourceInterface
{
    public function setReferralsProgram(?ReferralsProgramInterface $referralsProgram): void;

    public function getReferralsProgram(): ?ReferralsProgramInterface;

    public function setCustomer(?CustomerInterface $customer): void;

    public function getCustomer(): ?CustomerInterface;

    public function setIp(?string $ip): void;

    public function getIp(): ?string;
}
