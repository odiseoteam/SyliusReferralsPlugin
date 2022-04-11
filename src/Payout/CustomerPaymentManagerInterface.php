<?php

/*
* This file is part of the Odiseo Referrals Plugin package, a commercial software.
* Only users who have purchased a valid license and accept to the terms of the License Agreement can install
* and use this program.
*
* Copyright (c) 2018-2022 Odiseo - Pablo D'amico
*/

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Payout;

use Odiseo\SyliusReferralsPlugin\Entity\CustomerPaymentInterface;
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
