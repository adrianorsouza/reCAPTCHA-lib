<?php
require_once 'vendor/autoload.php';
use ReCaptcha\Captcha;
use ReCaptcha\CaptchaException;
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Custom Translations Sample 6</title>
</head>
<body>
	<div class="form">
		<h3>Custom translation</h3>
		<form action="verify.php" method="post">
			<label>Username</label> <input type="text" name="username">
			<hr>
			<?php
			$my_options = array(
				'custom_theme_widget'=>'recaptcha_widget',
				'tabindex' => 0,
				'lang' => 'de',
				'custom_translations' => array(
					'instructions_visual' => 'Geben Sie den angezeigten Text ein',
					'instructions_audio'  => 'Geben Sie das Gehörte ein:',
					'play_again'          => 'Wort erneut abspielen   ',
					'cant_hear_this'      => 'Wort als MP3 herunterladen',
					'visual_challenge'    => 'Captcha abrufen',
					'audio_challenge'     => 'Audio-Captcha abrufen',
					'refresh_btn'         => 'Neues Captcha abrufen',
					'help_btn'            => 'Hilfe',
					'incorrect_try_again' => 'Falsch, bitte versuchen Sie es erneut.',
					)
				);

			try {

				$captcha = new Captcha();
				echo $captcha->displayHTML('custom', $my_options);

			} catch (CaptchaException $e) {

				echo ($e->errorMessage());
			}
			?>    <br>
			<input type="submit" value="Check">
		</form>
	</div>
</body>
</html>
