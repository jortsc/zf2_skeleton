<?php
/**
 * Created by PhpStorm.
 * User: jorts
 * Date: 4/19/14
 * Time: 7:57 PM
 */

namespace Auth\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\Session\Container as SessionContainer,
    Zend\Permissions\Acl\Acl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Permissions\Acl\Resource\GenericResource as Resource;

use Zend\Debug\Debug;


class Checker extends AbstractPlugin
{
    protected $sessionContainer;

    private function getSessContainer()
    {
        if (!$this->sessionContainer) {
            $this->sessionContainer = new SessionContainer('zf2_skeleton');
        }
        return $this->sessionContainer;
    }

    public function checkAuth($e)
    {
        $controller = $e->getTarget();
        $controllerClass = (string)get_class($controller);
//      $namespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        $serviceLocator = $this->getController()->getServiceLocator();
//      Debug::dump($controllerClass);
//      Debug::dump($namespace);

        if('Auth\Controller\AuthController' !== $controllerClass){
            if (!$serviceLocator->get('AuthService')->hasIdentity()){
                $router = $e->getRouter();
                $url    = $router->assemble(array(), array('name' => 'logout'));
                $response = $e->getResponse();
                $response->setStatusCode(302);
                $response->getHeaders()->addHeaderLine('Location', $url);
                $e->stopPropagation();
            }
        }

      /*  //setting ACL...
        $acl = new Acl();
        //add role ..
        $acl->addRole(new Role('anonymous'));
        $acl->addRole(new Role('user'),  'anonymous');
        $acl->addRole(new Role('admin'), 'user');

        $acl->addResource(new Resource('Application'));
        $acl->addResource(new Resource('Login'));

        $acl->deny('anonymous', 'Application', 'view');
        $acl->allow('anonymous', 'Login', 'view');

        $acl->allow('user',
            array('Application'),
            array('view')
        );

        //admin is child of user, can publish, edit, and view too !
        $acl->allow('admin',
            array('Application'),
            array('publish', 'edit')
        );

        $controller = $e->getTarget();
        $controllerClass = get_class($controller);
        $namespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));

        $role = (! $this->getSessContainer()->role ) ? 'anonymous' : $this->getSessContainer()->role;
        if (!$acl->isAllowed($role, $namespace, 'view')){
            $router = $e->getRouter();
            $url    = $router->assemble(array(), array('name' => 'Login/auth'));

            $response = $e->getResponse();
            $response->setStatusCode(302);
            //redirect to login route...
            /* change with header('location: '.$url); if code below not working */
//            $response->getHeaders()->addHeaderLine('Location', $url);
//            $e->stopPropagation();
//        }

    }
} 