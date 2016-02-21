<?php
/**
 * PHP Library for reCAPTCHA Google's API v1
 * This is a PHP wrapper library that handles calling Google's reCAPTCHA API widget
 *
 * This library abstracts all possible configurations, customization to setup and
 * display a Google's reCAPTCHA API widget in your site or app.
 *
 * PHP version 5.3+
 *
 * NOTE:
 * In order to use Google's reCAPTCHA widget you must generate your API Key
 * https://www.google.com/recaptcha/admin.
 *
 * @date 2014-06-01
 *
 * @author  Adriano Rosa (http://adrianorosa.com)
 * @package ReCaptcha
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link    https://github.com/adrianorsouza/reCAPTCHA-lib
 * @link    https://developers.google.com/recaptcha/ reCAPTCHA API docs Reference
 * @version 0.1.3 2016
 */

namespace ReCaptcha;

/**
 * This class represents an error returned by ReCaptcha Library
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
