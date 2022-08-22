<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Mailer;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\PromotionCouponInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;

final class RewardEmailManager implements RewardEmailManagerInterface
{
    private SenderInterface $emailSender;

    public function __construct(
        SenderInterface $emailSender
    ) {
        $this->emailSender = $emailSender;
    }

    public function sendPromotionEmail(
        AffiliateInterface $affiliate,
        PromotionCouponInterface $coupon,
        ChannelInterface $channel,
        string $localeCode
    ): void {
        $this->emailSender->send(
            Emails::PROMOTION_REWARD,
            [$affiliate->getEmail()],
            [
                'coupon' => $coupon,
                'channel' => $channel,
                'localeCode' => $localeCode,
            ],
        );
    }
}
