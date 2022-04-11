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
