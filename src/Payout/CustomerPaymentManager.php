<?php

/*
* This file is part of the Odiseo Referrals Plugin package, a commercial software.
* Only users who have purchased a valid license and accept to the terms of the License Agreement can install
* and use this program.
*
* Copyright (c) 2018-2022 Odiseo - Pablo D'amico
*/

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Payout;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgramInterface;
use Odiseo\SyliusReferralsPlugin\Entity\CustomerPaymentInterface;
use Odiseo\SyliusReferralsPlugin\Repository\CustomerPaymentRepositoryInterface;
use SM\Factory\FactoryInterface as StateMachineFactoryInterface;
use SM\StateMachine\StateMachineInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

final class CustomerPaymentManager implements CustomerPaymentManagerInterface
{
    /** @var CustomerPaymentRepositoryInterface */
    private $customerPaymentRepository;

    /** @var EntityRepository $referralsProgramRepository */
    private $referralsProgramRepository;

    /** @var FactoryInterface */
    private $customerPaymentFactory;

    /** @var EntityManager */
    private $customerPaymentManager;

    /** @var StateMachineFactoryInterface */
    private $stateMachineFactory;

    public function __construct(
        CustomerPaymentRepositoryInterface $customerPaymentRepository,
        EntityRepository $referralsProgramRepository,
        FactoryInterface $customerPaymentFactory,
        EntityManager $customerPaymentManager,
        StateMachineFactoryInterface $stateMachineFactory
    ) {
        $this->customerPaymentRepository = $customerPaymentRepository;
        $this->referralsProgramRepository = $referralsProgramRepository;
        $this->customerPaymentFactory = $customerPaymentFactory;
        $this->customerPaymentManager = $customerPaymentManager;
        $this->stateMachineFactory = $stateMachineFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createPayments(OrderInterface $order): void
    {
        $session = new Session();

        if (!$session->has(ReferralsProgramInterface::TOKEN_PARAM_NAME)) {
            return;
        }

        $referralsProgram = $this->referralsProgramRepository->findOneBy([
            'tokenValue' => $session->get(ReferralsProgramInterface::TOKEN_PARAM_NAME)
        ]);

        if (!$referralsProgram) {
            return;
        }

        $amount = (int) $order->getTotal();

        /** @var CustomerPaymentInterface $customerPayment */
        $customerPayment = $this->customerPaymentFactory->createNew();
        $customerPayment->setOrder($order);
        $customerPayment->setCustomer($referralsProgram->getCustomer());
        $customerPayment->setAmount($amount);
        $customerPayment->setCurrencyCode($order->getCurrencyCode());
        $customerPayment->addReferralsProgram($referralsProgram);

        $this->customerPaymentRepository->add($customerPayment);

        $session->remove(ReferralsProgramInterface::TOKEN_PARAM_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function markPaymentsAsNew(OrderInterface $order): void
    {
        if ($order->getId()) {
            /** @var Collection|CustomerPaymentInterface[] $payments */
            $payments = $this->customerPaymentRepository->findByOrder($order);

            foreach ($payments as $payment) {
                $stateMachine = $this->stateMachineFactory->get($payment, CustomerPaymentTransitions::GRAPH);

                $this->applyTransition($stateMachine, CustomerPaymentTransitions::TRANSITION_CREATE);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getReadyPayments(): array
    {
        return $this->customerPaymentRepository->findNew();
    }

    /**
     * @param StateMachineInterface $stateMachine
     * @param string $transition
     * @throws \SM\SMException
     */
    private function applyTransition(StateMachineInterface $stateMachine, string $transition): void
    {
        if ($stateMachine->can($transition)) {
            $stateMachine->apply($transition);
        }
    }
}
