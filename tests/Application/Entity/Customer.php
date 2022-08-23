<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusReferralsPlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_customer")
 */
class Customer extends BaseCustomer implements AffiliateInterface
{
}
