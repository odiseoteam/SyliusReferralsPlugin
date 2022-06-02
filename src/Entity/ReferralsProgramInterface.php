<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface ReferralsProgramInterface extends
    ResourceInterface,
    TimestampableInterface
{
    public const TOKEN_PARAM_NAME = 'referrals_token_value';

    public function getTokenValue(): ?string;

    public function setTokenValue(?string $tokenValue): void;

    public function getLink(): ?string;

    public function setLink(?string $link): void;

    public function getCustomer(): ?CustomerInterface;

    public function setCustomer(?CustomerInterface $customer): void;

    public function getProduct(): ?ProductInterface;

    public function setProduct(?ProductInterface $product): void;

    public function getOrder(): ?OrderInterface;

    public function setOrder(?OrderInterface $order): void;

    /**
     * @psalm-return Collection<array-key, ReferralsProgramViewInterface>
     */
    public function getViews(): Collection;

    public function hasView(ReferralsProgramViewInterface $view): bool;

    public function addView(ReferralsProgramViewInterface $view): void;

    public function removeView(ReferralsProgramViewInterface $view): void;

    public function getExpiresAt(): ?\DateTimeInterface;

    public function setExpiresAt(?\DateTimeInterface $expiresAt): void;

    public function isExpired(): bool;
}
