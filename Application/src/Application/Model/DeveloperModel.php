<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Model;

use Application\Entity\Developer;

class DeveloperModel
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
    protected $_developer;

    /*
     *
     */
    public function __construct($entityManager)
    {
        $this->_em = $entityManager;
        $this->_repository = $this->_em->getRepository('Application\Entity\Developer');
    }

    /*
     *
     */
    public function getAll() {
        return $this->_repository->findAll();
    }

    /*
     *
     */
    public function add($attributes) {

        $developer = new Developer();
        $developer->setName($attributes['name']);
        $developer->setSkrill($attributes['skrill']);
        $developer->setSalary($attributes['salary']);
        $developer->setPrepaid($attributes['prepaid']);

        //add selected Items
        foreach ($attributes['items'] as $it) {
            $item = $this->_em
                ->getRepository('Application\Entity\Item')
                ->findOneBy(array('id' => $it));
            $developer->getItems()->add($item);
        }

        $this->_em->persist($developer);
        $this->_em->flush();

        return $developer->getId();
    }

    /*
     *
     */
    public function edit($attributes) {
        $developer = $this->get($attributes['id']);
        $developer->setName($attributes['name']);
        $developer->setSkrill($attributes['skrill']);
        $developer->setSalary($attributes['salary']);
        $developer->setPrepaid($attributes['prepaid']);
        $developer->clearItems();

        //add selected Items
        foreach ($attributes['items'] as $it) {
            $item = $this->_em
                ->getRepository('Application\Entity\Item')
                ->findOneBy(array('id' => $it));
            $developer->getItems()->add($item);
        }

        $this->_em->persist($developer);
        $this->_em->flush();

        return $developer->getId();
    }

    /*
     *
     */
    public function get($id){
        if (!$this->_developer) {
            $this->_developer = $this->_em->find('\Application\Entity\Developer', $id);
        }
        return $this->_developer;

    }

    /*
     *
     */
    public function delete() {
        $this->_em->remove($this->_developer);
        $this->_em->flush();
    }

    /*
     *
     */
    public function getItemIds() {
        $itemIds = array();
        foreach ($this->_developer->getItems() as $item) {
            $itemIds[] = $item->getId();
        }

        return $itemIds;
    }

}




