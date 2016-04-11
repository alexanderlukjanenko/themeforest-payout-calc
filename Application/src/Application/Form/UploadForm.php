<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class UploadForm extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
    }

    public function addElements()
    {
        $account = new Element\Select('account');
        $account->setLabel('Account')
            ->setAttribute('id','account')
            ->setAttribute('class', 'form-control')
            ->setValueOptions(array(
                ''
            ));


        $this->add($account);

        // File Input
        $file = new Element\File('csv-file');
        $file->setLabel('Statements file')
            ->setAttribute('id', 'csv-file')
            ->setAttribute('class', 'form-control');
        $this->add($file);


        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Add',
                'id' => 'submitbutton',
                'class' => 'btn btn-default',
            ),
        ));

    }

}