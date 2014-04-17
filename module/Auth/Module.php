<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Auth;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
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

    public function getServiceConfig()
    {
        return array(
            'factories'=>array(
                'Auth\Model\AuthStorage' => function($sm){
                        return new \Auth\Model\AuthStorage('zf2_skeleton');
                    },

                'AuthService' => function($sm) {
                        $dbAdapter           = $sm->get('Zend\Db\Adapter\Adapter');
                        $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter,
                            'auth','user','password', 'SHA1(?)');

                        $authService = new AuthenticationService();
                        $authService->setAdapter($dbTableAuthAdapter);
                        $authService->setStorage($sm->get('Auth\Model\AuthStorage'));

                        return $authService;
                    },
            ),
        );
    }
}
