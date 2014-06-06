<?php require_once dirname(__DIR__) .'/vendor/autoload.php';

// Verify form reCAPTCHA submission
if ( strtoupper($_SERVER['REQUEST_METHOD']) === 'POST' ) {
   try {
      echo 'Username: ' . $_POST['username'] . '<br>';

      $captcha = new \ReCaptcha\Captcha('br'); // pt_BR language
      $captcha->setPrivateKey('YourPrivateKey');

      if ( !$captcha->isValid() ) {

         $captcha->setError();
         throw new \ReCaptcha\CaptchaException($captcha->getError());

      } else {

         echo '<span style="color:green">Captcha Correct!!!</span>';
      }

   } catch (ReCaptcha\CaptchaException $e) {
      echo ($e->errorMessage());
   }
}
