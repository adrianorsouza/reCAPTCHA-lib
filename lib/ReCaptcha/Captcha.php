<?php

/**
 * CodeIgniter Wrapper Library for reCAPTCHA API
 *
 * @date 2014-06-01 01:30
 *
 * @package Libraries
 * @author  Adriano Rosa (http://adrianorosa.com)
 * @license The MIT License (MIT), http://opensource.org/licenses/MIT
 * @link    https://github.com/adrianorsouza/codeigniter-recaptcha
 * @link    reCAPTCHA docs Reference: {@link https://developers.google.com/recaptcha/}
 * @version 0.1.0
 **/

namespace ReCaptcha;

use ReCaptcha\Exception;
use ReCaptcha\CaptchaTheme;

class Captcha extends CaptchaTheme
{
   /**
    * Constant API Server (without scheme)
    *
    * @var string
    **/
   const RECAPTCHA_API_SERVER =  "www.google.com/recaptcha/api";

   /**
    * RECAPTCHA Verify server name
    *
    * @var string
    **/
   const RECAPTCHA_VERIFY_SERVER =  "www.google.com";

   /**
    * Public API KEY
    *
    * @access protected
    * @var string
    **/
   protected $_publicKey;

   /**
    * Private API KEY
    *
    * @access protected
    * @var string
    **/
   protected $_privateKey;

   /**
    * Enable / Disable API call via SSL
    *
    * @access protected
    * @var bool
    **/
   protected $_ssl;

   /**
    * Error Message Response
    *
    * @access public
    * @var string
    **/
   public $_errorResponse;

   /**
    * Instance construct
    *
    * @param string $lang Changes the widget language
    * @return void
    **/
   public function __construct($lang = NULL)
   {
      if ( defined('ENVIRONMENT') && defined('APPPATH') ) {

         $CI =& get_instance();
         $CI->config->load('captcha_config');
         $this->_publicKey  = $CI->config->item('captcha_publicKey');
         $this->_privateKey = $CI->config->item('captcha_privateKey');
         $this->_ssl        = $CI->config->item('captcha_ssl');

         // Overwrite's default options if it's set in config file
         if ( is_array($CI->config->item('captcha_options')) ) {
            $this->_recaptchaOptions = $CI->config->item('captcha_options');
         }
         // Overwrite's Standard_Theme name if it's set in config file
         if ( !empty($CI->config->item('captcha_standard_theme')) ) {
            $this->_recaptchaOptions['theme'] = $CI->config->item('captcha_standard_theme');
         }
      }

      if ( NULL !== $lang ) {
         $this->setTranslation($lang);
      }
   }

   /**
    * Create embedded widget script HTML called within form
    *
    * @param string $theme_name Optional Standard_Theme or custom theme name
    * @param array $options Optional array of reCAPTCHA options
    * @return string The reCAPTCHA widget embed HTML
    **/
   public function displayHTML($theme_name = NULL, $options = array())
   {
      if ($this->_publicKey === NULL || $this->_publicKey === '') {
         exit('To use reCAPTCHA you must get an API key from https://www.google.com/recaptcha/admin/create');
      }

      $scheme = ( $this->_ssl ) ? 'https://' : 'http://';
      $errorpart = ($this->_errorResponse) ? $errorpart = "&amp;error=" . $this->_errorResponse : NULL;

      $captcha_html = $this->_theme($theme_name, $options);
      $captcha_html .= '<script type="text/javascript" src="'. $scheme . self::RECAPTCHA_API_SERVER . '/challenge?k=' . $this->_publicKey . $errorpart . '"></script>

      <noscript>
         <iframe src="' . $scheme . self::RECAPTCHA_API_SERVER . '/noscript?k=' . $this->_publicKey . $errorpart . '&hl=' . $this->clientLang() . '" height="300" width="500" frameborder="0"></iframe><br>
         <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
         <input type="hidden" name="recaptcha_response_field" value="manual_challenge">
      </noscript>';

      return $captcha_html;

   }
}
