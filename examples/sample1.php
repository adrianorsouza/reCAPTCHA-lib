<?php
require_once 'vendor/autoload.php';
use ReCaptcha\Captcha;
use ReCaptcha\CaptchaException;
?>
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
				$captcha = new Captcha();

				$captcha->setPublicKey('YourPublicKey');
				$captcha->setPrivateKey('YourPrivateKey');
				echo $captcha->displayHTML();

				if ( !$captcha->isValid() ) {
					throw new CaptchaException($captcha->getError());
				}

			} catch (CaptchaException $e) {
				echo ($e->errorMessage());
			}
			?> <br>
			<input type="submit" value="Check">
		</form>
	</div>
</body>
</html>
