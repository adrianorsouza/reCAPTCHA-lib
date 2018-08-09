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
 * https://www.google.com/recaptcha/admin/create.
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
use ReCaptcha\CaptchaTheme;
use ReCaptcha\CaptchaException;

/**
 * PHP Library for reCAPTCHA Google's API.
 * Captcha class that handles calling Google's reCAPTCHA API widget.
 *
 * @package ReCaptcha
 * @author  Adriano Rosa http://adrianorosa.com
 * @copyright 2018 Adriano Rosa http://adrianorosa.com
 * @throws \ReCaptcha\CaptchaException Whether API Keys is not set.
 * @version 0.1.3 2016
 */
class Captcha extends CaptchaTheme
{
	/**
	 * reCAPTCHA Library Version
	 *
	 * @var string
	 */
	public $Version = '0.1.3';

	/**
	 * Server response timeout
	 *
	 * @var integer
	 */
	public $timeout = 10;

	const RECAPTCHA_API_SERVER =  'www.google.com/recaptcha/api/';

	const RECAPTCHA_VERIFY_SERVER = 'http://www.google.com/recaptcha/api/verify';

	const RECAPTCHA_HEADER = "POST %s HTTP/1.0\r\nHost: %s \r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: %s\r\nUser-Agent: reCAPTCHA/PHP\r\n\r\n%s";

	/**
	 * Public API KEY
	 *
	 * @var string
	 */
	protected $_publicKey;

	/**
	 * Private API KEY
	 *
	 * @var string
	 */
	protected $_privateKey;

	/**
	 * Enable / Disable API call via SSL
	 *
	 * @var bool
	 */
	protected $_ssl;

	/**
	 * Remote IP
	 *
	 * @var string
	 *
	 * @deprecated will be remove in major release v1.0.0
	 */
	protected $_remoteIP = '127.0.0.1';

	/**
	 * Error message response
	 *
	 * @var string
	 */
	protected $_error;

	/**
	 * Instance constructor
	 *
	 * @param string $lang Changes the widget language
	 * @param bool $https Enable using reCAPTCHA in SSL sites
	 * @return void
	 */
	public function __construct($lang = NULL, $https = FALSE)
	{
		if ( NULL !== $lang ) {
			$this->setTranslation($lang);
		}

		$this->_ssl = (TRUE === $https);
	}

	/**
	 * Set global reCAPTCHA options by loading an external config file
	 * these options can be set for all instances of reCAPTCHA in your
	 * app, this avoid to set options, private and public Keys all the
	 * time individualy within your forms that has a Captcha widget.
	 *
	 * @param string $config_location The absolute path to config file
	 * @throws \ReCaptcha\CaptchaException
	 * @return void
	 */
	public function setConfig($config_location = NULL)
	{

		static $CAPTCHA_CONFIG = array();
		$path = ( NULL === $config_location )
			? __DIR__ . DIRECTORY_SEPARATOR . 'captcha_config.php'
			: realpath($config_location);

		if ( false === $path ) {
			throw new CaptchaException(
				sprintf("Config File not found in: %s ", $config_location)
			);
		}

		if ( $path ) {

			include_once $path;

			foreach (get_class_vars(get_class($this)) as $key => $value) {

				$config = preg_replace('/^[\_A-Z]/', '\\1', $key);

				if ( array_key_exists($config, $CAPTCHA_CONFIG) ) {
					$this->$key = $CAPTCHA_CONFIG[$config];
				}
			}
		}
	}

	/**
	 * Set Public API KEY
	 *
	 * @param string $key
	 * @return void
	 */
	public function setPublicKey($key)
	{
		$this->_publicKey = $key;
	}

	/**
	 * Set Private API KEY
	 *
	 * @param string $key
	 * @return void
	 */
	public function setPrivateKey($key)
	{
		$this->_privateKey = $key;
	}

	/**
	 * Set a remote client IP
	 * Optional setter for an alternative IP address whether REMOTE_ADDR is empty,
	 * anyway default value for FALLBACK is 127.0.0.1
	 * Use this setter to use a different one.
	 *
	 * @param string $ip_address The valid IP address
	 * @return void
	 *
	 * @deprecated will be remove in major release v1.0.0
	 */
	public function setRemoteIp($ip_address)
	{
		$this->_remoteIP = $ip_address;
	}

