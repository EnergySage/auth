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

	protected function checkAcl(MvcEvent $e)
	{
		if (!$e->getRequest() instanceof \Zend\Http\Request) {
			return;
		}
		$route = $e->getRouteMatch()->getMatchedRouteName();

        /* @var $enforcerFactory \Callable */
		$enforcerFactory = $this->getServiceLocator()->get('FzyAuth\AclEnforcerFactory');
        /* @var $aclEnforcer \FzyAuth\Service\AclEnforcerInterface */
        $aclEnforcer = $enforcerFactory($e);

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