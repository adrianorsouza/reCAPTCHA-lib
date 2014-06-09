<?php
require_once 'vendor/autoload.php';
use ReCaptcha\Captcha;
use ReCaptcha\CaptchaException;

// Verify form reCAPTCHA submission
if ( strtoupper($_SERVER['REQUEST_METHOD']) === 'POST' ) {
	try {
		echo 'Username: ' . $_POST['username'] . '<br>';
      // pt_BR language
		$captcha = new Captcha('br');
		$captcha->setPrivateKey('YourPrivateKey');

      // Optional set different timeout
		$captcha->timeout = 50;
		if ( !$captcha->isValid() ) {

			$captcha->setError();
			throw new CaptchaException($captcha->getError());

		} else {

			echo '<span style="color:green">Captcha Valid!!!</span>';
		}

	} catch (CaptchaException $e) {
		echo ($e->errorMessage());
	}
}
