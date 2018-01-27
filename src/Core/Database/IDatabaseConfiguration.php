<?php

namespace Core\Database;

interface IDatabaseConfiguration
{
	public function getHost(): string;
	public function getDatabaseName(): string;
	public function getUsername(): string;
	public function getPassword(): string;
}