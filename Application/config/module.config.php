<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'account' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/account[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Account',
                        'action'     => 'index',
                    ),
                ),
            ),
            'item' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/item[/][:action][/:id][/:start][/:stop]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'start' => '[a-zA-Z0-9_-]*',
                        'stop' => '[a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Item',
                        'action'     => 'index',
                    ),
                ),
            ),
            'developer' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/developer[/][:action][/:id][/:start][/:stop]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                        'start' => '[a-zA-Z0-9_-]*',
                        'stop' => '[a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Developer',
                        'action'     => 'index',
                    ),
                ),
            ),
            'partner' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/partner[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Partner',
                        'action'     => 'index',
                    ),
                ),
            ),
            'statement' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/statement[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Statement',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'Application\Model\ItemModel' => 'Application\ModelFactory\ItemModelFactory',
            'Application\Model\AccountModel' => 'Application\ModelFactory\AccountModelFactory',
            'Application\Model\DeveloperModel' => 'Application\ModelFactory\DeveloperModelFactory',
            'Application\Model\PartnerModel' => 'Application\ModelFactory\PartnerModelFactory',
            'Application\Model\StatementFileModel' => 'Application\ModelFactory\StatementFileModelFactory',
            'Application\Model\PayoutModel' => 'Application\ModelFactory\PayoutModelFactory',
            'Application\Model\PayoutsModel' => 'Application\ModelFactory\PayoutsModelFactory',
            'Application\Model\ItemsModel' => 'Application\ModelFactory\ItemsModelFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Account' => 'Application\Controller\AccountController',
            'Application\Controller\Item' => 'Application\Controller\ItemController',
            'Application\Controller\Developer' => 'Application\Controller\DeveloperController',
            'Application\Controller\Partner' => 'Application\Controller\PartnerController',
            'Application\Controller\Statement' => 'Application\Controller\StatementController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),

    //ORM mappings
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Application/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' => 'application_entities'
                )
            )
        )
    ),
);