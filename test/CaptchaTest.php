<?php
/**
 * reCAPTCHA Library Test
 *
 * @package Test
 * @author  Adriano Rosa http://adrianorosa.com
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 * @copyright 2015 Adriano Rosa http://adrianorosa.com
 * @version 0.1.2
 */

namespace test;

use \lib\Recaptcha\Captcha;
use \lib\Recaptcha\CaptchaTheme;
use \lib\Recaptcha\CaptchaException;

class CaptchaTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @expectedException \Recaptcha\CaptchaException
	 */
	public function testCaptchaEmptyPublicKey()
	{
		$captcha = new \ReCaptcha\Captcha();
		$captcha->setPublicKey('');
		$captcha->displayHTML();
	}

	/**
	 * @expectedException \Recaptcha\CaptchaException
	 */
	public function testCaptchaEmptyPrivateKey()
	{
		$_SERVER['REQUEST_METHOD']          = 'POST';
		$_SERVER['REMOTE_ADDR']             = '127.0.0.1';
		$_POST['recaptcha_challenge_field'] = '123';
		$_POST['recaptcha_response_field']  = 'abc';

		$captcha = new \ReCaptcha\Captcha();
		$captcha->setPrivateKey('');
		$captcha->isValid();
	}

	/**
	 * test testInvalidCaptcha
	 * @return void
	 */
	public function testInvalidCaptcha() {

		$captcha = new \ReCaptcha\Captcha();
		$captcha->setPrivateKey('abc123');
        $this->assertFalse($captcha->isValid());
    }

	/**
	 * @expectedException \Recaptcha\CaptchaException
	 */
	public function testCaptchaInvalidConfigFile()
	{
		$captcha = new \ReCaptcha\Captcha();
		$captcha->setConfig('path/invalid/captcha_config.php');
		$captcha->displayHTML();
	}

	/**
	 * test testGetLangKey
	 * @return void
	 */
	public function testGetLangKey()
	{
		$result = $this->invokeMethod(new \ReCaptcha\Captcha('br'), 'i18n', array('instructions_visual'));
		$expected = 'Digite as palavras acima:';

		$this->assertEquals($expected, $result);
	}

	/**
	 * test testThemeDefault
	 * @return void
	 */
	public function testThemeDefault()
	{
		$result = $this->invokeMethod(new \ReCaptcha\Captcha(), '_theme', array( 'red', array() ));
		$expected = '';
		$this->assertEquals($expected, $result);
	}

	/**
	 * test testThemeOptions
	 * @return void
	 */
	public function testThemeOptions()
	{
		$result = $this->invokeMethod(new \ReCaptcha\Captcha(), '_theme', array( 'clean', array('lang'=>'fr') ));
		$expected = '<script type="text/javascript">var RecaptchaOptions = {"theme":"clean","lang":"fr"};</script>';
		$this->assertEquals($expected, $result);
	}

	/**
	 * test testHttpPostResult
	 * @return void
	 */
	public function testHttpPostResult()
	{
		$data = array(
				'privatekey' => 'privatekey',
				'remoteip'   => '127.0.0.1',
				'challenge'  => 'challenge',
				'response'   => 'response'
				);

		$captcha = new \ReCaptcha\Captcha();

		$this->invokeMethod($captcha, 'setPrivateKey', array('123ABC'));
		$result = ( $this->invokeMethod($captcha, '_postHttpChallenge', array($data)) );

		$this->assertArrayHasKey('isvalid', $result);
		$this->assertArrayHasKey('error', $result);
	}

	/**
	 * test testSanitizeInput
	 * @return void
	 */
	public function testSanitizeInput()
	{
		$this->assertRegExp('/[^a-zA-Z0-9._\-+\s]/i', 'ABCDEF%&^)(!+_');
		$this->assertNotRegExp('/[^a-zA-Z0-9._\-+\s]/i', 'abc123ABCDEF.+_');
	}

	/**
	 * test testBuildRecaptchaApiUri
	 * @return void
	 */
	public function testBuildRecaptchaApiUri()
	{
		$captcha = new \ReCaptcha\Captcha();
		$captcha->setPublicKey('MyPublicKey');
		$captcha->setTranslation('it');

		$result = $this->invokeMethod($captcha, '_buildServerURI');
		$expected = 'http://www.google.com/recaptcha/api/challenge?k=MyPublicKey&hl=it';
		$this->assertEquals($expected, $result);
	}

	/**
	 * test testBuildRecaptchaApiNoScriptUri
	 * @return void
	 */
	public function testBuildRecaptchaApiNoScriptUri()
	{
		$result = $this->invokeMethod(new \ReCaptcha\Captcha('es'), '_buildServerURI', array('noscript'));
		$expected = 'http://www.google.com/recaptcha/api/noscript?k=&hl=es';
		$this->assertEquals($expected, $result);
	}

	/**
	 * test testBuildUriHttps
	 * @return void
	 */
	public function testBuildUriHttps()
	{
		$result = $this->invokeMethod(new \ReCaptcha\Captcha('fr', TRUE), '_buildServerURI');
		$expected = 'https://www.google.com/recaptcha/api/challenge?k=&hl=fr';
		$this->assertEquals($expected, $result);
	}

	/**
	 * Call protected/private method of a class.
	 *
	 * @param object &$object    Instantiated object that we will run method on.
	 * @param string $methodName Method name to call
	 * @param array  $parameters Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	public function invokeMethod(&$object, $methodName, array $parameters = array())
	{
	    $reflection = new \ReflectionClass(get_class($object));
	    $method = $reflection->getMethod($methodName);
	    $method->setAccessible(true);

	    return $method->invokeArgs($object, $parameters);
	}
}
