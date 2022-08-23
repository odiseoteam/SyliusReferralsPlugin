<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Reward\Handler;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralAwareInterface;
use Odiseo\SyliusReferralsPlugin\Mailer\RewardEmailManagerInterface;
use Odiseo\SyliusReferralsPlugin\Reward\RewardHandlerInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PromotionCouponInterface;
use Sylius\Component\Core\Model\PromotionInterface;
use Sylius\Component\Core\Repository\PromotionRepositoryInterface;
use Sylius\Component\Promotion\Factory\PromotionCouponFactoryInterface;
use Sylius\Component\Promotion\Repository\PromotionCouponRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PromotionReward implements RewardHandlerInterface
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

    public function apply(OrderInterface $order): void
    {
        if (!$order instanceof AffiliateReferralAwareInterface) {
            return;
        }

        if (null === $affiliateReferral = $order->getAffiliateReferral()) {
            return;
        }

        /** @var AffiliateInterface $affiliate */
        $affiliate = $affiliateReferral->getAffiliate();
        if (!$affiliate instanceof CustomerInterface) {
            return;
        }

        /** @var PromotionInterface|null $promotion */
        $promotion = $this->promotionRepository->findOneBy([
            'code' => $this->promotionCode
        ]);

        if (null === $promotion) {
            throw new NotFoundHttpException();
        }

        if (!$promotion->isCouponBased()) {
            return;
        }

        $code = $this->generateCouponCode();

        /** @var PromotionCouponInterface $coupon */
        $coupon = $this->promotionCouponFactory->createForPromotion($promotion);
        $coupon->setCode($code);
        $coupon->setUsageLimit(1);
        $coupon->setPerCustomerUsageLimit(1);

        $this->promotionCouponRepository->add($coupon);

        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();
        /** @var string $localeCode */
        $localeCode = $order->getLocaleCode();

        $this->rewardEmailManager->sendPromotionEmail(
            $affiliate,
            $coupon,
            $channel,
            $localeCode
        );
    }

    private function generateCouponCode(): string
    {
        do {
            $hash = bin2hex(random_bytes(20));
            $code = strtoupper(substr($hash, 0, 8));
        } while (null !== $this->promotionCouponRepository->findOneBy(['code' => $code]));

        return $code;
    }
}
