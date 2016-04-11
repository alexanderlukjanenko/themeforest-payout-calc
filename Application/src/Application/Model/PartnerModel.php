<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Model;

use Application\Entity\Partner;


class PartnerModel
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
    protected $_partner;

    public function __construct($entityManager)
    {
        $this->_em = $entityManager;
        $this->_repository = $this->_em->getRepository('Application\Entity\Partner');
    }

    /*
     *
     */
    public function getAll() {
        return $this->_repository->findBy(array(),array('developer' => 'ASC'));
    }

    /*
     *
     */
    public function add($attributes) {
        
        $partner = new Partner();
        $partner->setShare($attributes['percent']);
        $partner->setSupportShare($attributes['support']);
        $this->_em->persist($partner);

        //create Item
        $item = $this->_em->getRepository('Application\Entity\Item')->findOneBy(array('id' => $attributes['item']));
        $partner->setItem($item);

        //create Developer
        $developer = $this->_em->getRepository('Application\Entity\Developer')->findOneBy(array('id' => $attributes['developer']));
        $partner->setDeveloper($developer);


        $this->_em->flush();

        return $partner->getId();
    }

    /*
     *
     */
    public function edit($attributes) {
        $partner = $this->get($attributes['id']);
        $partner->setShare($attributes['percent']);
        $partner->setSupportShare($attributes['support']);

        $this->_em->persist($partner);
        $this->_em->flush();

        return $partner->getId();
    }

    /*
     *
     */
    public function get($id){
        if (!$this->_partner) {
            $this->_partner = $this->_em->find('\Application\Entity\Partner', $id);
        }
        return $this->_partner;

    }

    /*
     *
     */
    public function delete() {
        $this->_em->remove($this->_partner);
        $this->_em->flush();
    }



    /*
     *
     */
    public function getPartnerRelationsCount ($developer) {
       return count($this->_repository->findBy(array('developer' => $developer )));
    }
}




