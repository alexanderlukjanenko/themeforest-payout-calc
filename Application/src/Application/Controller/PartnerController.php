<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class PartnerController extends AbstractActionController
{
    /*
     *
     */

    protected $_partnerModel;



    /*
     *
     */

    protected $_itemModel;



    /*
     *
     */

    protected $_developerModel;

    /*
     *
     */

    public function indexAction()    {
        $partnerModel = $this->getServiceLocator()->get('Application\Model\PartnerModel');

        return new ViewModel(array('partners' => $partnerModel->getAll() ));
    }




    /*
     *
     */
    public function addAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        $items = $this->getItemModel()->getAll();
        $developer = $this->getDeveloperModel()->get($id);

        if ($this->request->isPost()) {
            $partnerModel = $this->getServiceLocator()->get('Application\Model\PartnerModel');
            $partnerModel->add($this->getRequest()->getPost());
            return $this->redirect()->toRoute('developer');
        }

        return new ViewModel(array('developer' => $developer, 'items' => $items ));
    }




    /*
     *
     */

    public function editAction()
    {
        //developer
        $id = (int) $this->params()->fromRoute('id', 0);
        $partnerModel = $this->getServiceLocator()->get('Application\Model\PartnerModel');
        $partner =  $partnerModel->get($id);


        if ($this->request->isPost()) {
            $partnerModel->edit($this->getRequest()->getPost() );
            return $this->redirect()->toRoute('partner');
        }
        return new ViewModel(array('partner' => $partner));
    }

    /*
     *
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $partnerModel = $this->getServiceLocator()->get('Application\Model\PartnerModel');
        if ($this->request->isPost()) {
            $partnerModel->get($id);
            $partnerModel->delete();
            return $this->redirect()->toRoute('partner');
        }
        return new ViewModel(array('partner' => $partnerModel->get($id)));
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
    protected function getItemModel()
    {
        if (!$this->_itemModel) {
            $this->_itemModel = $this->getServiceLocator()->get('Application\Model\ItemModel');
        }
        return $this->_itemModel;
    }

}