	/**
	 * Set reCAPTCHA server Response error.
	 * NOTE: Default string error is: incorrect-captcha-sol
	 * Use this function to overwrite with your own message.
	 *
	 * @param string $e The error message string. Optional Whether this parameter is NULL, it will retrieve
	 *		   an error message translated by a given lang e.g: 'it' (for Italian) returns 'Scorretto. Riprova.'
	 * @return void
	 */
	public function setError($e = NULL)
	{
	  	// whether lang is set the I18n string is picked
		if ( NULL === $e ) {
			$e = $this->i18n('incorrect_try_again');
		}
		$this->_error = $e;
	}

	/**
	 * Get reCAPTCHA server Response error
	 *
	 * @param string
	 * @param strin
	 * @return void
	 */
	public function getError()
	{
		return $this->_error;
	}

	/**
	 * Create embedded widget script HTML called within a form
	 *
	 * NOTE: $theme_name is used to set theme name separated instead set
	 * it within array of options available, this is to keep compatibility
	 * for newer version in future.
	 *
	 * @param string $theme_name Optional Standard_Theme or custom theme name
	 * @param array $options Optional array of reCAPTCHA options
	 * @throws \ReCaptcha\CaptchaException
	 * @return string The reCAPTCHA widget embed HTML
	 */
	public function displayHTML($theme_name = NULL, $options = array())
	{
		if ( strlen($this->_publicKey == 0) ) {
			throw new CaptchaException('To use reCAPTCHA you must get a Public API key from https://www.google.com/recaptcha/admin');
		}

	  	// append a Theme
		$captcha_snippet = $this->_theme($theme_name, $options);
		$captcha_snippet .= '<script type="text/javascript" src="'. $this->_buildServerURI() . '"></script>

		<noscript>
		<iframe src="' . $this->_buildServerURI('noscript') . '" height="300" width="500" frameborder="0"></iframe><br>
		<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
		<input type="hidden" name="recaptcha_response_field" value="manual_challenge">
		</noscript>';

		return $captcha_snippet;
	}

	/**
	 * Verifying reCAPTCHA User's Answer
	 * resolves response challenge return TRUE if the answer matches
	 *
	 * @return bool
	 */
	public function isValid()
	{
	  	// Skip without submission
		if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {

			return FALSE;

		} else {

			$captchaChallenge = isset($_POST['recaptcha_challenge_field'])
				? $this->_sanitizeField($_POST['recaptcha_challenge_field'])
				: NULL;

			$captchaResponse  = isset($_POST['recaptcha_response_field'])
				? $this->_sanitizeField($_POST['recaptcha_response_field'])
				: NULL;
		 	// Skip empty submission
			if ( strlen($captchaChallenge) == 0 || strlen($captchaResponse) == 0 ) {
				$this->setError('incorrect-captcha-sol');
				return FALSE;
			}

			$data = array(
				'privatekey' => $this->_privateKey,
				'remoteip'   => $this->_remoteIp(),
				'challenge'  => $captchaChallenge,
				'response'   => $captchaResponse
				);

			$result = $this->_postHttpChallenge($data);

			if ( $result['isvalid'] === "true") {
				return TRUE;

			} else {
				$this->setError($result['error']);
			}
			return FALSE;
		}
	}

