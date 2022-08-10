<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Controller\Shop;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Generator\AffiliateGeneratorInterface;
use Odiseo\SyliusReferralsPlugin\Repository\AffiliateRepositoryInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class CreateFromUserAffiliateAction
{
    private CustomerContextInterface $customerContext;
    private AffiliateGeneratorInterface $affiliateGenerator;
    private AffiliateRepositoryInterface $affiliateRepository;
    private RouterInterface $router;
    private Environment $twig;

    public function __construct(
        CustomerContextInterface $customerContext,
        AffiliateGeneratorInterface $affiliateGenerator,
        AffiliateRepositoryInterface $affiliateRepository,
        RouterInterface $router,
        Environment $twig
    ) {
        $this->customerContext = $customerContext;
        $this->affiliateGenerator = $affiliateGenerator;
        $this->affiliateRepository = $affiliateRepository;
        $this->router = $router;
        $this->twig = $twig;
    }

    public function __invoke(Request $request): Response
    {
        /** @var CustomerInterface|null $customer */
        $customer = $this->customerContext->getCustomer();
        if (null === $customer) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED);
        }

        $affiliate = $this->affiliateRepository->findOneByCustomerNotExpired($customer);
        if ($affiliate === null) {
            $affiliate = $this->affiliateGenerator->generate($customer);
        }

        $link = $this->generateLink($affiliate);

        $data = [
            'link' => $link,
        ];

        $content = $this->twig->render(
            '@OdiseoSyliusReferralsPlugin/Shop/Account/Affiliate/Index/Link/_template.html.twig',
            $data
        );

        return new Response($content);
    }

    private function generateLink(AffiliateInterface $affiliate): string
    {
        return $this->router->generate(
            'sylius_shop_homepage',
            [
                AffiliateInterface::TOKEN_PARAM_NAME => $affiliate->getTokenValue()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
