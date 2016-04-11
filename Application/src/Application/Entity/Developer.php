<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/** @ORM\Entity */
class Developer {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $name;

    /** @ORM\Column(type="string") */
    protected $skrill;

    /** @ORM\Column(type="string") */
    protected $salary;

    /** @ORM\Column(type="string") */
    protected $prepaid;

    /** @ORM\ManyToMany(targetEntity="Item") */
    protected $items;

    public function __construct() {
        $this->items = new ArrayCollection();
    }

    // getters/setters

    public function getId () {
        return $this->id;
    }

    public function getName () {
        return $this->name;
    }

    public function getSkrill () {
        return $this->skrill;
    }

    public function getSalary () {
        return $this->salary;
    }

    public function getPrepaid () {
        return $this->prepaid;
    }

    public function getItems () {
        return $this->items;
    }

    public function setName ($val) {
        $this->name = $val;
    }

    public function setSkrill ($val) {
        $this->skrill = $val;
    }

    public function setSalary ($val) {
        $this->salary = $val;
    }

    public function setPrepaid ($val) {
        $this->prepaid = $val;
    }

    public function clearItems(){
        $this->items = new ArrayCollection();
    }

    public function getItemsCount() {
        return $this->items->count();
    }
}