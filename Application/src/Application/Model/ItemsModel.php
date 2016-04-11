<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Model;


class ItemsModel
{
    private $start_date;
    private $stop_date;
    private $total;
    private $itemStats;

    private $_em;

    /*
     *
     */
    public function __construct($entityManager) {
        $this->_em = $entityManager;
        $this->items = array();
    }



    /*
     *
     */
    public function setStartDate($val) {
        $this->start_date = $val;
    }



    /*
     *
     */
    public function setStopDate($val) {
        $this->stop_date = $val;
    }


    public function getNetSalesAllItems() {
        $items = $this->_em->getRepository('Application\Entity\Item')->findAll();

        foreach ($items as $item) {
            $itemModel = new ItemModel($this->_em);
            $itemStat = array();
            $itemStat['tfid'] = $item->getIdtf();
            $itemStat['name'] = $item->getName();
            $itemModel->load($item->getId());
            $itemStat['sales'] = $itemModel->getNetSales($this->start_date, $this->stop_date);
            $this->itemStats[] = $itemStat;
        }

        usort($this->itemStats, function ($a, $b)
        {
            if ($a['sales'] == $b['sales']) {
                return 0;
            }
            return ($a['sales'] > $b['sales']) ? -1 : 1;
        });


        return $this->itemStats;
    }
}