<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Model;

use Application\Entity\Account;


class AccountModel
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
    public function __construct($entityManager)
    {
        $this->_em = $entityManager;
        $this->_repository = $this->_em->getRepository('Application\Entity\Account');
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
    public function getAllArray() {
        $arr = array();
        $items = $this->_repository->findAll();
        foreach($items as $item){
            $arr[$item->getId()] = $item->getName();
        }

        return $arr;
    }

    /*
     *
     */
    public function add($attributes) {
        $account = new Account();
        $account->setAccountName($attributes['accountname']);
        $account->setApiKey($attributes['apikey']);
        $this->_em->persist($account);
        $this->_em->flush();

        return $account->getId();
    }

    /*
     *
     */
    public function edit($attributes) {
        $account = $this->get($attributes['id']);
        $account->setAccountName($attributes['accountname']);
        $account->setApiKey($attributes['apikey']);
        $this->_em->persist($account);
        $this->_em->flush();

        return $account->getId();
    }

    /*
     *
     */
    public function get($id){
        return $this->_em->find('\Application\Entity\Account', $id);
    }

    /*
     *
     */
    public function delete($id) {
        $account = $this->_em->find('\Application\Entity\Account', $id);
        $this->_em->remove($account);
        $this->_em->flush();
    }
}




