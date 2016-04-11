<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Payout {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="float") */
    protected $amount;

    /** @ORM\ManyToOne(targetEntity="Developer") */
    protected $developer;

    /** @ORM\Column(type="datetimetz") */
    protected $start_date;

    /** @ORM\Column(type="datetimetz") */
    protected $stop_date;

    // getters/setters
    public function setAmount($val){
        $this->amount = $val;
    }

    public function setDeveloper($val){
        $this->developer = $val;
    }

    public function setStartDate($val){
        $this->start_date = $val;
    }

    public function setStopDate($val){
        $this->stop_date = $val;
    }


    public function getId(){
        return $this->id;
    }

    public function getAmount(){
        return $this->amount;
    }

    public function getDeveloper(){
        return $this->developer;
    }

    public function getStartDate(){
        return $this->start_date;
    }

    public function getStopDate(){
        return $this->stop_date;
    }

}