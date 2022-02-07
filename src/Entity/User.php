<?php

namespace Langivi\ImportantReminder\Entity;

define('EMAIL_PATTERN', '/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/');
define('PASSWORD_PATTERN', '/^\w{4,20}$/');
define('LOGIN_PATTERN', '/^\w{2,25}$/');

class User
{
	private string $id;
	private string $login;
	private string $email;
	private string $password;

	public function __construct($login, $email, $password) 
	{
		$this->id = '$id';
		$this->login = $login;
		$this->email = $email;
		$this->password = $password;
	}

	public function getId (): ?string
	{
		return $this->id;
	}
	public function setId ($id): self
	{
		$this->id = $id;
		return  $this;
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

	public function validate (): bool
	{
		$isEmail = (bool) preg_match(EMAIL_PATTERN, $this->email);
		$isPassword = (bool) preg_match(PASSWORD_PATTERN, $this->password);
		$isLogin = (bool) preg_match(LOGIN_PATTERN, $this->login);
		// var_dump('MAIL',$isEmail);
		// var_dump('PAS',$isPassword);
		// var_dump('LOG',$isLogin);
		return $isEmail && $isPassword && $isLogin;
	}
}

