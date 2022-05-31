<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\EventListener;

use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgramInterface;
use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgramView;
use Odiseo\SyliusReferralsPlugin\Repository\ReferralsProgramRepository;
use Odiseo\SyliusReferralsPlugin\Repository\ReferralsProgramViewRepository;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class SetSessionFromReferralsProgramLink
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var ReferralsProgramRepository $referralsProgramRepository */
    private $referralsProgramRepository;

    /** @var ReferralsProgramViewRepository $referralsProgramViewRepository */
    private $referralsProgramViewRepository;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        ReferralsProgramRepository $referralsProgramRepository,
        ReferralsProgramViewRepository $referralsProgramViewRepository
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->referralsProgramRepository = $referralsProgramRepository;
        $this->referralsProgramViewRepository = $referralsProgramViewRepository;
    }

    public function setSession(RequestEvent $event): void
    {
        if ($event->getRequestType() !== HttpKernelInterface::MAIN_REQUEST) {
            return;
        }

        $request = $event->getRequest();

        /** @var Session $session */
        $session = $request->getSession();

        if (!$session->isStarted()) {
            return;
        }

        if (!$request->query->has(ReferralsProgramInterface::TOKEN_PARAM_NAME)) {
            return;
        }

        $tokenValue = $request->query->get(ReferralsProgramInterface::TOKEN_PARAM_NAME);
        if (null === $tokenValue) {
            return;
        }

        /** @var ReferralsProgramInterface|null $referralsProgram */
        $referralsProgram = $this->referralsProgramRepository->findOneBy([
            'tokenValue' => $tokenValue
        ]);
        if (null === $referralsProgram) {
            $session->getFlashBag()->add('error', 'The referral link is invalid!');

            return;
        }

        if ($referralsProgram->isExpired()) {
            $session->getFlashBag()->add('info', 'The link you followed has expired!');

            return;
        }

        $token = $this->tokenStorage->getToken();
        if (!$token instanceof TokenInterface) {
            return;
        }

        $referralsProgramView = new ReferralsProgramView();
        $referralsProgramView->setReferralsProgram($referralsProgram);
        $referralsProgramView->setIp($request->getClientIp());

        $shopUser = $token->getUser();

        if ($shopUser instanceof ShopUserInterface) {
            $customer = $shopUser->getCustomer();
            if ($customer instanceof CustomerInterface) {
                if ($customer === $referralsProgram->getCustomer()) {
                    return;
                }

                $referralsProgramView->setCustomer($customer);
            }
        }

        $this->referralsProgramViewRepository->add($referralsProgramView);

        $session->set(ReferralsProgramInterface::TOKEN_PARAM_NAME, $tokenValue);
    }
}
