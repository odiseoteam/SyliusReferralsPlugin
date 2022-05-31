<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface ReferralsProgramViewInterface extends ResourceInterface
{
    /**
     * @param ReferralsProgramInterface|null $referralsProgram
     *
     * @return void
     */
    public function setReferralsProgram(?ReferralsProgramInterface $referralsProgram): void;

    /**
     * @return ReferralsProgramInterface|null
     */
    public function getReferralsProgram(): ?ReferralsProgramInterface;

    /**
     * @param CustomerInterface|null $customer
     *
     * @return void
     */
    public function setCustomer(?CustomerInterface $customer): void;

    /**
     * @return CustomerInterface|null
     */
    public function getCustomer(): ?CustomerInterface;

    /**
     * @param string|null $ip
     *
     * @return void
     */
    public function setIp(?string $ip): void;

    /**
     * @return string|null
     */
    public function getIp(): ?string;
}
