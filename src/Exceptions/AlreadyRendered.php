<?php
/**
 * Created by PhpStorm.
 * User: marvin
 * Date: 23/10/17
 * Time: 15:03
 */

namespace Core\Exceptions;


class AlreadyRendered extends \Exception
{
	public function __construct()
	{
		parent::__construct("Already rendered");
	}
}