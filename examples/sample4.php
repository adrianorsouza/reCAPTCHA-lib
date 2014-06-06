<?php require_once dirname(__DIR__) .'/vendor/autoload.php'; ?>

<!doctype html>
<html>
 <head>
     <meta charset="utf-8">
     <title>Sample 4</title>
 </head>
<body>
<div class="form">
<form action="verify.php" method="post">
   <label>Username</label> <input type="text" name="username">
   <hr>
   <?php
      try {

         $captcha = new \ReCaptcha\Captcha();
         // Set Global options by adding a captcha_config.php file
         $captcha->setConfig( dirname(__DIR__) . '/path/to/config/captcha_config.php' );

         echo $captcha->displayHTML();

      } catch (ReCaptcha\CaptchaException $e) {
         echo ($e->errorMessage());
      }
   ?>    <br>
   <input type="submit" value="Check">
</form>
</div>
</body>
</html>
