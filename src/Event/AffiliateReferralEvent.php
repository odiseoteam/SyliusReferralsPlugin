<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Event;

use Symfony\Component\EventDispatcher\GenericEvent;

final class AffiliateReferralEvent extends GenericEvent
{
    public const PRE_GENERATE = 'odiseo_sylius_referrals_plugin.affiliate_referral.pre_generate';

    public const GENERATE = 'odiseo_sylius_referrals_plugin.affiliate_referral.generate';

    public const POST_GENERATE = 'odiseo_sylius_referrals_plugin.affiliate_referral.post_generate';
}
