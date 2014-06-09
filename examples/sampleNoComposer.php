<?php require_once 'ReCaptcha/CaptchaAutoloader.php'; ?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Sample Without Composer</title>
</head>
<body>
	<div class="form">
		<form action="sample1.php" method="post">
			<?php
			try {
				$captcha = new \ReCaptcha\Captcha();

				$captcha->setPublicKey('YourPublicKey');
				$captcha->setPrivateKey('YourPrivateKey');
				echo $captcha->displayHTML();

				if ( !$captcha->isValid() ) {
					throw new \ReCaptcha\CaptchaException($captcha->getError());
				}

			} catch (\ReCaptcha\CaptchaException $e) {
				echo ($e->errorMessage());
			}
			?>
			<input type="submit" value="Check">
		</form>
	</div>
</body>
</html>
