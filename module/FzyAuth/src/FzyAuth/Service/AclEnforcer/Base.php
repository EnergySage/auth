<?php
namespace FzyAuth\Service\AclEnforcer;

use FzyAuth\Service\Acl;
use FzyAuth\Service\AclEnforcerInterface;
use FzyCommon\Util\Params;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ModelInterface;

abstract class Base extends \FzyAuth\Service\Base implements AclEnforcerInterface {

    protected $acl;

    /**
     * Do any setup
     *
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function init( MvcEvent $e )
    {

    }

    /**
     * Add acl to the view model
     *
     * @param ModelInterface $viewModel
     *
     * @return mixed
     */
    public function attachToView( ModelInterface $viewModel )
    {
        $viewModel->setVariable('acl', $this->getAcl());
        return $this;
    }

    /**
     * @return \Zend\Permissions\Acl\Acl
     */
    public function getAcl()
    {
        if (!isset($this->acl)) {
	        $this->acl = $this->getServiceLocator()->get('FzyAuth\Acl');
        }
        return $this->acl;
    }


    /**
     * Abstraction to use current user role (guest if not logged in)
     *
     * @param $resource
     * @param null $privilege
     *
     * @return mixed
     */
    public function isAllowed( $resource, $privilege = null )
    {
        return $this->getAcl()->isAllowed($this->getCurrentUser()->getRole(), $resource, $privilege);
    }

    /**
     * @param MvcEvent $e
     * @param $routeName
     * @param $action
     *
     * @return mixed
     */
    public function isAllowedToRoute( MvcEvent $e, $routeName, $action )
    {
        return $this->isAllowed($routeName, $action);
    }

    /**
     * @param $routeName
     *
     * @return mixed
     */
    public function hasRoute( $routeName )
    {
        return $this->getAcl()->hasResource($routeName);
    }

	public function redirectTo(MvcEvent $e, $routeName, $routeParams = array(), $routeOptions = array())
	{
		$response = $e->getResponse();
		/* @var $router \Zend\Mvc\Router\Http\TreeRouteStack */
		$router = $this->getServiceLocator()->get('router');
		$url = $router->assemble($routeParams, array_merge($routeOptions, array('name' => $routeName)));
		$response->getHeaders()->addHeaderLine('Location', $url);
		return $this->triggerStatus($e, \Zend\Http\Response::STATUS_CODE_302);
	}

	public function triggerStatus(MvcEvent $e, $status = \Zend\Http\Response::STATUS_CODE_404)
	{
		$response = $e->getResponse();
		$response->setStatusCode($status);
		$response->sendHeaders();
		return $e->stopPropagation();
	}


}