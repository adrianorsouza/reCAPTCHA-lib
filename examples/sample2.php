<?php require_once dirname(__DIR__) .'/vendor/autoload.php'; ?>

<!doctype html>
<html>
 <head>
     <meta charset="utf-8">
     <title>Sample 2</title>
 </head>
<body>
<div class="form">
   <h3>Options:'lang = br', 'tabindex = 1'</h3>
   <form action="verify.php" method="post">
   <label>Username</label> <input type="text" name="username">
   <hr>
   <?php
      try {
         // set reCAPTCHA run over HTTPS
         $captcha = new \ReCaptcha\Captcha(null, true);
         // set my own lang translate
         $captcha->setTranslation('br', '/path/to/my/own/language-file/es');
         // set my public key
         $captcha->setPublicKey('YourPublicKey');
         // display options: Theme white, lang pt-BR and tabindex = 1
         echo $captcha->displayHTML('white', array('lang' => 'br', 'tabindex' => 1));

      } catch (ReCaptcha\CaptchaException $e) {
         echo ($e->errorMessage());
      }
   ?> <br>
   <input type="submit" value="Check">
</form>
</div>
</body>
