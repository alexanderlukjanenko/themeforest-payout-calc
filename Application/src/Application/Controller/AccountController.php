<?php

/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AccountController extends AbstractActionController
{

    protected $_accountModel;

    /*
     *
     */
    public function indexAction()
    {
        return new ViewModel(array('accounts' => $this->getAccountModel()->getAll() ));
    }

    /*
     *
     */
    public function addAction()
    {
        if ($this->request->isPost()) {
            $newId = $this->getAccountModel()->add(
                array(
                    'accountname' => $this->getRequest()->getPost('accountname'),
                    'apikey' => $this->getRequest()->getPost('apikey'),
                )
            );
            return $this->redirect()->toRoute('account');
        }
        return new ViewModel();
    }

    /*
     *
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($this->request->isPost()) {
            $this->getAccountModel()->edit(
                array(
                    'id' => $id,
                    'accountname' => $this->getRequest()->getPost('accountname'),
                    'apikey' => $this->getRequest()->getPost('apikey'),
                )
            );

            return $this->redirect()->toRoute('account');
        }
        return new ViewModel(array('account' => $this->getAccountModel()->get($id)));
    }

    /*
     *
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($this->request->isPost()) {
            $this->getAccountModel()->delete($id);
            return $this->redirect()->toRoute('account');
        }

        return new ViewModel(array('account' => $this->getAccountModel()->get($id)));
    }


    /*
     *
     */
    protected function getAccountModel()
    {
        if (!$this->_accountModel) {
            $this->_accountModel = $this->getServiceLocator()->get('Application\Model\AccountModel');
        }
        return $this->_accountModel;
    }

}