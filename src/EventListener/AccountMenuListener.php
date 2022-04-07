<?php

namespace Odiseo\SyliusReferralsPlugin\EventListener;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AccountMenuListener
{
    public function addAccountMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $menu
            ->addChild('account_referrals_program', [
                'route' => 'odiseo_sylius_referrals_plugin_shop_referrals_program_index'
            ])
            ->setLabel('odiseo_sylius_referrals_plugin.ui.referrals_program_stats')
            ->setLabelAttribute('icon', 'share')
        ;
    }
}