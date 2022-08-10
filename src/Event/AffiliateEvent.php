<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Event;

use Symfony\Component\EventDispatcher\GenericEvent;

final class AffiliateEvent extends GenericEvent
{
    public const PRE_GENERATE = 'odiseo_sylius_referrals_plugin.affiliate.pre_generate';

    public const GENERATE = 'odiseo_sylius_referrals_plugin.affiliate.generate';

    public const POST_GENERATE = 'odiseo_sylius_referrals_plugin.affiliate.post_generate';
}
