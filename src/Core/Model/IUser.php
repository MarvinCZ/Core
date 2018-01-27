<?php

namespace Core\Model;

interface IUser
{
	public function getName(): string;

	public function getRights(): int;

	public function getId(): int;

	public function hasRole(int $role): bool;
}