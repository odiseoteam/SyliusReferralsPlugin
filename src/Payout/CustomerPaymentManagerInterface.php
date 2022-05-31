<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Payout;

use Sylius\Component\Core\Model\OrderInterface;

interface CustomerPaymentManagerInterface
{
    /**
     * @param OrderInterface $order
     */
    public function createPayments(OrderInterface $order): void;

    /**
     * @param OrderInterface $order
     */
    public function markPaymentsAsNew(OrderInterface $order): void;

    /**
     * @return array
     */
    public function getReadyPayments(): array;
}
