<?php
/**
 * PHP Library for reCAPTCHA Google's API v1
 * This is a PHP wrapper library that handles calling Google's reCAPTCHA API widget
 *
 * This library abstracts all possible configurations, customization to setup and
 * display a Google's reCAPTCHA API widget in your site or app.
 *
 * PHP version 5.3+
 *
 * NOTE:
 * In order to use Google's reCAPTCHA widget you must generate your API Key
 * https://www.google.com/recaptcha/admin/create.
 *
 * @date 2014-06-01
 *
 * @author  Adriano Rosa (http://adrianorosa.com)
 * @package ReCaptcha
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link    https://github.com/adrianorsouza/reCAPTCHA-lib
 * @link    https://developers.google.com/recaptcha/ reCAPTCHA API docs Reference
 * @version 0.1.3 2016
 */

namespace ReCaptcha;

/**
 * CaptchaTheme
 * Handle custom theming, reCAPTCHA's options and users language
 *
 * @author  Adriano Rosa (http://adrianorosa.com)
 * @package ReCaptcha
 * @version 0.1.3 2016
 */
class CaptchaTheme
{
	/**
	 * reCAPTCHA Theme default options
	 * RecaptchaOptions Reference: {@link: https://developers.google.com/recaptcha/docs/customization}
	 *
	 * @access protected
	 * @var array
	 */
	protected $_recaptchaOptions = array(
		'theme'               => 'red',
		'lang'                => null, // null means to use built-in
		'custom_translations' => null,
		'custom_theme_widget' => null,
		'tabindex'            => 0
		);

	/**
	 * List of Standard Theme names available
	 * Standard names Reference: {@link: https://developers.google.com/recaptcha/docs/customization#Standard_Themes}
	 *
	 * @access protected
	 * @var array
	 */
	protected $_standardThemes = array('red','white','blackglass','clean');

	/**
	 * Theme JavaScript wrapper
	 *
	 * @var string
	 */
	protected $_optionsWrapper = '<script type="text/javascript">var RecaptchaOptions = %s;</script>';

	/**
	 * For comparison of built in i18n languages
	 *
	 * @access protected
	 * @var array
	 */
	protected $_builtInlang = array(
		'English'    => 'en',
		'Dutch'      => 'nl',
		'French'     => 'fr',
		'German'     => 'de',
		'Portuguese' => 'pt',
		'Russian'    => 'ru',
		'Spanish'    => 'es',
		'Turkish'    => 'tr',
		);

	/**
	 * Standard Theme
	 * Display's Theme customization for reCAPTCHA widget
	 * by writting a snippet for Standard_Themes and Custom_Theming
	 *
	 * @param string $theme_name Optional theme name. NOTE: overwrite theme if it's set in an external config
	 * @param array $options reCAPTCHA Associative array of available options. NOTE: overwrite options set in an external config
	 * @return string Standard_Theme | Custom_Theme | Fallback default reCAPTCHA theme
	 */
	protected function _theme($theme_name = NULL, $options = array())
	{
		if ( count($options) > 0 ) {
		 	// Avoid invalid options passed via array
			foreach ($options as $opt => $value) {
				if ( !array_key_exists($opt, $this->_recaptchaOptions) ) {
					unset($options[$opt]);
				}
			}
		}

	  	// Avoid empty values
		foreach ($this->_recaptchaOptions as $key => $value) {
			if ( NULL === $value || $value === 0 ) {
				unset($this->_recaptchaOptions[$key]);
			}
		}

		$this->_recaptchaOptions = array_merge($this->_recaptchaOptions, $options);

		if ( NULL !== $theme_name ) {
			$this->_recaptchaOptions['theme'] = $theme_name;
		}

		// Skip to default reCAPTCHA theme if there is no options
		if ( count($this->_recaptchaOptions) == 0 ) {
			return;
		}

		// Whether lang option value is not built-in try to set it from a translation file
		if ( isset($this->_recaptchaOptions['lang'])
			&& !in_array($this->_recaptchaOptions['lang'], $this->_builtInlang)
			&& !isset($this->_recaptchaOptions['custom_translations']) ) {
			$this->setTranslation($this->_recaptchaOptions['lang']);
		}

		// Whether theme empty set default theme to default for FALLBACK
		if ( !isset($this->_recaptchaOptions['theme']) && count($this->_recaptchaOptions) > 0 ) {
			$this->_recaptchaOptions['theme'] = 'red';
		}

		// Skip to default reCAPTCHA theme if it's set to 'red' and there is no options at all
		if ( $this->_recaptchaOptions['theme'] === 'red' && count($this->_recaptchaOptions) == 1 ) {
			return;
		}

		  // Whether the theme name is Standard_Themes or not
		if ( in_array($this->_recaptchaOptions['theme'], $this->_standardThemes) ) {

			unset($this->_recaptchaOptions['custom_theme_widget']);
			$js_options = json_encode($this->_recaptchaOptions);

			return sprintf($this->_optionsWrapper, $js_options);

		} elseif ( $this->_recaptchaOptions['theme'] === 'custom' ) {
			// Custom theme MUST have an option [custom_theme_widget: ID_some_widget_name] set for recaptcha
			// If this option is not set, we make it.
			if ( !isset($this->_recaptchaOptions['custom_theme_widget']) ) {
				$this->_recaptchaOptions['custom_theme_widget'] = 'recaptcha_widget';
			}

			$custom_template = $this->custom_theme($this->_recaptchaOptions['custom_theme_widget']);

			$js_options = json_encode($this->_recaptchaOptions);
			return sprintf($this->_optionsWrapper, $js_options) . $custom_template;
		}
		// FALLBACK to red one default theme
		return;
	}

