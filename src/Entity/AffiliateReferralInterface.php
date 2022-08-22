<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface AffiliateReferralInterface extends
    ResourceInterface,
    TimestampableInterface
{
    public const TOKEN_PARAM_NAME = 'token_value';

    public const REWARD_TYPE_PROMOTION = 'promotion';

    public function getTokenValue(): ?string;

    public function setTokenValue(?string $tokenValue): void;

    public function getType(): ?string;

    public function setType(?string $type): void;

    public function getExpiresAt(): ?\DateTimeInterface;

    public function setExpiresAt(?\DateTimeInterface $expiresAt): void;

    public function isExpired(): bool;

    public function getCustomer(): ?CustomerInterface;

    public function setCustomer(?CustomerInterface $customer): void;

    public function getProduct(): ?ProductInterface;

    public function setProduct(?ProductInterface $product): void;

    /**
     * @psalm-return Collection<array-key, AffiliateReferralViewInterface>
     */
    public function getViews(): Collection;

    public function hasView(AffiliateReferralViewInterface $view): bool;

    public function addView(AffiliateReferralViewInterface $view): void;

    public function removeView(AffiliateReferralViewInterface $view): void;
}
