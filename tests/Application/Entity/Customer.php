<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusMarketplacePlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgramsTrait;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_customer")
 */
class Customer extends BaseCustomer 
{
    use ReferralsProgramsTrait;
}
