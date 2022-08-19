## Installation

1. Run `composer require odiseoteam/sylius-referrals-plugin --no-scripts`

2. Enable the plugin in bundles.php

```php
<?php
// config/bundles.php

return [
    // ...
    Odiseo\SyliusReferralsPlugin\OdiseoSyliusReferralsPlugin::class => ['all' => true],
];
```

3. Import the plugin configurations

```yml
# config/packages/_sylius.yaml
imports:
    # ...
    - { resource: "@OdiseoSyliusReferralsPlugin/Resources/config/config.yaml" }
```

4. Add the shop route

```yml
# config/routes.yaml
odiseo_sylius_referrals_plugin_shop:
    resource: "@OdiseoSyliusReferralsPlugin/Resources/config/routing/shop.yaml"
    prefix: /{_locale}
    requirements:
        _locale: ^[A-Za-z]{2,4}(_([A-Za-z]{4}|[0-9]{3}))?(_([A-Za-z]{2}|[0-9]{3}))?$
```

5. Include traits and override the resources

```php
<?php
// src/Entity/Order/Order.php

// ...
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

    // ...
}
```

```php
<?php
// src/Repository/OrderRepository.php

// ...
use Odiseo\SyliusReferralsPlugin\Repository\OrderRepositoryInterface;
use Odiseo\SyliusReferralsPlugin\Repository\OrderRepositoryTrait;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository as BaseOrderRepository;

class OrderRepository extends BaseOrderRepository implements OrderRepositoryInterface
{
    use OrderRepositoryTrait;

    // ...
}
```

```yml
# config/packages/_sylius.yaml
sylius_order:
    resources:
        order:
            classes:
                repository: App\Repository\OrderRepository
```

6. Add the environment variables

```
# Add the promotion code to assign the reward coupon type
ODISEO_REFERRALS_PROMOTION_CODE=EDITME
```

7. Finish the installation updating the database schema and installing assets

```
php bin/console doctrine:migrations:migrate
php bin/console sylius:theme:assets:install
php bin/console cache:clear
```
