<?php

namespace Odiseo\SyliusReferralsPlugin\Controller;

use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgram;
use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgramInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
//use App\Entity\User\AdminUserInterface;
//use App\Entity\User\ShopUserInterface;
use Odiseo\SyliusReferralsPlugin\Repository\ReferralsProgramRepository;
use Odiseo\SyliusReferralsPlugin\Repository\ReferralsProgramViewRepository;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Webmozart\Assert\Assert;

class ReferralsProgramController extends ResourceController
{
    public function getStatistics(Request $request): Response
    {   
        $template = $request->get('template');
        
        $customer = $this->getUser()->getCustomer();

        $dateTime = new \DateTime();
        $repository = $this->getReferralsProgramRepository();

        $maxViewsReferedPage    = $repository->findMaxViewReferedPageByCustomer($customer);
        $maxProductReferedPage  = $repository->findMaxProductReferedPageByCustomer($customer);
        $average                = $this->averageByCustomer($customer);
        $monthReferrals         = $this->getReferralsProgramViewRepository()->findMonthReferralsByCustomer($customer, $dateTime);

        return $this->render($template, [
            'maxViewsReferedPage' => $maxViewsReferedPage,
            'maxProductReferedPage' => $maxProductReferedPage,
            'average' => $average,
            'monthReferrals' => $monthReferrals,
        ]);
    }

    public function createFromProduct(Request $request): Response
    {
        $product = $this->getProductRepository()->find($request->query->getInt('product'));
        Assert::notNull($product);
        
        $customer = $this->getUser()->getCustomer();
        Assert::notNull($customer);

        $referralsProgram = new ReferralsProgram();
        $referralsProgram->setCustomer($customer);
        $referralsProgram->setProduct($product);

        $this->generateLink($referralsProgram);

        $em = $this->getDoctrine()->getManager();
        $em->persist($referralsProgram);
        $em->flush();

        return $this->json([
            'link' => $referralsProgram->getLink(), 
            'responseURL' => $request->getUri(),
        ]);
    }

    private function getProductRepository(): ProductRepository
    {
        return $this->get('sylius.repository.product');
    }

    private function getReferralsProgramRepository(): ReferralsProgramRepository
    {
        return $this->get('odiseo_sylius_referrals_plugin.repository.referrals_program');
    }

    private function getReferralsProgramViewRepository(): ReferralsProgramViewRepository
    {
        return $this->get('odiseo_sylius_referrals_plugin.repository.referrals_program_view');
    }

    private function generateLink(ReferralsProgramInterface $referralsProgram): void
    {
        $referralsProgram->setTokenValue($this->generateTokenValue());

        $link = $this->get('router')->generate(
            'sylius_shop_product_show',
            [
                'slug' => $referralsProgram->getProduct()->getSlug(),
                ReferralsProgramInterface::TOKEN_PARAM_NAME => $referralsProgram->getTokenValue()
            ],
            UrlGenerator::ABSOLUTE_URL
        );
        $referralsProgram->setLink($link);
    }

    private function generateTokenValue(): string
    {
        return uniqid('ap_', true);
    }

    private function averageByCustomer(CustomerInterface $customer): int
    {
        $sum = $this->getReferralsProgramViewRepository()->findViewsByCustomer($customer);
        $count = $this->getReferralsProgramRepository()->findCountPaymentsByCustomer($customer);

        if ($count == 0 || $sum == 0) {
            return 0;
        }

        return (int) (($count / $sum) * 100);
    }
}
