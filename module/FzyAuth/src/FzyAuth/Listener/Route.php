<?php
namespace FzyAuth\Listener;

use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\MvcEvent;

class Route extends Base
{

	public function latch( MvcEvent $e )
	{
		if ($this->getModuleConfig()->get('enforce_acl', true)) {
			$this->latchTo(MvcEvent::EVENT_ROUTE, array($this, 'checkAcl'));
		}
	}

	public function checkAcl(MvcEvent $e)
	{
		if (!$e->getRequest() instanceof \Zend\Http\Request) {
			return;
		}
		$route = $e->getRouteMatch()->getMatchedRouteName();

		/* @var $aclEnforcer \FzyAuth\Service\AclEnforcerInterface */
		$aclEnforcer = $this->getServiceLocator()->get('FzyAuth\AclEnforcerFactory');

		$aclEnforcer->init($e);
		$aclEnforcer->attachToView($e->getViewModel());

		// check if acl has resource "route"
		if (!$aclEnforcer->hasRoute($route)) {
			return $aclEnforcer->handleRouteMissing($e);
		}
		if ($aclEnforcer->isAllowedToRoute($e, $route, $e->getRouteMatch()->getParam('action'))) {
            return $aclEnforcer->handleAllowed($e);
		}
		return $aclEnforcer->handleNotAllowed($e);
	}
}