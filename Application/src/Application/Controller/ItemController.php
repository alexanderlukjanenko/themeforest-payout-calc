<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Themeforest;

class ItemController extends AbstractActionController
{

    /*
     *
     */
    public function indexAction()
    {
        $itemModel = $this->getServiceLocator()->get('Application\Model\ItemModel');
        return new ViewModel(array('items' => $itemModel->getAll() ));
    }

    /*
     *
     */
    public function tfItemsAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $account = $this->getServiceLocator()->get('Application\Model\AccountModel')->get($id);

        $tf = new Themeforest($account->getAccountName(), $account->getApiKey());
        $items = $tf->getItems();
        return new ViewModel(array('items' => $items, 'account' => $account->getAccountName() ));
    }

    /*
     *
     */
    public function importAction() {
        // account info
        $id = (int) $this->params()->fromRoute('id', 0);
        $account = $this->getServiceLocator()->get('Application\Model\AccountModel')->get($id);

        //get items from TF api
        $tf = new Themeforest($account->getAccountName(), $account->getApiKey());
        $items = $tf->getItems();

        $itemModel = $this->getServiceLocator()->get('Application\Model\ItemModel');
        $message = $itemModel->importTfItems($items);

        return new ViewModel(array('message' => $message));

    }



    /*
     *
     */
    public function selectPerfAction (){
        $fileModel = $this->getServiceLocator()->get('Application\Model\StatementFileModel');
        $periods = $fileModel->getPeriods();

        if ($this->request->isPost()) {
            return $this->redirect()->toRoute(
                'item', array(
                'controller' => 'item',
                'action' =>  'showperf',
                'id' => $this->getRequest()->getPost('file'),
            ));
        }

        return new ViewModel(array('periods' => $periods));
    }




    /*
     *
     */
    public function showPerfAction(){
        $periodid = (int) $this->params()->fromRoute('id', 0);

        $itemsModel = $this->getServiceLocator()->get('Application\Model\ItemsModel');
        $fileModel = $this->getServiceLocator()->get('Application\Model\StatementFileModel');
        $fileModel->load($periodid);
        $period = $fileModel->getPeriod();

        $itemsModel->setStartDate($period['start_date']);
        $itemsModel->setStopDate($period['stop_date']);
        $items = $itemsModel->getNetSalesAllItems();

        return new ViewModel(array('items' => $items, 'period' => $period));
    }



    /*
     *
     */
    public function selectPerfReportAction (){
        if ($this->request->isPost()) {
            return $this->redirect()->toRoute(
                'item', array(
                'controller' => 'item',
                'action' =>  'showperfreport',
                'id' => '0',
                'start' => $this->getRequest()->getPost('start'),
                'stop' => $this->getRequest()->getPost('stop')
            ));
        }
        return new ViewModel();
    }


    /*
     *
     */
    public function showPerfReportAction(){

        $start = $this->params()->fromRoute('start', 0);
        $stop = $this->params()->fromRoute('stop', 0);
        $period = array('start_date' => new \DateTime($start), 'stop_date' => new \DateTime($stop));

        $itemsModel = $this->getServiceLocator()->get('Application\Model\ItemsModel');

        $itemsModel->setStartDate($period['start_date']);
        $itemsModel->setStopDate($period['stop_date']);
        $items = $itemsModel->getNetSalesAllItems();

        return new ViewModel(array('items' => $items, 'period' => $period));
    }

}