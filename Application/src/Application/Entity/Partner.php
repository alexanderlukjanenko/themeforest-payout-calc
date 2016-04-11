<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Partner {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $share;

    /** @ORM\Column(type="string") */
    protected $supportshare;

    /** @ORM\ManyToOne(targetEntity="Item") */
    protected $item;

    /** @ORM\ManyToOne(targetEntity="Developer") */
    protected $developer;

    // getters/setters
    public function setShare($val){
        $this->share = $val;
    }

    public function setSupportShare($val){
        $this->supportshare = $val;
    }

    public function setItem($val) {
        $this->item = $val;
    }

    public function setDeveloper($val) {
        $this->developer = $val;
    }

    public function getId(){
        return $this->id;
    }

    public function getItem(){
        return $this->item;
    }

    public function getDeveloper(){
        return $this->developer;
    }

    public function getShare(){
        return $this->share;
    }

    public function getSupportShare(){
        return $this->supportshare;
    }
}