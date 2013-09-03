<?php
namespace Themes\ThemeManager\Exception;

class MissingDefaultThemeException extends \Exception
{
	protected $message = 'Missing default theme';
	protected $code    = 500;
}