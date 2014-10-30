<?php
namespace FzyAuth\Listener;

use Zend\Mvc\MvcEvent;

interface ListenerInterface  {
	public function latch(MvcEvent $e);
}