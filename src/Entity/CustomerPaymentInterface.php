<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Sylius\Component\Order\Model\OrderInterface;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface CustomerPaymentInterface extends ResourceInterface, TimestampableInterface
{
    public const STATE_ON_HOLD = 'on_hold';
    public const STATE_NEW = 'new';
    public const STATE_PROCESSING = 'processing';
    public const STATE_COMPLETED = 'completed';
    public const STATE_CANCELLED = 'cancelled';
    public const STATE_REFUNDED = 'refunded';

    /**
     * @return string|null
     */
    public function getCurrencyCode(): ?string;

    /**
     * @param string|null $currencyCode
     */
    public function setCurrencyCode(?string $currencyCode): void;

    /**
     * @return int|null
     */
    public function getAmount(): ?int;

    /**
     * @param int|null $amount
     */
    public function setAmount(?int $amount): void;

    /**
     * @return string|null
     */
    public function getState(): ?string;

    /**
     * @param string|null $state
     */
    public function setState(?string $state): void;

    /**
     * @return array
     */
    public function getDetails(): ?array;

    /**
     * @param array $details
     */
    public function setDetails(array $details): void;

    /**
     * @return OrderInterface|null
     */
    public function getOrder(): ?OrderInterface;

    /**
     * @param OrderInterface|null $order
     */
    public function setOrder(?OrderInterface $order): void;

    /**
     * @return CustomerInterface|null
     */
    public function getCustomer(): ?CustomerInterface;

    /**
     * @param CustomerInterface|null $vendor
     */
    public function setCustomer(?CustomerInterface $vendor): void;

    /**
     * @return Collection
     */
    public function getReferralsPrograms(): ?Collection;

    /**
     * @param ReferralsProgramInterface|null $referralsProgram
     *
     * @return void
     */
    public function addReferralsProgram(?ReferralsProgramInterface $referralsProgram): void;
}
