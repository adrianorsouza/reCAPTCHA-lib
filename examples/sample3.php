<?php
require_once 'vendor/autoload.php';
use ReCaptcha\Captcha;
use ReCaptcha\CaptchaException;
?>
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
         		// set pt_BR language
				$captcha = new Captcha();
         		// set Public Key
				$captcha->setPublicKey('YourPublicKey');
         		// set Standard_Theme clean
				echo $captcha->displayHTML('clean');

			} catch (CaptchaException $e) {
				echo ($e->errorMessage());
			}
			?>    <br>
			<input type="submit" value="Check">
		</form>
	</div>
</body>
</html>
