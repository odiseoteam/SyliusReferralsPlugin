<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\EventListener;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralInterface;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralView;
use Odiseo\SyliusReferralsPlugin\Repository\AffiliateReferralRepositoryInterface;
use Odiseo\SyliusReferralsPlugin\Repository\AffiliateReferralViewRepositoryInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SetSessionFromAffiliateReferralLink
{
    private TokenStorageInterface $tokenStorage;

    private AffiliateReferralRepositoryInterface $affiliateReferralRepository;

    private AffiliateReferralViewRepositoryInterface $affiliateReferralViewRepository;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AffiliateReferralRepositoryInterface $affiliateReferralRepository,
        AffiliateReferralViewRepositoryInterface $affiliateReferralViewRepository,
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->affiliateReferralRepository = $affiliateReferralRepository;
        $this->affiliateReferralViewRepository = $affiliateReferralViewRepository;
    }

    public function setSession(RequestEvent $event): void
    {
        if ($event->getRequestType() !== HttpKernelInterface::MAIN_REQUEST) {
            return;
        }

        $request = $event->getRequest();

        /** @var Session $session */
        $session = $request->getSession();

        if (!$request->query->has(AffiliateReferralInterface::TOKEN_PARAM_NAME)) {
            return;
        }

        $tokenValue = $request->query->get(AffiliateReferralInterface::TOKEN_PARAM_NAME);
        if (null === $tokenValue) {
            return;
        }

        if ($session->get(AffiliateReferralInterface::TOKEN_PARAM_NAME) === $tokenValue) {
            return;
        }

        /** @var AffiliateReferralInterface|null $affiliateReferral */
        $affiliateReferral = $this->affiliateReferralRepository->findOneBy([
            'tokenValue' => $tokenValue,
        ]);

        if (null === $affiliateReferral) {
            $session->getFlashBag()->add('error', 'The referral link is invalid!');

            return;
        }

        if ($affiliateReferral->isExpired()) {
            $session->getFlashBag()->add('info', 'The link you followed has expired!');

            return;
        }

        $token = $this->tokenStorage->getToken();
        if (!$token instanceof TokenInterface) {
            return;
        }

        $affiliateReferralView = new AffiliateReferralView();
        $affiliateReferralView->setAffiliateReferral($affiliateReferral);
        $affiliateReferralView->setIp($request->getClientIp());

        $shopUser = $token->getUser();

        if ($shopUser instanceof ShopUserInterface) {
            $customer = $shopUser->getCustomer();
            if ($customer instanceof AffiliateInterface) {
                if ($customer === $affiliateReferral->getAffiliate()) {
                    return;
                }
            }
        }

        $this->affiliateReferralViewRepository->add($affiliateReferralView);

        $session->set(AffiliateReferralInterface::TOKEN_PARAM_NAME, $tokenValue);
    }
}
