<?php

/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Account {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $accountName;

    /** @ORM\Column(type="string") */
    protected $apiKey;

    // getters/setters

    public function setAccountName($val) {
        $this->accountName = $val;
    }

    public function setApiKey($val) {
        $this->apiKey = $val;
    }

    public function  getId() {
        return $this->id;
    }

    public function getAccountName(){
        return $this->accountName;
    }

    public function getApiKey() {
        return $this->apiKey;
    }

}