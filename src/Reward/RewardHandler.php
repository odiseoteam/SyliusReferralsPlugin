<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Reward;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralAwareInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Traversable;

final class RewardHandler implements RewardHandlerInterface
{
    private array $handlers;

    public function __construct(
        Traversable $handlers
    ) {
        $this->handlers = iterator_to_array($handlers);
    }

    public function apply(OrderInterface $order): void
    {
        if (!$order instanceof AffiliateReferralAwareInterface) {
            return;
        }

        if (null === $affiliateReferral = $order->getAffiliateReferral()) {
            return;
        }

        /** @var string $rewardType */
        $rewardType = $affiliateReferral->getRewardType();

        $this->resolve($rewardType)->apply($order);
    }

    private function resolve(string $rewardType): RewardHandlerInterface
    {
        return $this->handlers[$rewardType];
    }
}
