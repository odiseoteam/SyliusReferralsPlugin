<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Reward;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateAwareInterface;
use Odiseo\SyliusReferralsPlugin\Mailer\RewardEmailManagerInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PromotionCouponInterface;
use Sylius\Component\Core\Model\PromotionInterface;
use Sylius\Component\Core\Repository\PromotionRepositoryInterface;
use Sylius\Component\Promotion\Factory\PromotionCouponFactoryInterface;
use Sylius\Component\Promotion\Repository\PromotionCouponRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class RewardManager implements RewardManagerInterface
{
    private PromotionRepositoryInterface $promotionRepository;
    private PromotionCouponFactoryInterface $promotionCouponFactory;
    private PromotionCouponRepositoryInterface $promotionCouponRepository;
    private RewardEmailManagerInterface $rewardEmailManager;
    private string $promotionCode;

    public function __construct(
        PromotionRepositoryInterface $promotionRepository,
        PromotionCouponFactoryInterface $promotionCouponFactory,
        PromotionCouponRepositoryInterface $promotionCouponRepository,
        RewardEmailManagerInterface $rewardEmailManager,
        string $promotionCode
    ) {
        $this->promotionRepository = $promotionRepository;
        $this->promotionCouponFactory = $promotionCouponFactory;
        $this->promotionCouponRepository = $promotionCouponRepository;
        $this->rewardEmailManager = $rewardEmailManager;
        $this->promotionCode = $promotionCode;
    }

    public function create(OrderInterface $order): void
    {
        if (!$order instanceof AffiliateAwareInterface) {
            return;
        }

        if (null === $affiliate = $order->getAffiliate()) {
            return;
        }

        /** @var PromotionInterface|null $promotion */
        $promotion = $this->promotionRepository->findOneBy([
            'code' => $this->promotionCode
        ]);

        if (null === $promotion) {
            throw new NotFoundHttpException();
        }

        /** @var PromotionCouponInterface $coupon */
        $coupon = $this->promotionCouponFactory->createForPromotion($promotion);
        $coupon->setCode($order->getNumber());
        $coupon->setUsageLimit(1);
        $coupon->setPerCustomerUsageLimit(1);

        $this->promotionCouponRepository->add($coupon);

        /** @var CustomerInterface $customer */
        $customer = $affiliate->getCustomer();
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();
        /** @var string $localeCode */
        $localeCode = $order->getLocaleCode();

        $this->rewardEmailManager->sendEmail(
            $customer,
            $coupon,
            $channel,
            $localeCode
        );
    }
}
