<?php

namespace ReCaptcha;

/**
 * PHP Library for reCAPTCHA Google's API
 * This is a PHP wrapper library that handles calling Google's reCAPTCHA API widget.
 *
 * NOTE: before start using this library you must generate your reCAPTCHA API Key
 *          {@link: https://www.google.com/recaptcha/admin/create}
 * This library was written based on plugin version from
 * AUTHORS: Mike Crawford, Ben Maurer -- http://recaptcha.net
 *
 * @date 2014-06-01 01:30
 *
 * @author  Adriano Rosa (http://adrianorosa.com)
 * @package Libraries
 * @subpackage ReCaptcha
 * @license The MIT License (MIT), http://opensource.org/licenses/MIT
 * @link    https://github.com/adrianorsouza/reCAPTCHA-lib
 * @link    API reCAPTCHA docs Reference: {@link https://developers.google.com/recaptcha/}
 * @version 0.1.0
 */
class CaptchaException extends \Exception
{
	/**
	 * Prettify error message output
	 * @return string
	 */
	public function errorMessage()
	{
		$errorMsg = '<span style="color:#e90f15">' . $this->getMessage() . "</span><br>\n";
		return $errorMsg;
	}
}
