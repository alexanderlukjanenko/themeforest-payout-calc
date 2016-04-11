<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Payout;


class DeveloperController extends AbstractActionController
{

    protected $_developerModel;
    protected $_objectManager;

    /*
     *
     */

    public function indexAction()
    {
        $partnerItems = array();
        $developers = $this->getDeveloperModel()->getAll();
        $partnerModel = $this->getServiceLocator()->get('Application\Model\PartnerModel');

        foreach ($developers as $developer) {
            $partnerItems[$developer->getId()] = $partnerModel->getPartnerRelationsCount( $developer );
        }

        return new ViewModel(array('developers' => $developers, 'partneritems' => $partnerItems ));
    }




    /*
     *
     */
    public function addAction()
    {
        if ($this->request->isPost()) {
            $this->getDeveloperModel()->add( $this->getRequest()->getPost() );
            return $this->redirect()->toRoute('developer');
        }

        $items = $this->getServiceLocator()->get('Application\Model\ItemModel')->getAll();
        return new ViewModel(array('items' => $items ));
    }




    /*
     *
     */

    public function editAction()
    {
        //developer
        $id = (int) $this->params()->fromRoute('id', 0);
        $developerModel = $this->getDeveloperModel();
        $developer =  $developerModel->get($id);

        //developer Items
        $developerModel->get($id);
        $developerItems = $developerModel->getItemIds();

        //items
        $allItems = $this->getServiceLocator()->get('Application\Model\ItemModel')->getAll();

        if ($this->request->isPost()) {
            $this->getDeveloperModel()->edit($this->getRequest()->getPost() );
            return $this->redirect()->toRoute('developer');
        }
        return new ViewModel(array('developer' => $developer, 'items' => $allItems, 'developeritems' => $developerItems));
    }

    /*
     *
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $developer = $this->getDeveloperModel();
        if ($this->request->isPost()) {
            $developer->get($id);
            $developer->delete();
            return $this->redirect()->toRoute('developer');
        }
        return new ViewModel(array('developer' => $developer->get($id)));
    }

    /*
     *
     */
    public function selectPayoutAction (){
        $fileModel = $this->getServiceLocator()->get('Application\Model\StatementFileModel');
        $periods = $fileModel->getPeriods();

        if ($this->request->isPost()) {
            return $this->redirect()->toRoute(
            'developer', array(
                'controller' => 'developer',
                'action' =>  'showpayout',
                'id' => $this->getRequest()->getPost('file'),
            ));
        }

        return new ViewModel(array('periods' => $periods));
    }

    /*
     *
     */

    public function showPayoutAction(){
        $periodid = (int) $this->params()->fromRoute('id', 0);

        $payouts = $this->getServiceLocator()->get('Application\Model\PayoutsModel');
        $payouts->setPeriod($periodid);
        $payouts->load();

        return new ViewModel(array('payouts' => $payouts, 'periodid' => $periodid));
    }

    /*
     *
     */
    public function savePayoutAction(){
        $periodid = (int) $this->params()->fromRoute('id', 0);

        $payouts = $this->getServiceLocator()->get('Application\Model\PayoutsModel');
        $payouts->setPeriod($periodid);
        $payouts->load();
        $count = $payouts->save();

        $message = $count . " payouts have been saved.";

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
                'developer', array(
                'controller' => 'developer',
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

        $payouts = $this->getServiceLocator()->get('Application\Model\PayoutsModel');
        $payouts->setPeriod($periodid);
        $payouts->load();

        return new ViewModel(array('payouts' => $payouts));
    }



    /*
     *
     */
    protected function getDeveloperModel()
    {
        if (!$this->_developerModel) {
            $this->_developerModel = $this->getServiceLocator()->get('Application\Model\DeveloperModel');
        }
        return $this->_developerModel;
    }

    /*
     *
     */
    public function selectPerfReportAction (){
        if ($this->request->isPost()) {
            return $this->redirect()->toRoute(
                'developer', array(
                'controller' => 'developer',
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

        $payouts = $this->getServiceLocator()->get('Application\Model\PayoutsModel');
        $payouts->setPeriod($period);
        $payouts->load();

        return new ViewModel(array('payouts' => $payouts));
    }

}