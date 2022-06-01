<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Payout;

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
    private CustomerPaymentRepositoryInterface $customerPaymentRepository;
    private EntityRepository $referralsProgramRepository;
    private FactoryInterface $customerPaymentFactory;
    private StateMachineFactoryInterface $stateMachineFactory;

    public function __construct(
        CustomerPaymentRepositoryInterface $customerPaymentRepository,
        EntityRepository $referralsProgramRepository,
        FactoryInterface $customerPaymentFactory,
        StateMachineFactoryInterface $stateMachineFactory
    ) {
        $this->customerPaymentRepository = $customerPaymentRepository;
        $this->referralsProgramRepository = $referralsProgramRepository;
        $this->customerPaymentFactory = $customerPaymentFactory;
        $this->stateMachineFactory = $stateMachineFactory;
    }

    public function createPayments(OrderInterface $order): void
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

        $amount = $order->getTotal();

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

    public function markPaymentsAsNew(OrderInterface $order): void
    {
        if ($order->getId()) {
            $payments = $this->customerPaymentRepository->findByOrder($order);

            foreach ($payments as $payment) {
                $stateMachine = $this->stateMachineFactory->get($payment, CustomerPaymentTransitions::GRAPH);

                $this->applyTransition($stateMachine, CustomerPaymentTransitions::TRANSITION_CREATE);
            }
        }
    }

    public function getReadyPayments(): array
    {
        return $this->customerPaymentRepository->findNew();
    }

    private function applyTransition(StateMachineInterface $stateMachine, string $transition): void
    {
        if ($stateMachine->can($transition)) {
            $stateMachine->apply($transition);
        }
    }
}
