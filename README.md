PHP Library for reCAPTCHA Google's API
==============================================

This library abstracts what the best you can do to configure and customize Google's reCAPTCHA API widget and get it on your site or app.

**Stable version: v0.1.0**

###What does this library do?  

- Insert a reCAPTCHA widget into your form. 
- Validate a given image Captcha returns the result.
- Supports to different Standard Theme and Custom Theme.
- Full Languague support and custom language translation.
- Custom Global configuration if you need to run reCAPTCHA in many forms.
- Individualy configuration for each widget.
- Timeout configuration.
- Error handling support.

##Quick Installation via composer
You can find this library at [Composer/Packagist](https://packagist.org/packages/recaptcha-lib/recaptcha).

```
$ composer require "recaptcha-lib/recaptcha:v0.1.0"
```

##Usage
Display default options and theme.

```PHP
$captcha = new \ReCaptcha\Captcha();
$captcha->setPublicKey('YourPublicKey');
$captcha->setPrivateKey('YourPrivateKey');
echo $captcha->displayHTML();
```

##Samples
ReCaptcha-lib provides many ways to display and configure a reCAPTCHA widget.
A theme can be set by a given name e.g: `Captcha::displayHTML('clean')`.

|`displayHTML();`|`displayHTML('white');`|`displayHTML('clean');`|`displayHTML('custom');`
|--------- |--------- |--------- |--------- |
|![image][1] |![image][2] |![image][3] |![image][4]
[1]: https://s3-sa-east-1.amazonaws.com/adrianorosa/github/sample1.png
[2]: https://s3-sa-east-1.amazonaws.com/adrianorosa/github/sample2.png
[3]: https://s3-sa-east-1.amazonaws.com/adrianorosa/github/sample3.png
[4]: https://s3-sa-east-1.amazonaws.com/adrianorosa/github/sample4.png


##Options
Custom options can be set by passing an array of values to `Captcha::displayHTML()`
```php
array('lang'=>'it', 'tabindex'=>2);
```
Changing theme and language of the widget

```php
	$captcha->displayHTML(
		'custom', 
		array(
			'lang'=>'it', 
			'custom_theme_widget'=>'my_container_id'
		)
	);
```
Set a language in object constructor
```php
$captcha = new \ReCaptcha\Captcha('pt');
```
Use reCAPTCHA over SSL site

```php
$captcha = new \ReCaptcha\Captcha(NULL, TRUE);
```	

###Configuration
Set a Global configuration by creating an external file with all options and configurations to be read globally.

File: `captcha_config.php`.

```php
// your KEYs
$CAPTCHA_CONFIG['publicKey']  = '6Ldoa_YourPublicKey';
$CAPTCHA_CONFIG['privateKey'] = '6Ldoa_YourPrivateKey';

// reCAPTCHA options
$CAPTCHA_CONFIG['recaptchaOptions'] = array(
                     'theme'    => 'custom',
                     'lang'     => 'it',
                     'tabindex' => 0
                     );

// Enable/Disable when you use reCAPTCHA on an SSL site
$CAPTCHA_CONFIG['ssl'] = false;

// Set Server API timeout
$CAPTCHA_CONFIG['timeout'] = 10;
```

Then pass its path to `Captcha::setConfig($path)`;

```php
// Set Global captcha_config.php
$my_config = '/path/to/my/captcha_config.php';
$captcha = new \ReCaptcha\Captcha();
$captcha->setConfig($my_config);
```

###Internationalization

###Customization

###Create your own custom theme


####Notes

In order to start using this library you must generate your reCAPTCHA's API Key from [Google's reCAPTCHA](https://www.google.com/recaptcha/admin/create) site.