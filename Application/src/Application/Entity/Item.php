<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class Item {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="integer", unique=true) */
    protected $idtf;

    /** @ORM\Column(type="string") */
    protected $name;

    /** @ORM\Column(type="string") */
    protected $price_cents;

    /** @ORM\Column(type="string") */
    protected $icon_url;

    /** @ORM\Column(type="string") */
    protected $url;

    /** @ORM\Column(type="string") */
    protected $author_username;

    /** @ORM\Column(type="string") */
    protected $author_image;

    /** @ORM\Column(type="string") */
    protected $classification;

    // getters/setters

    public function setItem($val) {
        $keys = array('name', 'price_cents', 'author_username', 'author_image', 'classification');

        foreach ($keys as $key) {
            $this->$key = $val[$key];
        }

        $this->url = $val['previews']['live_site']['url'];

        $this->icon_url = $val['previews']['icon_with_landscape_preview']['icon_url'];

        $this->idtf = $val['id'];
    }

    public function getName () {
        return $this->name;
    }

    public function getId() {
        return $this->id;
    }

    public function getIdtf() {
        return $this->idtf;
    }

    public function getPrice() {
        return $this->price_cents/100;
    }

    public function getIcon() {
        return $this->icon_url;
    }

    public function getUrl(){
        return $this->url;
    }

    public function getAuthorUsername(){
        return $this->author_username;
    }

    public function getAuthorIcon(){
        return $this->author_image;
    }

    public function getClassification(){
        return $this->classification;
    }

}