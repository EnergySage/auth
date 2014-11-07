<?php
namespace FzyAuth;
return array(
	'service_manager' => array(
		'invokables' => array(
			'FzyAuth\Listener\Route' => 'FzyAuth\Listener\Route',
			'FzyAuth\Listener\Register' => 'FzyAuth\Listener\Register',
			'FzyAuth\Listener\DispatchError' => 'FzyAuth\Listener\DispatchError',
			'FzyAuth\Service\Acl' => 'FzyAuth\Service\Acl',
            'FzyAuth\Service\ApiRequestDetector' => 'FzyAuth\Service\ApiRequestDetector',
            'FzyAuth\Factory\Acl' => 'FzyAuth\Factory\Acl',
            'FzyAuth\Service\AclEnforcer\Web' => 'FzyAuth\Service\AclEnforcer\Web',
            'FzyAuth\Service\AclEnforcer\Api' => 'FzyAuth\Service\AclEnforcer\Api',
		),
		'factories' => array(
            'FzyAuth\Config' => function($sm) {
                /* @var $config \FzyCommon\Util\Params */
                $config = $sm->get('FzyCommon\Config');
                return $config->getWrapped(\FzyAuth\Service\Base::MODULE_CONFIG_KEY);
            },
			'FzyAuth\AclEnforcerFactory' => function($sm) {
                /* @var $detector \FzyAuth\Service\ApiRequestDetector */
                $detector = $sm->get('FzyAuth\Service\ApiRequestDetector');
				/* @var $application \Zend\Mvc\Application */
				return $sm->get($detector->isApiRequest($sm->get('Application')->getMvcEvent()) ? 'FzyAuth\Service\AclEnforcer\Api' : 'FzyAuth\Service\AclEnforcer\Web');
			},
			/**
			 * Factory method for instantiating the configuration specified factory, running it
			 * on the ACL configuration and returning an ACL object.
			 * @return \Zend\Permissions\Acl\Acl
			 */
			'FzyAuth\Acl' => function($sm) {
				/* @var $moduleConfig \FzyCommon\Util\Params */
				$moduleConfig = $sm->get('FzyAuth\Config');
				$aclConfig = $moduleConfig->getWrapped('acl');
				/* @var $aclFactory \FzyAuth\Factory\Acl */
				$aclFactory = $sm->get($moduleConfig->get('acl_factory', 'FzyAuth\Factory\Acl'));
				return $aclFactory->createAcl($aclConfig, $sm);
			},

			/**
			 * Factory to return a UserInterface object indicating the currently logged in user.
			 *
			 * A UserNull object is returned in the event the user is not logged in.
			 *
			 * @return \FzyAuth\Entity\Base\UserInterface
			 */
			'FzyAuth\CurrentUser' => function($sm) {
				$zfcAuth = $sm->get('zfcuser_auth_service');
				if ($zfcAuth->hasIdentity()) {
					return $zfcAuth->getIdentity();
				}
				$nullUserClass = $sm->get('FzyAuth\Config')->get('null_user_class', '\FzyAuth\Entity\Base\UserNull');
				return new $nullUserClass();
			}
		),
	),
	\FzyAuth\Service\Base::MODULE_CONFIG_KEY => array(
		// whether to display exception traces
		'debug' => true,
		// whether to intercept api errors
		'intercept_api_errors' => true,
		// whether to enforce the ACL on route events
		'enforce_acl' => true,
        // the route name for api requests
        'api_route_name' => 'api',
        // service to generate and configure ACL
        //'acl_factory' => 'FzyAuth\Factory\Acl',
	),
	'doctrine' => array(
		'driver' => array(
			__NAMESPACE__ . '_driver' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
			),
			'orm_default' => array(
				'drivers' => array(
					__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
				)
			)
		)
	),
	/**
	 * Override zfcuser settings
	 */
	'zfcuser' => array(
		// telling ZfcUser to use our own class
		'user_entity_class'       => 'FzyAuth\Entity\Base\User',
		// telling ZfcUserDoctrineORM to skip the entities it defines
		'enable_default_entities' => false,
		'enable_username' => true,
	),
);