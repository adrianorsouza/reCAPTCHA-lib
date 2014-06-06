<?php require_once dirname(__DIR__) .'/vendor/autoload.php'; ?>

<!doctype html>
<html>
 <head>
     <meta charset="utf-8">
     <title>Sample 3</title>
 </head>
<body>
<div class="form">
<form action="verify.php" method="post">
   <label>Username</label> <input type="text" name="username">
   <?php
      try {
         // set global language to pt_BR
         $captcha = new \ReCaptcha\Captcha('br');
         // set your Public Key
         $captcha->setPublicKey('YourPublicKey');
         // disply Standard_Theme clean
         echo $captcha->displayHTML('clean');

      } catch (ReCaptcha\CaptchaException $e) {
         echo ($e->errorMessage());
      }
   ?>    <br>
   <input type="submit" value="Check">
</form>
</div>
</body>
</html>