	/**
	 * Custom Theme Template
	 * In order to use a custom theme, you must set reCAPTCHA options correctly,
	 * also provide a custom CSS to display it properly.
	 * Fully custom theme reference: {@link: https://developers.google.com/recaptcha/docs/customization#Custom_Theming}
	 *
	 * @access public
	 * @param string $widget_id The ID name for wrapper container
	 * @return string
	 */
	public function custom_theme($widget_id = 'recaptcha_widget')
	{
		$captcha_html = '
		<div id="'. $widget_id .'" style="display:none">
		<div id="recaptcha_image"></div>
		<div class="recaptcha_only_if_incorrect_sol" style="color:red">'. $this->i18n("incorrect_try_again") .'</div>

		<span class="recaptcha_only_if_image">'. $this->i18n('instructions_visual') .'</span>
		<span class="recaptcha_only_if_audio">'. $this->i18n('instructions_audio') .'</span>

		<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" placeholder="'. $this->i18n('instructions_visual') .'" />

		<div><a href="javascript:Recaptcha.reload()">'. $this->i18n('refresh_btn') .'</a></div>
		<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type(\'audio\')">'. $this->i18n('audio_challenge') .'</a></div>
		<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type(\'image\')">'. $this->i18n('visual_challenge') .'</a></div>

		<div><a href="javascript:Recaptcha.showhelp()">'. $this->i18n('help_btn') .'</a></div>
		</div>';

		return $captcha_html;
	}

	/**
	 * Custom Translations
	 *
	 * In order to use custom translation (even if it is not built in specially for a custom theme),
	 * the translations must be set manually by this method or by passing the lang two letters code to
	 * instance constructor. It will set translation by a lang code given and overwrites other languages
	 * set in an external captcha_config file.
	 *
	 * NOTE: If translate file recaptcha.lang[lang_code].php with its respective translation strings
	 * within a folder i18n is not found a default lang English 'en' will be used instead.
	 *
	 * To use an external file for a custom lang translation you must create a copy of some lang file
	 * already done within the folder I18n and rename it as 'recaptcha.lang[lang_code].php'
	 * place it wherever you want and tell as second parameter its absolute $path without filename
	 *
	 * @param string $language Two letter language code e.g: (Italian = 'it')
	 * @param string $path Optional path to translate file
	 * @return void
	 */
	public function setTranslation($language = 'en', $path = NULL)
	{
		$this->_recaptchaOptions['lang'] = $language;

		if ( !in_array($this->_recaptchaOptions['lang'], $this->_builtInlang)
			|| isset($this->_recaptchaOptions['theme'])
			&& $this->_recaptchaOptions['theme'] === 'custom' ) {

			$custom_translations = $this->i18n(NULL, $path);
			$this->_recaptchaOptions['custom_translations'] = $custom_translations;
		}
	}

	/**
	 * Fetch I18n language line
	 *
	 * @param string $key The string translated
	 * @param string $path Optional path to your own language file
	 * @return array|string
	 */
	protected function i18n($key = NULL, $path = NULL)
	{
		static $RECAPTCHA_LANG;

		if ( $RECAPTCHA_LANG ) {
			return isset($key) ? $RECAPTCHA_LANG[$key] : $RECAPTCHA_LANG;
		}

		if ( !isset($this->_recaptchaOptions['lang']) ) {
			$language = $this->clientLang();
		} else {
			$language = $this->_recaptchaOptions['lang'];
		}

		$RECAPTCHA_LANG = array(
			'instructions_visual' => 'Enter the words above:',
			'instructions_audio'  => 'Type what you hear:',
			'play_again'          => 'Play sound again',
			'cant_hear_this'      => 'Download sound as MP3',
			'visual_challenge'    => 'Get an image CAPTCHA',
			'audio_challenge'     => 'Get an audio CAPTCHA',
			'refresh_btn'         => 'Get another CAPTCHA',
			'help_btn'            => 'Help',
			'incorrect_try_again' => 'Incorrect, please try again.'
			);

	  	// default: path/to/vendor/lib/ReCaptcha/I18n/recaptcha.lang.[langcode].php
		$path = ( NULL === $path )
			? __DIR__ . DIRECTORY_SEPARATOR . 'I18n'
			: $path;

		$language_file = rtrim($path, '/') . DIRECTORY_SEPARATOR . 'recaptcha.lang.' . $language . '.php';

		if ( file_exists( $language_file  ) ) {

			include_once $language_file;
		}

		return isset($key) ? $RECAPTCHA_LANG[$key] : $RECAPTCHA_LANG;
	}

	/**
	 * Get user's browser language preference
	 *
	 * @return string
	 *
	 * @deprecated will be remove in major release v1.0.0
	 */
	public function clientLang()
	{
		if ( isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ) {

			$language = explode(',', preg_replace('/(;\s?q=[0-9\.]+)|\s/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE']))));
			return strtolower($language[0]);
		}

		return;
	}
}
