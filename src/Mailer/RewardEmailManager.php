<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Mailer;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
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
        CustomerInterface $customer,
        PromotionCouponInterface $coupon,
        ChannelInterface $channel,
        string $localeCode
    ): void {
        $this->emailSender->send(
            Emails::PROMOTION_REWARD,
            [$customer->getEmail()],
            [
                'coupon' => $coupon,
                'channel' => $channel,
                'localeCode' => $localeCode,
            ],
        );
    }
}
