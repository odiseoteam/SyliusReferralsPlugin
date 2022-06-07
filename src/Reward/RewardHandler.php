<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Reward;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateAwareInterface;
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
        if (!$order instanceof AffiliateAwareInterface) {
            return;
        }

        if (null === $affiliate = $order->getAffiliate()) {
            return;
        }

        /** @var string $type */
        $type = $affiliate->getType();

        $this->resolve($type)->apply($order);
    }

    private function resolve(string $type): RewardHandlerInterface
    {
        return $this->handlers[$type];
    }
}
