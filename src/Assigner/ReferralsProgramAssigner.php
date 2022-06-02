<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Assigner;

use Doctrine\Persistence\ObjectManager;
use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgramInterface;
use Odiseo\SyliusReferralsPlugin\Repository\ReferralsProgramRepositoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Session\Session;

final class ReferralsProgramAssigner implements ReferralsProgramAssignerInterface
{
    private ObjectManager $referralsProgramManager;
    private ReferralsProgramRepositoryInterface $referralsProgramRepository;

    public function __construct(
        ObjectManager $referralsProgramManager,
        ReferralsProgramRepositoryInterface $referralsProgramRepository,
    ) {
        $this->referralsProgramManager = $referralsProgramManager;
        $this->referralsProgramRepository = $referralsProgramRepository;
    }

    public function assignOrder(OrderInterface $order): void
    {
        $session = new Session();

        if (!$session->has(ReferralsProgramInterface::TOKEN_PARAM_NAME)) {
            return;
        }

        /** @var ReferralsProgramInterface|null $referralsProgram */
        $referralsProgram = $this->referralsProgramRepository->findOneBy([
            'tokenValue' => $session->get(ReferralsProgramInterface::TOKEN_PARAM_NAME)
        ]);

        if (null === $referralsProgram) {
            return;
        }

        $referralsProgram->setOrder($order);

        $this->referralsProgramManager->flush();

        $session->remove(ReferralsProgramInterface::TOKEN_PARAM_NAME);
    }
}
