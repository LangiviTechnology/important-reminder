<?php

namespace Langivi\ImportantReminder\Entity;

class User
{
	private string $id;
	private string $login;
	private string $email;
	private string $password;

	public function __construct($id, $login, $email, $password) 
	{
		$this->id = $id;
		$this->login = $login;
		$this->email = $email;
		$this->password = $password;
	}

	public function getId (): ?string
	{
		return $this->id;
	}

	public function getLogin (): ?string
	{
		return $this->login;
	}

	public function setLogin (string $login): self
	{
		$this->login = $login;
		return $this;
	}
	public function getPassword (): ?string
	{
		return $this->password;
	}
	public function setPassword (string $password): self
	{
		$this->password = $password;
		return $this;
	}
	public function getEmail (): ?string
	{
		return $this->email;
	}

	public function setEmail (string $email): self
	{
		$this->email = $email;
		return $this;
	}
	public function getData (): object
	{
		return (object)[
			'id' => $this->id,
			'email' => $this->email,
			'login' => $this->login,
		];
	}

}

