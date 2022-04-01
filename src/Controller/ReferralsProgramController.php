<?php

namespace App\Controller\AffiliateProgram;

use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgram;
use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgramInterface;
//use App\Entity\Customer\CustomerInterface;
//use App\Entity\User\AdminUserInterface;
//use App\Entity\User\ShopUserInterface;
//use App\Repository\AffiliateProgram\AffiliateProgramRepository;
//use App\Repository\AffiliateProgram\AffiliateProgramViewRepository;
//use App\Repository\Customer\CustomerRepositoryInterface;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Webmozart\Assert\Assert;

class ReferralsProgramController extends ResourceController
{
    /*
    public function getStatistics(Request $request): Response
    {   
        $template = $request->get('template');

        $customer = $this->resolveCustomer($this->getUser());

        $dateTime = new \DateTime();
        $repository = $this->getAffiliateProgramRepository();

        $maxViewsReferedPage    = $repository->findMaxViewReferedPageByCustomer($customer);
        $maxProductReferedPage  = $repository->findMaxProductReferedPageByCustomer($customer);
        $pendingPayouts         = $repository->findPendingPayoutsByCustomer($customer);
        $average                = $this->averageByCustomer($customer);
        $monthReferrals         = $this->getAffiliateProgramViewRepository()->findMonthReferralsByCustomer($customer, $dateTime);
        $earnings               = $repository->findLastMonthEarningsByCustomer($customer, $dateTime);
        $totalEarnings          = $repository->totalEarningsByCustomer($customer);

        return $this->render($template, [
            'maxViewsReferedPage' => $maxViewsReferedPage,
            'maxProductReferedPage' => $maxProductReferedPage,
            'average' => $average,
            'pendingPayouts' => $pendingPayouts,
            'monthReferrals' => $monthReferrals,
            'earnings' => $earnings,
            'totalEarnings' => $totalEarnings
        ]);
    }
    */
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

        return $this->json(['link' => $referralsProgram->getLink()]);
    }

    private function getProductRepository(): ProductRepository
    {
        return $this->get('sylius.repository.product');
    }
/*
    private function getAffiliateProgramRepository(): AffiliateProgramRepository
    {
        return $this->get('app.repository.affiliate_program');
    }

    private function getAffiliateProgramViewRepository(): AffiliateProgramViewRepository
    {
        return $this->get('app.repository.affiliate_program_view');
    }

    private function getCustomerRepository(): CustomerRepositoryInterface
    {
        return $this->get('sylius.repository.customer');
    }
*/

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
/*
    private function resolveCustomer(UserInterface $user): CustomerInterface
    {
        if ($user instanceof AdminUserInterface) {
            $customer = $this->getCustomerRepository()->findCustomerByVendor($user->getVendor());
        } elseif ($user instanceof ShopUserInterface) {
            $customer = $user->getCustomer();
        }

        Assert::notNull($customer);

        return $customer;
    }

    private function averageByCustomer(CustomerInterface $customer): int
    {
        $sum = $this->getAffiliateProgramViewRepository()->findViewsByCustomer($customer);
        $count = $this->getAffiliateProgramRepository()->findCountPaymentsByCustomer($customer);

        if ($count == 0 || $sum == 0) {
            return 0;
        }

        return (int) (($count / $sum) * 100);
    }
*/    
}
