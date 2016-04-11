<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Statement {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="datetimetz") */
    protected $date;

    /** @ORM\Column(type="integer") */
    protected $orderId;

    /** @ORM\Column(type="string") */
    protected $type;

    /** @ORM\Column(type="string") */
    protected $detail;

    /** @ORM\Column(type="integer") */
    protected $itemId;

    /** @ORM\Column(type="string") */
    protected $document;

    /** @ORM\Column(type="float") */
    protected $price;

    /** @ORM\Column(type="float") */
    protected $euvat;

    /** @ORM\Column(type="float") */
    protected $usrwt;

    /** @ORM\Column(type="float") */
    protected $usbwt;

    /** @ORM\Column(type="float") */
    protected $amount;

    /** @ORM\Column(type="integer") */
    protected $accountId;

    /** @ORM\Column(type="integer") */
    protected $fileId;



    // getters/setters
    public function setDate ($val) {
        $this->date = $val;
    }

    public function setOrderId ($val) {
        $this->orderId = $val;
    }

    public function setType ($val) {
        $this->type = $val;
    }

    public function setDetail ($val) {
        $this->detail = $val;
    }

    public function setItemId ($val) {
        $this->itemId = $val;
    }

    public function setDocument ($val) {
        $this->document = $val;
    }

    public function setPrice ($val) {
        $this->price = $val;
    }

    public function setEuvat ($val) {
        $this->euvat = $val;
    }

    public function setUsrwt ($val) {
        $this->usrwt = $val;
    }

    public function setUsbwt ($val) {
        $this->usbwt = $val;
    }

    public function setAmount ($val) {
        $this->amount = $val;
    }

    public function setAccountId ($val) {
        $this->accountId = $val;
    }

    public function setFileId ($val) {
        $this->fileId = $val;
    }

    //
    public function getId() {
        return $this->id;
    }

    public function getDate () {
        return $this->date;
    }

    public function getOrderId () {
        return $this->orderId;
    }

    public function getType () {
        return $this->type;
    }

    public function getDetail () {
        return $this->detail;
    }

    public function getItemId () {
        return $this->itemId;
    }

    public function getDocument () {
        return $this->document;
    }

    public function getPrice () {
        return $this->price;
    }

    public function getEuvat () {
        return $this->euvat;
    }

    public function getUsrwt () {
        return $this->usrwt;
    }

    public function getUsbwt () {
        return $this->usbwt;
    }

    public function getAmount () {
        return $this->amount;
    }

    public function getAccountId () {
        return $this->accountId;
    }

    public function getFileId () {
        return $this->fileId;
    }

}