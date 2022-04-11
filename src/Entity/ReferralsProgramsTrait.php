<?php

/*
* This file is part of the Odiseo Referrals Plugin package, a commercial software.
* Only users who have purchased a valid license and accept to the terms of the License Agreement can install
* and use this program.
*
* Copyright (c) 2018-2022 Odiseo - Pablo D'amico
*/

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Entity;

use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgramInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait ReferralsProgramsTrait 
{
    /**
     * @ORM\OneToMany(targetEntity="Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgram", mappedBy="customer")
     */
    private $referralsPrograms;

    /**
     * @ORM\OneToMany(targetEntity="Odiseo\SyliusReferralsPlugin\Entity\CustomerPayment", mappedBy="customer")
     *
     * @var Collection|VendorPaymentInterface[]
     */
    protected $payments;    

    public function __construct()
    {
        parent::__construct();
        
        $this->referralsPrograms = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getReferralsPrograms(): Collection
    {
        return $this->referralsPrograms;
    }

    /**
     * {@inheritdoc}
     */
    public function addReferralsProgram(?ReferralsProgramInterface $referralProgram): void
    {
        if (!$this->referralsPrograms->contains($referralProgram)) {
            $this->referralsPrograms->add($referralProgram);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeReferralsProgram(?ReferralsProgramInterface $referralProgram): void
    {
        if ($this->referralsPrograms->contains($referralProgram)) {
            $this->referralsPrograms->removeElement($referralProgram);
        }
    }
}
