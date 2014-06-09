<?php
require_once 'vendor/autoload.php';
use ReCaptcha\Captcha;
use ReCaptcha\CaptchaException;
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Custom Sample 5</title>
</head>
<body>
	<div class="form">
		<h3>Set a different Timeout for server API</h3>
		<form action="verify.php" method="post">
			<label>Username</label> <input type="text" name="username">
			<hr>
			<?php
			try {
         // Set Global captcha_config.php
				$my_config = '/path/to/captcha_config.php';

				$captcha = new Captcha();
				$captcha->setConfig($my_config);
				echo $captcha->displayHTML('custom');

			} catch (CaptchaException $e) {

				echo ($e->errorMessage());
			}
			?>    <br>
			<input type="submit" value="Check">
		</form>
	</div>
</body>
</html>
