<?php
namespace FzyAuth\Listener;

use FzyAuth\Service\AclEnforcerInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\MvcEvent;

class Route extends Base
{

    public function latch(MvcEvent $e)
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
        $controller = $e->getRouteMatch()->getParam('controller');
        $action = $e->getRouteMatch()->getParam('action');

        if ($aclEnforcer->hasControllerResource($controller)) {
            return $aclEnforcer->isAllowed(AclEnforcerInterface::RESOURCE_CONTROLLER_PREFIX . $controller, $action) ? $aclEnforcer->handleAllowed($e) : $aclEnforcer->handleNotAllowed($e);
        } elseif ($aclEnforcer->hasRouteResource($route)) {
            return $aclEnforcer->isAllowed(AclEnforcerInterface::RESOURCE_ROUTE_PREFIX . $route, $action) ? $aclEnforcer->handleAllowed($e) : $aclEnforcer->handleNotAllowed($e);
        }

        return $aclEnforcer->handleRouteMissing($e);
    }
}
