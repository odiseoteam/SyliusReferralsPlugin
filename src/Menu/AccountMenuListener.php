<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AccountMenuListener
{
    public function addAccountMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $menu
            ->addChild('referrals_program', [
                'route' => 'odiseo_sylius_referrals_plugin_shop_account_referrals_program_index'
            ])
            ->setLabel('odiseo_sylius_referrals_plugin.ui.referrals_program')
            ->setLabelAttribute('icon', 'share')
        ;
    }
}
