<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Mailer;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\PromotionCouponInterface;

interface RewardEmailManagerInterface
{
    public function sendPromotionEmail(
        AffiliateInterface $affiliate,
        PromotionCouponInterface $coupon,
        ChannelInterface $channel,
        string $localeCode
    ): void;
}
