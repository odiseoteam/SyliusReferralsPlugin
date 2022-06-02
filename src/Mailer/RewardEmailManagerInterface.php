<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Mailer;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\PromotionCouponInterface;

interface RewardEmailManagerInterface
{
    public function sendEmail(
        CustomerInterface $customer,
        PromotionCouponInterface $coupon,
        ChannelInterface $channel,
        string $localeCode
    ): void;
}
