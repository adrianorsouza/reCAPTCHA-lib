<?php
require_once 'vendor/autoload.php';
use ReCaptcha\Captcha;
use ReCaptcha\CaptchaException;
?>
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
				$captcha = new Captcha(null, true);
				$captcha->setPublicKey('YourPublicKey');
				echo $captcha->displayHTML('white', array('lang' => 'br', 'tabindex' => 1));

			} catch (CaptchaException $e) {
				echo ($e->errorMessage());
			}
			?> <br>
			<input type="submit" value="Check">
		</form>
	</div>
</body>
</html>