	/**
	 * reCAPTCHA API Request
	 *
	 * Post reCAPTCHA input challenge, response
	 * Uses function fsockopen() and curl() as a fallback
	 * If both functions are unavailable in server configuration
	 * an {@link \ReCaptcha\CaptchaException} exception will be thrown
	 *
	 * @param array $data Array of reCAPTCHA parameters
	 * @throws \ReCaptcha\CaptchaException
	 * @return array
	 */
	protected function _postHttpChallenge(array $data)
	{
		if ( strlen($this->_privateKey) == 0 ) {
			throw new CaptchaException(
				'To use reCAPTCHA you must get a Private API key from https://www.google.com/recaptcha/admin'
			);
		}

		$responseHeader = '';
		$result = array('isvalid'=>'false', 'error'=>'');

		$remote_url = parse_url(self::RECAPTCHA_VERIFY_SERVER);
		$httpQuery  = http_build_query($data);

		$requestHeader  = sprintf(
			self::RECAPTCHA_HEADER,
			$remote_url['path'],
			$remote_url['host'],
			strlen($httpQuery),
			$httpQuery);

		if ( function_exists('fsockopen') ) {

			$handler = @fsockopen($remote_url['host'], 80, $errno, $errstr, $this->timeout);

			if( false == ( $handler ) ) {
				throw new CaptchaException(
					sprintf('Could not open sock to check reCAPTCHA at %s.', self::RECAPTCHA_VERIFY_SERVER)
				);
			}

			stream_set_timeout($handler, $this->timeout);

			fwrite($handler, $requestHeader);

			$remote_response = stream_get_line($handler, 32, "\n");

			if (strpos($remote_response, '200 OK') !== false) {

				while (!feof($handler)) {
					$responseHeader .= stream_get_line($handler, 356);
				}
				fclose($handler);

				$responseHeader = str_replace("\r\n", "\n", $responseHeader);
				$responseHeader = explode("\n\n", $responseHeader);
				array_shift($responseHeader);

				$responseHeader = explode("\n", implode("\n\n", $responseHeader));
			}

	  	// Fallback to CURL if fsockopen is not enabled
		} elseif ( extension_loaded('curl') ) {

			$ch = curl_init(self::RECAPTCHA_VERIFY_SERVER);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
			curl_setopt($ch, CURLOPT_USERAGENT, 'reCAPTCHA/PHP');
			curl_setopt($ch, CURLOPT_HTTPHEADER, explode("\r\n", $requestHeader) );
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $httpQuery);

			$responseHeader = curl_exec($ch);

			curl_close($ch);

			if ($responseHeader !== false) {

				$responseHeader = explode("\n\n", $responseHeader);
				$responseHeader = explode("\n", implode("\n\n", $responseHeader));
			}

		} else {
			throw new CaptchaException(
				sprintf('Can\'t connect to the server %s. Try again.', self::RECAPTCHA_VERIFY_SERVER )
			);
		}

		$result = ( count($responseHeader) == 2 )
			? array_combine(array_keys($result), $responseHeader)
			: $result;

		return $result;
	}

	/**
	 * Get the client remote IP
	 * in order to send post to the API must have an IP
	 * set for sometimes IP is empty, so we set a FALLBACK
	 * for that.
	 *
	 * @return string
	 *
	 * @deprecated will be remove in major release v1.0.0
	 */
	private function _remoteIp()
	{
		if ( !$_SERVER['REMOTE_ADDR'] ) {
			return $this->_remoteIP;
		}
		return $_SERVER['REMOTE_ADDR'];
	}

	/**
	 * Sanitizes recaptcha_input_field
	 *
	 * @param string $recaptcha_input_field The input string to be sanitized
	 * @return string
	 *
	 * @deprecated will be remove in major release v1.0.0
	 */
	private function _sanitizeField($recaptcha_input_field)
	{
		return preg_replace('/[^a-zA-Z0-9._\-+\s]/i', '', $recaptcha_input_field);
	}

	/**
	 * Build API server URI
	 *
	 * @param string $path The path whether is noscript for iframe or not
	 * @return string
	 *
	 * @deprecated will be remove in major release v1.0.0
	 */
	private function _buildServerURI($path = 'challenge')
	{
	  	// Scheme
		$uri  = ( TRUE === $this->_ssl ) ? 'https://' : 'http://';
	  	// Host
		$uri .= self::RECAPTCHA_API_SERVER;
	  	// Path
		$uri .= ($path !== 'challenge') ? 'noscript' : $path;
	  	// Query
		$uri .= '?k=' . $this->_publicKey;
		$uri .= ($this->_error) ? '&error=' . $this->_error : NULL;
		$uri .= ( isset($this->_recaptchaOptions['lang']) ) ? '&hl=' . $this->_recaptchaOptions['lang'] : '&hl=' .$this->clientLang();

		return $uri;
	}
}
