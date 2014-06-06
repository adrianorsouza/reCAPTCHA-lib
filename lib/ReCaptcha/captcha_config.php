<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { exit(); }

// ---------------------------------------------------
// Use this config file to set a Global configuration for reCAPTCHA
// this file can be placed anywhere as long you tell where it is by
// passing its path to the function setConfig(PATH)
// ---------------------------------------------------

// In order to run reCAPTCHA get your KEYS at: {@link https://www.google.com/recaptcha/admin#createsite}
$CAPTCHA_CONFIG['publicKey']  = '6Ldoa_YourPublicKey'; // your public KEY
$CAPTCHA_CONFIG['privateKey'] = '6Ldoa_YourPrivateKey'; // your private KEY

// Optional: Array of reCAPTCHA options further info at:
// {@link https://developers.google.com/recaptcha/docs/customization#Custom_Theming}
$CAPTCHA_CONFIG['recaptchaOptions'] = array(
                                       'theme'    => 'custom',
                                       'lang'     => 'it',
                                       'tabindex' => 0
                                       );

// Set this TRUE to avoid getting browser warnings when you use reCAPTCHA on an SSL site
$CAPTCHA_CONFIG['ssl'] = false;

