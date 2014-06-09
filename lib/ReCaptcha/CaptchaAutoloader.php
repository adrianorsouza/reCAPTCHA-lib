<?php

/**
 * Autoloader for reCAPTCHA Library
 * The use of this file is only needed if you don't use composer autoloader
 * Include this file to autoload reCAPTCHA lib classes
 *
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
