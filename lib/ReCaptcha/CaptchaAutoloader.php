<?php
/**
 * The use of this file is only needed if you don't use composer autoloader
 * Include this file to autoload reCAPTCHA lib classes
 *
 * @author  Adriano Rosa (http://adrianorosa.com)
 * @package ReCaptcha
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link    https://github.com/adrianorsouza/reCAPTCHA-lib
 * @link    https://developers.google.com/recaptcha/ reCAPTCHA API docs Reference
 * @version 0.1.3 2016
 */

/**
 * Autoloader for reCAPTCHA Library
 *
 * @param $class Classname
 * @return void
 */
function CaptchaAutoload($class)
{
    $ns = 'ReCaptcha';

    if (strncmp($ns, $class, strlen($ns)) === 0) {
        $classname = substr($class, strlen($ns));
        $file = __DIR__ . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';

        if (is_readable($file)) {
            include $file;
        }
    }
}

spl_autoload_register('CaptchaAutoload');
