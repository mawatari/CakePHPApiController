<?php
App::uses('CakeRoute', 'Routing/Route');
class ApiRoute extends CakeRoute {
	public function __construct($template, $defaults = [], $options = []) {
		$defaults['controller'] = 'api_' . $defaults['controller'];
		parent::__construct($template, $defaults, $options);
	}
}
