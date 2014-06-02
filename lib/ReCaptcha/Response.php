<?php
namespace ReCaptcha;

use \ReCaptcha\Captcha as Captcha;
/**
*
*/
class Response extends Captcha
{

   function __construct()
   {
      parent::__construct('');
      _vd($this);
   }
}
