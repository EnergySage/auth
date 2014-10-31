<?php
namespace FzyAuth;
return array(
	'service_manager' => array(
		'invokables' => array(
			'FzyAuth\Listener\Route' => 'FzyAuth\Listener\Route',
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
            'FzyAuth\Role\Guest' => function($sm) {
                return $sm->get('FzyAuth\Config')->get('role_guest', 'guest');
            },
			'FzyAuth\AclEnforcerFactory' => function($sm) {
                /* @var $detector \FzyAuth\Service\ApiRequestDetector */
                $detector = $sm->get('FzyAuth\Service\ApiRequestDetector');
                return function(\Zend\Mvc\MvcEvent $e) use ($detector, $sm) {
                    if ($detector->isApiRequest($e)) {
                        return $sm->get('FzyAuth\Service\AclEnforcer\Api');
                    }
                    return $sm->get('FzyAuth\Service\AclEnforcer\Web');
                };
			},
		),
	),
	\FzyAuth\Service\Base::MODULE_CONFIG_KEY => array(
		// whether to display exception traces
		'debug' => true,
		// whether to intercept api errors
		'intercept_api_errors' => true,
		// whether to enforce the ACL on route events
		'enforce_acl' => true,
		// the acl service to use (must be an instance of FzyAuth\Service\AclEnforcerInterface)
		'acl_service' => 'FzyAuth\Service\Acl',
        // the route name for api requests
        'api_route_name' => 'api',
        // guest role name
        'role_guest' => 'guest',
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
	)
);