<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Payout;

final class CustomerPaymentTransitions
{
    public const GRAPH = 'odiseo_sylius_referrals_plugin_customer_payment';

    public const TRANSITION_CREATE = 'create';

    public const TRANSITION_PROCESS = 'process';

    public const TRANSITION_COMPLETE = 'complete';

    public const TRANSITION_CANCEL = 'cancel';

    public const TRANSITION_REFUND = 'refund';

    private function __construct()
    {
    }
}
