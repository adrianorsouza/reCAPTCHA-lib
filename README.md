PHP Library for reCAPTCHA Google's API
======================================

Build status: [![Build Status](https://travis-ci.org/adrianorsouza/reCAPTCHA-lib.svg?branch=master)](https://travis-ci.org/adrianorsouza/reCAPTCHA-lib)
[![Latest Stable Version](https://poser.pugx.org/recaptcha-lib/recaptcha/v/stable.svg)](https://packagist.org/packages/recaptcha-lib/recaptcha)
[![Total Downloads](https://poser.pugx.org/recaptcha-lib/recaptcha/downloads.svg)](https://packagist.org/packages/recaptcha-lib/recaptcha)
[![Latest Unstable Version](https://poser.pugx.org/recaptcha-lib/recaptcha/v/unstable.svg)](https://packagist.org/packages/recaptcha-lib/recaptcha)

This is PHP library that handle Google's reCAPTCHA API, you can easy implement it into your site or app and be able to set custom configurations and options to display the widget as you want.

## Library Features

- Display a reCAPTCHA V1 widget onto your form.
- Validate a given input value for an image Captcha and returns the result.
- Support to different Standard Theme, Custom Theming and Template customization.
- Internationalization support and custom language strings translation.
- Custom Global configuration if you need to run reCAPTCHA in many forms.
- Individually configuration for each widget.
- Timeout configuration for request server API.
- Error handling.
- Uses socket connection for API calls fallback to CURL whether the function `fsockopen` is not available.
- Fully customization and optimization to display reCAPTCHA
- Ease implementation

## Quick Installation via composer
You can find this library at [Composer/Packagist](https://packagist.org/packages/recaptcha-lib/recaptcha).

```
$ composer require "recaptcha-lib/recaptcha:0.1.*"
```

### Optional Manual Install
If you don't use composer you'll have to download the latest [release](https://github.com/adrianorsouza/reCAPTCHA-lib/releases) of this library and unpack it within your project folder.

Once you've done it, you'll need only those files within the `ReCaptcha` folder *you can get rid of others files and samples in there, if you want to*.

The reCAPTCHA Libray comes with an [Autoloader](https://github.com/adrianorsouza/reCAPTCHA-lib/blob/master/lib/ReCaptcha/CaptchaAutoloader.php) that you can include in the top of your page. By using the autoloader there is no need of including files manually, see an example below:

```PHP
require_once 'ReCaptcha/CaptchaAutoloader.php';

$captcha = new \ReCaptcha\Captcha();
echo $captcha->displayHTML();
```

## Displaying reCAPTCHA
Display default options and theme.

```PHP
use ReCaptcha\Captcha;

$captcha = new Captcha();
$captcha->setPublicKey('YourPublicKey');
echo $captcha->displayHTML();
```

Display a Standard Theme, your API Keys and options from a config file.

```PHP
use ReCaptcha\Captcha;

$my_config = '/optional/path/to/captcha_config.php';
$captcha = new Captcha();
$captcha->setConfig($my_config);
echo $captcha->displayHTML();
```

To display a different theme and language straightforward.

```PHP
echo $captcha->displayHTML('theme_name', array('lang'=>'es'));
```

## [Samples](id:samples)
ReCaptcha-lib provides many ways to display and configure a reCAPTCHA widget.
A theme can be set by a given name e.g: `Captcha::displayHTML('clean')`. You can find all **Standard Theme** names in [https://developers.google.com/recaptcha/docs/customization](https://developers.google.com/recaptcha/docs/customization).

| `displayHTML();` | `displayHTML('white');` | `displayHTML('clean');` | `displayHTML('custom');`
|------------------|-------------------------|-------------------------|---------------------------
|![image][1]       | ![image][2]             | ![image][3]             | ![image][4]               |

[1]: http://adrianorsouza.github.io/reCAPTCHA-lib/images/sample1.png
[2]: http://adrianorsouza.github.io/reCAPTCHA-lib/images/sample2.png
[3]: http://adrianorsouza.github.io/reCAPTCHA-lib/images/sample3.png
[4]: http://adrianorsouza.github.io/reCAPTCHA-lib/images/sample4.png
*****

> **NOTE:** When using a `custom` theme, as you can see above, the template is displayed in pure HTML so you need to provide a CSS to display a custom theme properly to your users. The widget elements are wrapped within a `div` container default ID `recaptcha_widget` but you can change this ID using the option `custom_theme_widget`

```PHP
array('custom_theme_widget' => 'my_widget_id_name');
```

You can find more examples how to implement reCAPTCHA in [examples](https://github.com/adrianorsouza/reCAPTCHA-lib/tree/master/examples) folder.

## Verifying reCAPTCHA User's Answer
The Library has a method `Captcha::isValid()` to check the user's answer when performing a form post. It's easy to implement.

**Basic Verify**

```PHP
$captcha = new Captcha();
$captcha->setPrivateKey('YourPrivateKey');

if ( !$captcha->isValid() ) {

   echo $captcha->getError();
   // stop action

} else {

   echo 'Captcha Valid!!!';
   // do something else
}
```

The method `Captcha::isValid()` takes an action only when its encountered a `REQUEST_METHOD` `POST`, so you can set in the same page that method `Captcha::displayHTML()`

```PHP
$captcha = new Captcha();

$captcha->setPublicKey('YourPublicKey');
$captcha->setPrivateKey('YourPrivateKey');

// only perform when the form is submitted
if ( !$captcha->isValid() ) {
   echo $captcha->getError();
}

echo $captcha->displayHTML();
// something else ...
```

## [Options](id:options)
Custom options can be set by passing an array of values to `Captcha::displayHTML()`

```PHP
array('lang'=>'it', 'tabindex'=>2);
```
Custom theming and language of the widget

```PHP
$captcha->displayHTML(
	'custom',
	array(
		'lang'=>'it',
		'custom_theme_widget'=>'my_container_id'
	)
);
```

## [Configuration](id:configuration)
#### Changing Widget Language
Set a language in object constructor as a first parameter.

```PHP
$captcha = new Captcha('pt');
```

#### Run reCAPTCHA Over SSL
When using SSL site, to avoid getting browser certificate warnings when you display reCAPTCHA set a second parameter to `TRUE` in object constructor.

```PHP
$captcha = new Captcha(NULL, TRUE);
```
`NULL` set the language to default system built-in translation. `TRUE` enable call API using https://

#### Timeout
The Library has a timeout when performing a request server API to verify the user's answer. The default timeout value is 10. If you want to change.

```PHP
$captcha->timeout = 100;
```

#### Global Config
If you want instead of passing arrays all the time into your widget you might set a [Global configuration](https://github.com/adrianorsouza/reCAPTCHA-lib/blob/master/lib/ReCaptcha/captcha_config.php) by creating an external file with all options and configurations to be read globally.

Sample File: `captcha_config.php`.

```PHP
// your KEYs
$CAPTCHA_CONFIG['publicKey']  = '6Ldoa_YourPublicKey';
$CAPTCHA_CONFIG['privateKey'] = '6Ldoa_YourPrivateKey';

// reCAPTCHA options
$CAPTCHA_CONFIG['recaptchaOptions'] = array(
                     'theme'    => 'custom',
                     'lang'     => 'it',
                     'tabindex' => 0
                     );

// Enable/Disable when you use reCAPTCHA on SSL site
$CAPTCHA_CONFIG['ssl'] = false;

// Set Server API timeout
$CAPTCHA_CONFIG['timeout'] = 10;
```

Then pass its path to the method: `Captcha::setConfig($path)`.

```PHP
// Set Global config captcha_config.php
$my_config = '/path/to/my/captcha_config.php';
$captcha = new Captcha();
$captcha->setConfig($my_config);
```

## Internationalization
This library has two different approaches to display reCAPTCHA widget in a different language to the user's screen.

#### Built-in Language
It's a default Google's reCAPTCHA API language translation, so the widget strings are shown according the user's browser language preference, whether user's language is not in the system built-in the English will be used instead.

#### Custom language
If you are using `Custom Theme Template` you must set a customized translation as well, despite using any **Standard Theme** that you need to display your reCAPTCHA widget in a different language which is not available in system built-in you can use your own translation strings.

This library offers you a simple way to customize translation strings, within a folder [`I18n`](https://github.com/adrianorsouza/reCAPTCHA-lib/tree/master/lib/ReCaptcha/I18n) there's a few languages already translated in there. If you want to use one of them just pass the language code as first parameter to the object constructor. See examples in section [options](#options) and [configuration](#configuration) above.

Even if your language is not in there, you have two options to get your widget translated.


##### Create a Language File
You might translate the widget strings by writing a file `recaptcha.lang.[lang_code].php` *take a look one of them within `I18n` folder* as a reference*, place it somewhere in your `dir`, then tell to the library where it is by passing its path as a second parameter to the method `Captcha::setTranslation(lang_code, path)`.

```PHP
$myCustomLang = '/optional/path/to/file/lang'; // no need trailing slash.
$captcha->setTranslation('fr', $myCustomLang);
```

This will point to a file: `/optional/path/to/file/lang/recaptcha.lang.fr.php`

> **NOTE:** your custom Lang file must be named as `recaptcha.lang.[lang_code].php` where `lang_code` is your language abbreviation e.g. 'French' is `fr`.

##### Optional
Instead of create an external Lang file you may also translate your widget strings by passing an array to the `custom_translations` option:

```PHP
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
      'incorrect_try_again' => 'Falsch, bitte versuchen Sie es erneut.'
    )
);
```


**Collaborate**
If you want to translate a language file and collaborate, fork this project do it and send us a pull request.

## Theme Customization
There are a few **Standard Theme** ready to go with, as written in [Samples](#samples), but to go any further, it's possible to style as you want by adding an option `theme=>custom` as array to config file, by parameter option or straightforward `Captcha::displayHTML('custom')`. To do so set one of these options and create a CSS to prettify it, you can checkout [demo](http://www.google.com/recaptcha/demo/custom) or see more in reCAPTCHA [Custom Theming](https://developers.google.com/recaptcha/docs/customization#Custom_Theming) Docs.

## Documentation
This library has a better documentation generated by PHPDoc you can take a look at [Documentation Page](http://adrianorsouza.github.io/reCAPTCHA-lib/).

## Bugs

Have you found a bug? Please open a new [issue](https://github.com/adrianorsouza/reCAPTCHA-lib/issues).

### Author
Adriano Rosa
[@adrianorosa](https://twitter.com/adrianorosa)


### Notes

In order to start using this library you must generate your reCAPTCHA's API Key from [Google's reCAPTCHA](https://www.google.com/recaptcha/admin) site.

### License

This software is licensed under the MIT License. Please read LICENSE for information on the software availability and distribution.
