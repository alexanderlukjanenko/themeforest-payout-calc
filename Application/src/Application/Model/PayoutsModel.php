<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Model;



class PayoutsModel
{
    private $period;
    private $total;
    private $payouts;

    private $_em;

    /*
     *
     */
    public function __construct($entityManager) {
        $this->_em = $entityManager;
        $this->payouts = array();
    }



    /*
     * Period ID, based on statement file
     */
    public function setPeriod($val) {
        $this->period = $val;
    }



    /*
     *
     */
    public function load() {
        $developers = $this->_em->getRepository('Application\Entity\Developer')->findAll();

        foreach ($developers as $developer) {
            $payout = new PayoutModel($this->_em);
            $payout->set($developer, $this->period);
            $this->payouts[] = $payout;
        }
    }

    public function getPayouts() {
        return $this->payouts;
    }

    public function getPeriodText() {
        return $this->payouts[0]->getPeriod();
    }

    public function getTotal() {
        if(isset($this->total)) {

        } else {
            $this->total = 0;
            foreach($this->payouts as $payout) {
                $this->total += $payout->getTotal();
            }
        }

        return $this->total;
    }

    public function getEarnings(){
        $dates = $this->payouts[0]->getPeriodById();

        $dql = "SELECT SUM(s.amount) AS balance FROM Application\Entity\Statement s " .
            "WHERE s.date >= ?1 AND s.date <= ?2 AND s.type NOT LIKE '%Withdrawal%'";

        $netSales = $this->_em->createQuery($dql)
            ->setParameter(1, $dates['start_date']->format('Y-m-d H:i:s'))
            ->setParameter(2, $dates['stop_date']->format('Y-m-d H:i:s'))
            ->getSingleScalarResult();

        return round($netSales,2 );
    }



    public function getEarningsPerAccount(){
        $dates = $this->payouts[0]->getPeriodById();

        $dql = "SELECT SUM(s.amount) AS balance FROM Application\Entity\Statement s " .
            "WHERE s.date >= ?1 AND s.date <= ?2 AND s.type NOT LIKE '%Withdrawal%'" .
            "GROUP BY s.fileId";

        $netSales = $this->_em->createQuery($dql)
            ->setParameter(1, $dates['start_date']->format('Y-m-d H:i:s'))
            ->setParameter(2, $dates['stop_date']->format('Y-m-d H:i:s'))
            ->getResult();

        return $netSales;
    }

    public function save() {
        $result = 0;
        foreach($this->payouts as $payout) {
            $result += $payout->save();
        }

        return $result;
    }

}