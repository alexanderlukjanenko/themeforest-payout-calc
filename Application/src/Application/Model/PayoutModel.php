<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Model;
use Application\Entity\Payout;


class PayoutModel
{
    private $period;
    private $start_date;
    private $stop_date;
    private $developer;
    private $items;
    private $developer_items_net_sales;
    private $total_money;
    private $fixed_money; // salary
    private $prepaid_money; // avans
    private $partner_money;
    private $default_sales;
    private $extended_support_sales;
    private $statementFile;
    private $details;

    private $_em;

    /*
     *
     */
    public function __construct($entityManager) {
        $this->_em = $entityManager;
    }



    public function set($developer, $periodId) {
        $this->developer = $developer;
        if (is_array($periodId)) {
            $this->start_date = $periodId['start_date'];
            $this->stop_date = $periodId['stop_date'];
        } else {
            $this->period = $periodId;
            $this->getPeriodById();
        }
    }



    public function getPeriodById() {

            if(isset($this->statementFile)) {

            } else {
                $this->statementFile = $this->_em->getRepository('Application\Entity\statementFile')->findOneBy(array('id' => $this->period));
            }

            if (isset($this->start_date)) {

            } else {
                $this->start_date = $this->statementFile->getStartDate();
            }

            if(isset($this->stop_date)) {

            } else {
                $d = $this->statementFile->getStopDate()->format('Y-m-d');
                $add = '23:59:59';
                $new = new \DateTime($d . ' ' . $add);
                $this->stop_date = $new;
            }

            $date = array('start_date' => $this->start_date, 'stop_date' => $this->stop_date);

            return $date;
    }



    public function getPeriod() {
        $start = $this->start_date;
        $stop = $this->stop_date;
        return $start->format('Y-m-d H:i:s') . ' - ' . $stop->format('Y-m-d H:i:s');
    }

    //get Partner money
    public function getPartnerMoney()
    {
        if (isset($this->default_sales)) {
            return ($this->default_sales + $this->extended_support_sales);
        } else {
            $this->calculateMoney();
            return ($this->default_sales + $this->extended_support_sales);
        }
    }

    //get Partner money
    // OLD DEPRICATED
    public function getPartnerMoneyOld()
    {
        if (isset($this->partner_money)) {
            return $this->partner_money;
        } else {
            $this->calculateMoney();
            return $this->partner_money;
        }
    }

    //get Item Sales (default)
    public function getDefaultSales()
    {
        if (isset($this->default_sales)) {
            return $this->default_sales;
        } else {
            $this->calculateMoney();
            return $this->default_sales;
        }
    }

    //get Item Sales (extended support)
    public function getExtendedSupportSales()
    {
        if (isset($this->extended_support_sales)) {
            return $this->extended_support_sales;
        } else {
            $this->calculateMoney();
            return $this->extended_support_sales;
        }
    }

    // calculate Money
    // Default sales (using Partner %)
    // Extended support sales (using PartnerSupport %)
    //
    // generate sales details per item
    public function calculateMoney() {
        if (isset($this->partner_money)) {
            return $this->partner_money;
        } else {
            $this->details = '';
            $this->items = $this->getPartnerItems();

            $allItemsMoney = 0;
            $allItemsSales = 0;
            $alItemExSupport = 0;
            foreach ($this->items as $partnerItem) {
                $itemModel = new ItemModel($this->_em);
                $itemModel->load($partnerItem->getItem()->getId());

                $itemMoney = round ($itemModel->getNetSales($this->start_date, $this->stop_date) * $partnerItem->getShare() / 100, 2);
                $itemSales = round ($itemModel->getItemSales($this->start_date, $this->stop_date) * $partnerItem->getShare() / 100, 2);
                $itemExSupport = round ($itemModel->getExtendedSupportSales($this->start_date, $this->stop_date) * $partnerItem->getSupportShare() / 100, 2);
                $this->details .= '' . $partnerItem->getItem()->getName() . '<br> '
                    . 'default = '
                    . $itemSales . '; extended suppor = '
                    . $itemExSupport . '<br>';

                $allItemsMoney += $itemMoney;
                $allItemsSales += $itemSales;
                $alItemExSupport += $itemExSupport;
            }

            $this->partner_money = round($allItemsMoney, 2);
            $this->default_sales = round($allItemsSales, 2);
            $this->extended_support_sales = round($alItemExSupport, 2);
        }
    }


    public function getPartnerItems() {
        $partnerItems = $this->_em->getRepository('Application\Entity\Partner')->findBy(array('developer' => $this->developer));
        return $partnerItems;
    }


    // salary
    public function getFixedMoney() {
        if(isset($this->fixed_money)) {
            return $this->fixed_money;
        } else {
            $this->fixed_money = $this->developer->getSalary();
            return round($this->fixed_money,2);
        }
    }

    //avans
    public function getPrepaidMoney() {
        if(isset($this->prepaid_money)) {
            return $this->prepaid_money;
        } else {
            $this->prepaid_money = $this->developer->getPrepaid();
            return round($this->prepaid_money,2);
        }
    }



    public function getTotal() {
        if ($this->getPrepaidMoney() > 0 ) { // if we pay avans to developer
            if ($this->getPrepaidMoney() > $this->getPartnerMoney()) { //if avans is BIGGER then %
                $this->total_money = $this->getPrepaidMoney();
            } else {
                $this->total_money = $this->getPartnerMoney();
            }
        } else {
            $this->total_money = $this->getFixedMoney() + $this->getPartnerMoney();
        }
        return $this->total_money;
    }



    public function getDeveloper(){
        return $this->developer;
    }



    /*
     *
     */
    public function getDeveloperItemsNetSales() {
        if (isset($this->developer_items_net_sales)) {
            return $this->developer_items_net_sales;
        } else {
            $developerItems = $this->getDeveloperItems();

            $allItemsMoney = 0;
            foreach ($developerItems as $item) {
                $itemModel = new ItemModel($this->_em);
                $itemModel->load($item->getId());

                $allItemsMoney += $itemModel->getNetSales($this->start_date, $this->stop_date);

            }

            $this->developer_items_net_sales = round($allItemsMoney, 2);
            return $this->developer_items_net_sales;
        }
    }



    public function getDeveloperItems() {
        return $this->developer->getItems();
    }



    public function getDetails() {
        return $this->details;
    }



    /*
     *
     */
    public function save() {
        $exsitPayout = $this->_em->getRepository('Application\Entity\Payout')->findOneBy(array('start_date' => $this->start_date, 'developer' => $this->developer));

        if($exsitPayout) {
            echo "Error! Payout for " . $this->developer->getName() . " is exist.<br>";
            return 0;
        } else {
            $payout = new Payout();
            $payout->setDeveloper($this->developer);
            $payout->setAmount($this->getTotal());
            $payout->setStartDate($this->start_date);
            $payout->setStopDate($this->stop_date);

            $this->_em->persist($payout);
            $this->_em->flush();

            return 1;
        }

    }



    /*
     *
     */
    public function getPayouts() {
        $dql = "SELECT SUM(s.amount) AS total FROM Application\Entity\Payout s " .
            "WHERE s.start_date >= ?1 AND s.start_date <= ?2 AND s.developer = ?3";

        $payoutTotal = $this->_em->createQuery($dql)
            ->setParameter(1, $this->start_date)
            ->setParameter(2, $this->stop_date)
            ->setParameter(3, $this->developer)
            ->getSingleScalarResult();

        return round($payoutTotal,2 );

    }
}