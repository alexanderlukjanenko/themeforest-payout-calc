<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Model;

use Application\Entity\Item;


class ItemModel
{

    /*
     *
     */
    protected $_repository;

    /*
     *
     */
    protected $_em;

    /*
     *
     */
    protected $_item;

    /*
     *
     */
    public function __construct($entityManager)
    {
        $this->_em = $entityManager;
        $this->_repository = $this->_em->getRepository('Application\Entity\Item');
    }

    /*
     *
     */
    public function add($itemtf) {
        $item = new Item();
        $item->setItem($itemtf);
        $this->_em->persist($item);
        $this->_em->flush();

        return $item->getName() . ' - item was added successfully  <br />';
    }

    /*
     *
     */
    public function getAll () {
        return $this->_repository->findAll();
    }

    /*
     *
     */
    public function getExistenItems () {
        $existentItems = $this->getAll();
        $exIds = array();
        foreach ($existentItems as $exItem) {
            $exIds[] = $exItem->getIdtf();
        }

        return $exIds;
    }

    public function importTfItems($items) {

        //get existen items
        $exIds = $this->getExistenItems();

        // Import items
        $message = '';
        foreach ($items['matches'] as $itemtf ) {

            if( in_array($itemtf['id'], $exIds)){
                $message .= 'Import FAILED: Item is already exist. <br />';
            } else {
                $message .= $this->add($itemtf);
            }

        }

        return $message;
    }

    /*
     *
     */
    public function findOneBy ($val) {
        return $this->_repository->findOneBy(array('idtf' => $val));
    }

    /*
     *
     */
    public function load($id) {
        $this->_item = $this->_repository->findOneBy(array('id' => $id));
    }

    public function getNetSales ($start, $stop) {

        $dql = "SELECT SUM(s.amount) AS balance FROM Application\Entity\Statement s " .
            "WHERE s.date >= ?1 AND s.date <= ?2 AND s.itemId = ?3 AND s.type <> 'Purchase'";

        $netSales = $this->_em->createQuery($dql)
            ->setParameter(1, $start)
            ->setParameter(2, $stop)
            ->setParameter(3, $this->_item->getIdtf())
            ->getSingleScalarResult();

        return round($netSales,2 );
    }


    //get Item sales (Extended support not included)
    public function getItemSales ($start, $stop) {
        $dql = "SELECT SUM(s.amount) AS balance FROM Application\Entity\Statement s " .
            "WHERE s.date >= ?1 AND s.date <= ?2 AND s.itemId = ?3 AND s.type <> 'Purchase' AND s.detail NOT LIKE '%extended support%' ";

        $amount = $this->_em->createQuery($dql)
            ->setParameter(1, $start)
            ->setParameter(2, $stop)
            ->setParameter(3, $this->_item->getIdtf())
            ->getSingleScalarResult();

        return round($amount,2 );
    }

    //get Extended support
    public function getExtendedSupportSales ($start, $stop) {
        $dql = "SELECT SUM(s.amount) AS balance FROM Application\Entity\Statement s " .
            "WHERE s.date >= ?1 AND s.date <= ?2 AND s.itemId = ?3 AND s.type <> 'Purchase' AND (s.detail LIKE '%extended support%' OR s.detail LIKE '%renewed support%')";

        $amount = $this->_em->createQuery($dql)
            ->setParameter(1, $start)
            ->setParameter(2, $stop)
            ->setParameter(3, $this->_item->getIdtf())
            ->getSingleScalarResult();

        return round($amount,2 );
    }

}




