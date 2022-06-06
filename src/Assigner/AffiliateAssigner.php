<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Assigner;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateAwareInterface;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Repository\AffiliateRepositoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Session\Session;

final class AffiliateAssigner implements AffiliateAssignerInterface
{
    private AffiliateRepositoryInterface $affiliateRepository;

    public function __construct(
        AffiliateRepositoryInterface $affiliateRepository
    ) {
        $this->affiliateRepository = $affiliateRepository;
    }

    public function assign(OrderInterface $order): void
    {
        $session = new Session();

        if (!$session->has(AffiliateInterface::TOKEN_PARAM_NAME)) {
            return;
        }

        /** @var AffiliateInterface|null $affiliate */
        $affiliate = $this->affiliateRepository->findOneBy([
            'tokenValue' => $session->get(AffiliateInterface::TOKEN_PARAM_NAME)
        ]);

        if (null === $affiliate) {
            return;
        }

        if ($order instanceof AffiliateAwareInterface) {
            $order->setAffiliate($affiliate);
        }

        $session->remove(AffiliateInterface::TOKEN_PARAM_NAME);
    }
}
