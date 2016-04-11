<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Controller;

use Application\Entity\StatementFile;
use Application\Model\StatementFileModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form;

class StatementController extends AbstractActionController
{

    /*
     *
     */
    public function indexAction()
    {
        return new ViewModel(array('statementfiles' => $this->getServiceLocator()->get('Application\Model\StatementFileModel')->getAllSorted() ));
    }

    /*
     *
     */
    public function importAction()
    {

    }

    /*
     *
     */
    public function uploadFormAction()
    {
        $accounts = $this->getServiceLocator()->get('Application\Model\AccountModel');
        $accounts = $accounts->getAll();

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the files info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $fileModel = $this->getServiceLocator()->get('Application\Model\StatementFileModel');
            $fileId = $fileModel->add($post);
            if ($fileId === false ) {
                $message = "Error! File contains statements that were already imported to DB.";
                return array('accounts' => $accounts, 'message' => $message );
            } else {
                $fileModel->import($fileId);
            }

            return $this->redirect()->toRoute('statement');
        }

        return array('accounts' => $accounts);
    }

    /*
     *
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $file = $this->getServiceLocator()->get('Application\Model\StatementFileModel');
        $file->load($id);

        if ($this->request->isPost()) {
            $file->delete();
            return $this->redirect()->toRoute('statement');
        }

        return new ViewModel(array('statementfile' => $file));
    }

}