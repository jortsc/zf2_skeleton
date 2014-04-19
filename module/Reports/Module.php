<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Reports;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    private $sm;
    private $controller;

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Getting service locator and called controller to redirect if it is necessary
    */
 /*   public function onBootstrap(MvcEvent $e)
    {
        $this->sm = $e->getApplication()->getServiceManager();

        $e->getApplication()->getEventManager()
                            ->getSharedManager()
                            ->attach('Zend\Mvc\Controller\AbstractActionController',
                                     'dispatch',
                                 function($e) {
                                     $this->controller = $e->getTarget();
                                 },
                                 100
                            );
    }*/

    /**
     * Checking for auth

    public function preDispatch(MvcEvent $e){
        $this->sm = $e->getApplication()->getServiceManager();

        if (!$this->sm->get('AuthService')->hasIdentity()){
            $this->controller->plugin('redirect')->toRoute('auth');
        }else{
            die('lol');
        }
        die('lol');
    }
*/

}
