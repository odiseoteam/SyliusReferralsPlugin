<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\EventListener;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateView;
use Odiseo\SyliusReferralsPlugin\Repository\AffiliateRepositoryInterface;
use Odiseo\SyliusReferralsPlugin\Repository\AffiliateViewRepositoryInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SetSessionFromAffiliateLink
{
    private TokenStorageInterface $tokenStorage;
    private AffiliateRepositoryInterface $affiliateRepository;
    private AffiliateViewRepositoryInterface $affiliateViewRepository;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AffiliateRepositoryInterface $affiliateRepository,
        AffiliateViewRepositoryInterface $affiliateViewRepository
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->affiliateRepository = $affiliateRepository;
        $this->affiliateViewRepository = $affiliateViewRepository;
    }

    public function setSession(RequestEvent $event): void
    {
        if ($event->getRequestType() !== HttpKernelInterface::MAIN_REQUEST) {
            return;
        }

        $request = $event->getRequest();

        /** @var Session $session */
        $session = $request->getSession();

        if (!$request->query->has(AffiliateInterface::TOKEN_PARAM_NAME)) {
            return;
        }

        $tokenValue = $request->query->get(AffiliateInterface::TOKEN_PARAM_NAME);
        if (null === $tokenValue) {
            return;
        }

        if ($session->get(AffiliateInterface::TOKEN_PARAM_NAME) === $tokenValue) {
            return;
        }

        /** @var AffiliateInterface|null $affiliate */
        $affiliate = $this->affiliateRepository->findOneBy([
            'tokenValue' => $tokenValue
        ]);

        if (null === $affiliate) {
            $session->getFlashBag()->add('error', 'The referral link is invalid!');

            return;
        }

        if ($affiliate->isExpired()) {
            $session->getFlashBag()->add('info', 'The link you followed has expired!');

            return;
        }

        $token = $this->tokenStorage->getToken();
        if (!$token instanceof TokenInterface) {
            return;
        }

        $affiliateView = new AffiliateView();
        $affiliateView->setAffiliate($affiliate);
        $affiliateView->setIp($request->getClientIp());

        $shopUser = $token->getUser();

        if ($shopUser instanceof ShopUserInterface) {
            $customer = $shopUser->getCustomer();
            if ($customer instanceof CustomerInterface) {
                if ($customer === $affiliate->getCustomer()) {
                    return;
                }

                $affiliateView->setCustomer($customer);
            }
        }

        $this->affiliateViewRepository->add($affiliateView);

        $session->set(AffiliateInterface::TOKEN_PARAM_NAME, $tokenValue);
    }
}
