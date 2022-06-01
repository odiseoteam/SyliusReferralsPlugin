<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Payout;

use Sylius\Component\Core\Model\OrderInterface;

interface CustomerPaymentManagerInterface
{
    public function createPayments(OrderInterface $order): void;

    public function markPaymentsAsNew(OrderInterface $order): void;

    public function getReadyPayments(): array;
}
