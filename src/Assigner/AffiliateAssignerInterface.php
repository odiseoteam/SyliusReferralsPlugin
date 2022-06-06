<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Assigner;

use Sylius\Component\Core\Model\OrderInterface;

interface AffiliateAssignerInterface
{
    public function assign(OrderInterface $order): void;
}
