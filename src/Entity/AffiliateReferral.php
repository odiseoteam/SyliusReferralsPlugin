<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;

class AffiliateReferral implements AffiliateReferralInterface
{
    use TimestampableTrait;

    protected ?int $id = null;
    protected ?string $tokenValue = null;
    protected ?string $rewardType = null;
    protected ?\DateTimeInterface $expiresAt = null;
    protected ?AffiliateInterface $affiliate = null;
    protected ?ProductInterface $product = null;

    /**
     * @psalm-var Collection<array-key, AffiliateReferralViewInterface>
     */
    protected Collection $views;

    public function __construct()
    {
        $this->views = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTokenValue(): ?string
    {
        return $this->tokenValue;
    }

    public function setTokenValue(?string $tokenValue): void
    {
        $this->tokenValue = $tokenValue;
    }

    public function getRewardType(): ?string
    {
        return $this->rewardType;
    }

    public function setRewardType(?string $rewardType): void
    {
        $this->rewardType = $rewardType;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeInterface $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    public function isExpired(): bool
    {
        if ($this->expiresAt === null) {
            return false;
        }

        $now = new \DateTime();

        return $now > $this->expiresAt;
    }

    public function getAffiliate(): ?AffiliateInterface
    {
        return $this->affiliate;
    }

    public function setAffiliate(?AffiliateInterface $affiliate): void
    {
        $this->affiliate = $affiliate;
    }

    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }

    public function getViews(): Collection
    {
        return $this->views;
    }

    public function hasView(AffiliateReferralViewInterface $view): bool
    {
        return $this->views->contains($view);
    }

    public function addView(AffiliateReferralViewInterface $view): void
    {
        if (!$this->hasView($view)) {
            $this->views->add($view);
            $view->setAffiliateReferral($this);
        }
    }

    public function removeView(AffiliateReferralViewInterface $view): void
    {
        if ($this->hasView($view)) {
            $this->views->removeElement($view);
            $view->setAffiliateReferral(null);
        }
    }
}
