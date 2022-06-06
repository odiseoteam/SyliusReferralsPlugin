<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;

class Affiliate implements AffiliateInterface
{
    use TimestampableTrait;

    protected ?int $id = null;
    protected ?string $tokenValue = null;
    protected ?\DateTimeInterface $expiresAt = null;
    protected ?CustomerInterface $customer = null;
    protected ?ProductInterface $product = null;

    /**
     * @psalm-var Collection<array-key, AffiliateViewInterface>
     */
    protected Collection $views;

    public function __construct()
    {
        $this->expiresAt = new \DateTime();
        $this->expiresAt->modify('+15 day');

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
        $now = new \DateTime();

        return $now > $this->expiresAt;
    }

    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    public function setCustomer(?CustomerInterface $customer): void
    {
        $this->customer = $customer;
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

    public function hasView(AffiliateViewInterface $view): bool
    {
        return $this->views->contains($view);
    }

    public function addView(AffiliateViewInterface $view): void
    {
        if (!$this->hasView($view)) {
            $this->views->add($view);
            $view->setAffiliate($this);
        }
    }

    public function removeView(AffiliateViewInterface $view): void
    {
        if ($this->hasView($view)) {
            $this->views->removeElement($view);
            $view->setAffiliate(null);
        }
    }
}
