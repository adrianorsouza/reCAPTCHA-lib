<?php

namespace ReCaptcha;

/**
 * PHP reCAPTCHA Google's API Wrapper Library for CodeIgniter
 * This is a PHP library that handles calling reCAPTCHA widget.
 *
 * NOTE: before start using this library you must generate reCAPTCHA API Key
 *          https://www.google.com/recaptcha/admin/create
 * This library was written based on plugin version from
 * AUTHORS: Mike Crawford, Ben Maurer -- http://recaptcha.net
 *
 * @date 2014-06-01 01:30
 *
 * @author  Adriano Rosa (http://adrianorosa.com)
 * @package Libraries
 * @subpackage ReCaptcha
 * @license The MIT License (MIT), http://opensource.org/licenses/MIT
 * @link    https://github.com/adrianorsouza/codeigniter-recaptcha
 * @link    reCAPTCHA docs Reference: {@link https://developers.google.com/recaptcha/}
 * @version 0.1.0
 */
class CaptchaException extends \Exception
{
   /**
    * Format error message output
    * @return string
    */
   public function errorMessage()
   {
      $errorMsg = '<span style="color:#e90f15">' . $this->getMessage() . "</span><br>\n";
      return $errorMsg;
   }
}
