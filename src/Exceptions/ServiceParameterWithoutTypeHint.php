<?php
/**
 * Created by PhpStorm.
 * User: marvin
 * Date: 23/10/17
 * Time: 15:03
 */

namespace Core\Exceptions;


class ServiceParameterWithoutTypeHint extends \Exception
{
	public function __construct($serviceName)
	{
		parent::__construct("Service {$serviceName} is not defined");
	}
}