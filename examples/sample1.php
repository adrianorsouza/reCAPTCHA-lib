<?php require_once dirname(__DIR__) .'/vendor/autoload.php'; ?>

<!doctype html>
<html>
 <head>
     <meta charset="utf-8">
     <title>Sample 1</title>
 </head>
<body>
<div class="form">
   <form action="sample1.php" method="post">
      <label>Username</label> <input type="text" name="username">
      <hr>
      <?php
         try {
            $captcha = new \ReCaptcha\Captcha();
            // Set your keys
            $captcha->setPublicKey('YourPublicKey');
            $captcha->setPrivateKey('YourPrivateKey');

            echo $captcha->displayHTML();
            // throws an error if recaptcha is invalid
            if ( !$captcha->isValid() ) {
               throw new \ReCaptcha\CaptchaException($captcha->getError());
            }

         } catch (ReCaptcha\CaptchaException $e) {
            echo ($e->errorMessage());
         }
      ?> <br>
      <input type="submit" value="Check">
   </form>
</div>
</body>
</html>
