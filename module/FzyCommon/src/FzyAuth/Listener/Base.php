<?php
namespace FzyAuth\Listener;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\MvcEvent;
use FzyAuth\Service\Base as BaseService;

abstract class Base extends BaseService implements ListenerInterface, EventManagerAwareInterface {

	protected $responseCode = Response::STATUS_CODE_302;

	protected $eventManager;

	protected function sendToRouteNamed(MvcEvent $e, $routeName, $routeParams = array(), $routeOptions = array())
	{
		$url = $this->urlFromRoute($routeName, $routeParams, $routeOptions);
		return $this->sendToUrl($e, $url);
	}

	protected function urlFromRoute($routeName, $params = array(), $options = array()) {
		return $this->getServiceLocator()->get('router')->assemble($params, array('name' => $routeName));
	}

	protected function sendToUrl(MvcEvent $e, $url) {
		$response = $e->getResponse();
		$response->getHeaders()->addHeaderLine('Location', $url);
		$response->setStatusCode($this->responseCode);
		$response->sendHeaders();
		return $e->stopPropagation();
	}

	/**
	 * @return int
	 */
	public function getResponseCode() {
		return $this->responseCode;
	}

	/**
	 * @param int $responseCode
	 *
	 * @return Base
	 */
	public function setResponseCode( $responseCode ) {
		$this->responseCode = $responseCode;

		return $this;
	}

	/**
	 * @return EventManagerInterface
	 */
	public function getEventManager() {
		return $this->eventManager;
	}

	/**
	 * @param mixed $eventManager
	 *
	 * @return Base
	 */
	public function setEventManager(EventManagerInterface $eventManager ) {
		$this->eventManager = $eventManager;

		return $this;
	}



	protected function latchTo($mvcEvent, $callback)
	{
		$this->getEventManager()->getSharedManager()->attach('Zend\Mvc\Application', $mvcEvent, $callback);
	}



}