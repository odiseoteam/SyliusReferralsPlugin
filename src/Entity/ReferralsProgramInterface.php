<?php

/*
* This file is part of the Odiseo Referrals Plugin package, a commercial software.
* Only users who have purchased a valid license and accept to the terms of the License Agreement can install
* and use this program.
*
* Copyright (c) 2018-2022 Odiseo - Pablo D'amico
*/

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface ReferralsProgramInterface extends ResourceInterface, TimestampableInterface
{
    public const TOKEN_PARAM_NAME = 'referrals_token_value';

    /**
     * @return string|null
     */
    public function getTokenValue(): ?string;

    /**
     * @param string $tokenValue
     *
     * @return void
     */
    public function setTokenValue(string $tokenValue): void;

    /**
     * @return string|null
     */
    public function getLink(): ?string;

    /**
     * @param string $link
     *
     * @return void
     */
    public function setLink(string $link): void;

    /**
     * @return CustomerInterface|null
     */
    public function getCustomer(): ?CustomerInterface;

    /**
     * @param CustomerInterface|null $customer
     *
     * @return void
     */
    public function setCustomer(?CustomerInterface $customer): void;

    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface;

    /**
     * @param ProductInterface $product
     *
     * @return void
     */
    public function setProduct(ProductInterface $product): void;

    /**
     * @return Collection
     */
    public function getViews(): Collection;

    /**
     * @param ReferralsProgramViewInterface|null $view
     *
     * @return void
     */
    public function addView(?ReferralsProgramViewInterface $view): void;

    /**
     * @param ReferralsProgramViewInterface|null $view
     *
     * @return void
     */
    public function removeView(?ReferralsProgramViewInterface $view): void;

    /**
     * @param CustomerPaymentInterface $payment
     *
     * @return void
     */
    public function addPayment(CustomerPaymentInterface $payment): void;

    /**
     * @param CustomerPaymentInterface $payment
     *
     * @return void
     */
    public function removePayment(CustomerPaymentInterface $payment): void;    

    /**
     * @return \DateTimeInterface|null
     */
    public function getExpireAt(): ?\DateTimeInterface;

    /**
     * @param \DateTimeInterface $expireAt
     *
     * @return void
     */
    public function setExpireAt(\DateTimeInterface $expireAt): void;
}
