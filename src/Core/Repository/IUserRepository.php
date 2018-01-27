<?php

namespace Core\Repository;

use Core\Model\IUser;

interface IUserRepository
{
	public function getUser($id): IUser;
}