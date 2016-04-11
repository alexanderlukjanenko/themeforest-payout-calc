<?php
/**
 * Copyright © 2015 Alexander Lukjanenko (alexander.lukjanenko@gmail.com)
 */

namespace Application\ModelFactory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\PayoutsModel;

class PayoutsModelFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        return new PayoutsModel($entityManager);
    }
}