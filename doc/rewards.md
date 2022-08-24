## Rewards

You can add rewards to the affiliates.
This plugin supports **Promotion Coupon** reward type, but you can create your own reward easily.

### Adding a new reward type

In the following example, a new reward type will be configured.

1. Set up a service for handling the new reward type

```php
<?php
// src/Reward/Handler/ExampleReward.php

use Odiseo\SyliusReferralsPlugin\Reward\RewardHandlerInterface;

class ExampleReward implements RewardHandlerInterface
{
    public function apply(OrderInterface $order): void
    {
        // your own logic
    }
}
```

```yml
# config/services.yaml
app.reward.handler.example:
    class: App\Reward\Handler\ExampleReward
    tags:
        - { name: 'odiseo_sylius_referrals_plugin.reward.reward_handler', key: 'example' }
```
