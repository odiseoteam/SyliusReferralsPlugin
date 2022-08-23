<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Controller\Shop;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralInterface;
use Odiseo\SyliusReferralsPlugin\Generator\AffiliateReferralGeneratorInterface;
use Odiseo\SyliusReferralsPlugin\Repository\AffiliateReferralRepositoryInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class CreateFromUserAffiliateReferralAction
{
    private CustomerContextInterface $customerContext;
    private AffiliateReferralGeneratorInterface $affiliateReferralGenerator;
    private AffiliateReferralRepositoryInterface $affiliateReferralRepository;
    private RouterInterface $router;
    private Environment $twig;

    public function __construct(
        CustomerContextInterface $customerContext,
        AffiliateReferralGeneratorInterface $affiliateReferralGenerator,
        AffiliateReferralRepositoryInterface $affiliateReferralRepository,
        RouterInterface $router,
        Environment $twig
    ) {
        $this->customerContext = $customerContext;
        $this->affiliateReferralGenerator = $affiliateReferralGenerator;
        $this->affiliateReferralRepository = $affiliateReferralRepository;
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

        if (!$customer instanceof AffiliateInterface) {
            throw new NotFoundHttpException();
        }

        $affiliateReferral = $this->affiliateReferralRepository->findOneByAffiliateNotExpired($customer);
        if ($affiliateReferral === null) {
            $affiliateReferral = $this->affiliateReferralGenerator->generate($customer);
        }

        $link = $this->generateLink($affiliateReferral);

        $data = [
            'link' => $link,
        ];

        $content = $this->twig->render(
            '@OdiseoSyliusReferralsPlugin/Shop/Account/AffiliateReferral/Index/Link/_template.html.twig',
            $data
        );

        return new Response($content);
    }

    private function generateLink(AffiliateReferralInterface $affiliateReferral): string
    {
        return $this->router->generate(
            'sylius_shop_homepage',
            [
                AffiliateReferralInterface::TOKEN_PARAM_NAME => $affiliateReferral->getTokenValue()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
