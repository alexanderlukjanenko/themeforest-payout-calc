<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity */
class StatementFile {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="date") */
    protected $upload_date;

    /** @ORM\Column(type="string") */
    protected $file_path;

    /** @ORM\Column(type="string") */
    protected $file_name;

    /** @ORM\Column(type="string") */
    protected $label;

    /** @ORM\Column(type="date") */
    protected $start_date;

    /** @ORM\Column(type="date") */
    protected $stop_date;

    /** @ORM\Column(type="integer") */
    protected $accountId;


    // getters/setters

    public function setUploadDate($val) {
        $this->upload_date = $val;
    }

    public function setFilePath($val) {
        $this->file_path = $val;
    }

    public function setFileName($val) {
        $this->file_name = $val;
    }

    public function setFileLabel($val) {
        $this->label = $val;
    }

    public function setStartDate($val) {
        $this->start_date = $val;
    }

    public function setStopDate($val) {
        $this->stop_date = $val;
    }

    public function setAccountId($val) {
        $this->accountId = $val;
    }
    //
    public function getId() {
        return $this->id;
    }

    public function getUploadDate() {
        return $this->upload_date;
    }

    public function getFilePath() {
        return $this->file_path;
    }

    public function getFileName() {
        return $this->file_name;
    }

    public function getFileLabel() {
        return $this->label;
    }

    public function getStartDate() {
        return $this->start_date;
    }

    public function getStartDateStr() {
        $str = $this->start_date->format('d-m-Y');
        return $str;
    }

    public function getStopDate() {
        return $this->stop_date;
    }

    public function getStopDateStr() {
        $str = $this->stop_date->format('d-m-Y');
        return $str;

    }

    public function getAccountId() {
        return $this->accountId;
    }

}