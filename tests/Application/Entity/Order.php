<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusReferralsPlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateAwareInterface;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_order")
 */
class Order extends BaseOrder implements AffiliateAwareInterface
{
    use AffiliateTrait;
}
