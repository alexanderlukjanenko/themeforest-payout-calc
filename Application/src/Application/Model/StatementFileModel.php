<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Model;

use Application\Entity\Statement;
use Application\Entity\StatementFile;


class StatementFileModel
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
    protected $_file;

    /*
     *
     */
    public function __construct($entityManager)
    {
        $this->_em = $entityManager;
        $this->_repository = $this->_em->getRepository('Application\Entity\StatementFile');
    }

    /*
     *
     */
    public function add($attributes) {

        $time = new \DateTime("now");
        $tmp_file = $attributes['csv-file']['tmp_name'];
        $file_path = 'uploads/' . $time->getTimestamp() . '___' . $attributes['csv-file']['name'];
        //account
        $account = $this->_em->find('\Application\Entity\Account', $attributes['account']);

        $this->_file = new StatementFile();
        $this->_file->setUploadDate($time);
        $this->_file->setFilePath($file_path);
        $this->_file->setFileName($attributes['csv-file']['name']);
        $this->_file->setAccountId($attributes['account']);
        $this->_file->setFileLabel($this->getLabel($account->getAccountName(), $attributes['csv-file']['name']));
        $this->_file->setStartDate($this->getStartDate($attributes['csv-file']['name']));
        $this->_file->setStopDate($this->getStopDate($attributes['csv-file']['name']));


        if ($this->isExist()) {
            move_uploaded_file($tmp_file, $file_path);
            $this->_em->persist($this->_file);
            $this->_em->flush();
        } else {
            return false;
        }

        return $this->_file->getId();

    }

    public function import($id) {
        $this->load($id);

        $rows = new \RdnCsv\Controller\Plugin\CsvImport;
        $rows = $rows->__invoke($this->_file->getFilePath());

        foreach ($rows as $row) {

            if (count($row) > 0) {
                $statement = new Statement();

                $date = \DateTime::createFromFormat('Y-m-d H:i:s O', $row['Date']);

                $statement->setDate($date);
                $statement->setOrderId ($row['Order ID']);
                $statement->setType ($row['Type']);
                $statement->setDetail ($row['Detail']);
                $statement->setItemId ($row['Item ID']);
                $statement->setDocument ($row['Document']);
                $statement->setPrice ($row['Price']);
                $statement->setEuvat($row['EU VAT']);
                $statement->setUsrwt($row['US RWT']);
                $statement->setUsbwt($row['US BWT']);
                $statement->setAmount ($row['Amount']);
                $statement->setAccountId($this->_file->getAccountId());
                $statement->setFileId($this->_file->getId());

                $this->_em->persist($statement);
                $this->_em->flush();
            }
        }
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
    public function getAllByAccount() {
        return $this->_repository->findBy( array('accountId' =>  $this->_file->getAccountId() ));
    }

    /*
     *
     */
    public function getAllSorted() {
        return $this->_repository->findBy(array(), array('start_date' => 'ASC'));
    }



    /*
     *
     */
    public function load($id) {
        $this->_file = $this->_em->find('\Application\Entity\StatementFile', $id);
    }

    public function get() {
        return $this->_file;
    }
    /*
     *
     */
    public function delete() {

        $statements = $this->_em->getRepository('Application\Entity\Statement')->findBy(array('fileId' => $this->_file->getId() ));

        foreach ($statements as $statement) {
            $this->_em->remove($statement);
            $this->_em->flush();
        }

        try {
            unlink($this->_file->getFilePath());
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        $this->_em->remove($this->_file);
        $this->_em->flush();
    }

    public function getLabel($account, $file) {
        $arr = explode("_", $file);
        $arr2 = explode(".",$arr[5]);
        $date = new \DateTime($arr2[0]);
        return $account . ' ' . $date->format('M y');
    }

    public function getStartDate($val) {
        $arr = explode("_", $val);
        $date = new \DateTime($arr[3]);
        return $date;
    }

    public function getStopDate($val) {
        $arr = explode("_", $val);
        $arr2 = explode(".",$arr[5]);
        $date = new \DateTime($arr2[0]);
        return $date;
    }

    public function isExist() {
        $new_start = $this->_file->getStartDate();
        $new_stop = $this->_file->getStopDate();

        $files = $this->getAllByAccount();

        foreach($files as $file) {
            $start = $file->getStartDate();
            $stop = $file->getStopDate();

            if ( ($new_start < $start && $new_stop < $start) ||  ($new_start >  $stop &&  $new_stop > $stop) ) {
            } else {
                return false;
            }
        }
        return true;
    }

    public function getPeriods() {
        $files = $this->getAll();
        $periods = array();
        $period_points = array();
        foreach ($files as $file) {
            if ( in_array($file->getStartDate()->format('Y-m-d'), $period_points) || in_array($file->getStopDate()->format('Y-m-d'), $period_points) ) {

            } else {
                $period_points[] = $file->getStartDate()->format('Y-m-d');
                $period_points[] = $file->getStopDate()->format('Y-m-d');
                $period = array($file->getId(), $file->getStartDate()->format('Y-m-d'), $file->getStopDate()->format('Y-m-d'));
                $periods[] = $period;
            }

        }

        return $periods;
    }

    public function getPeriod() {

        if (isset($this->start_date)) {

        } else {
            $this->start_date = $this->_file->getStartDate();
        }

        if(isset($this->stop_date)) {

        } else {
            $d = $this->_file->getStopDate()->format('Y-m-d');
            $add = '23:59:59';
            $new = new \DateTime($d . ' ' . $add);
            $this->stop_date = $new;
        }

        return array('start_date' => $this->start_date, 'stop_date' => $this->stop_date);
    }
}




