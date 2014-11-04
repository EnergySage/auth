<?php
namespace FzyAuth\Service\AclEnforcer;

use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\MvcEvent;
use ZfcUser\Controller\UserController;

class Web extends Base {

    /**
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function handleRouteMissing( MvcEvent $e )
    {
	    $routeName = 'home';
	    if ($e->getRouteMatch()->getMatchedRouteName() == $routeName) {
		    // prevent infinite loop
		    exit();
	    }
		return $this->redirectTo($e, $routeName);
    }

    /**
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function handleAllowed( MvcEvent $e )
    {
	    // do nothing
    }

    /**
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function handleNotAllowed( MvcEvent $e )
    {
	    // is this user authenticated?
	    if (!$this->getCurrentUser()->isNull()) {
		    // not allowed to this route by the ACL
		    return $this->triggerStatus($e, Response::STATUS_CODE_403);
	    }
	    // redirect to login
	    if ($e->getRouteMatch()->getMatchedRouteName() == UserController::ROUTE_LOGIN) {
		    // prevent infinite loop
		    return $this->triggerStatus($e, Response::STATUS_CODE_403);
	    }
	    return $this->redirectTo($e, UserController::ROUTE_LOGIN);
    }
}