<?php
namespace FzyAuth\Listener;

use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

class DispatchError extends Base
{

	/**
	 * @param MvcEvent $e
	 */
	public function latch( MvcEvent $e )
	{
		if ($this->getModuleConfig()->get('intercept_api_errors', true)) {
			$debug = $this->getModuleConfig()->get( 'debug', false );
            /* @var $apiRequestDetector \FzyAuth\Service\ApiRequestDetector */
            $apiRequestDetector = $this->getServiceLocator()->get('FzyAuth\Service\ApiRequestDetector');
			$this->latchTo( MvcEvent::EVENT_DISPATCH_ERROR, function (MvcEvent $e) use ($apiRequestDetector, $debug) {
				if ($apiRequestDetector->isApiRequest($e)) {
					/* @var $exception \Exception */
					$exception = $e->getParam( 'exception', new \Exception( 'Unknown Error', 500 ) );
					$viewData  = array(
						'exception' => array(
							'message' => $exception->getMessage(),
							'code'    => $exception->getCode(),
						),
					);
					if ( $debug ) {
						$viewData['exception']['trace'] = $exception->getTrace();
					}
					$view = new JsonModel( $viewData );
					$e->setViewModel( $view );
				}
			} );
		}
		return $this;
	}

}