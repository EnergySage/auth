<?php
namespace FzyAuth\Listener;

use FzyAuth\Service\AclEnforcer\Base as BaseAclEnforcer;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class DispatchError extends Base
{

	/**
	 * @param MvcEvent $e
	 */
	public function latch( MvcEvent $e )
	{
		$intercept = $this->getModuleConfig()->get('intercept_api_errors', true);
		$debug = $this->getModuleConfig()->get( 'debug', false );
        /* @var $apiRequestDetector \FzyAuth\Service\ApiRequestDetector */
        $apiRequestDetector = $this->getServiceLocator()->get('FzyAuth\Service\ApiRequestDetector');
		if ($apiRequestDetector->isApiRequest($e)) {
			if ($this->getModuleConfig()->get('intercept_api_errors', true)) {
				$this->latchTo(MvcEvent::EVENT_DISPATCH_ERROR, function(MvcEvent $e) use ($debug) {
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
				});
			}
		} else {
			$this->latchTo( MvcEvent::EVENT_DISPATCH_ERROR, function (MvcEvent $e) {
				$error = $e->getError();
				if (empty($error) || $error != BaseAclEnforcer::ACL_ACCESS_DENIED) {
					return;
				}
				// set up basic 403 error page
				$result = $e->getResult();
				$baseModel = new ViewModel();
				$baseModel->setTemplate('layout/layout');
				$model = new ViewModel();
				$model->setTemplate('error/403');
				$baseModel->addChild($model);
				$baseModel->setTerminal(true);
				$e->setViewModel($baseModel);
				$response = $e->getResponse();
				$response->setStatusCode(Response::STATUS_CODE_403);
				$e->setResult($baseModel);
				return $response;
			}, -999);
		}
		return $this;
	}

}