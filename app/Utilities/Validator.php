<?php

/// 'inspired' by the CartItemValidator in the Darryldecode/Cart package..
/// (AKA -> outright copy-paste + a few extra features..)

namespace App\Utilities;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

class Validator
{
    protected static $factory, $locale = 'en';

    public static function setLocale(String $locale)
    {
        static::$locale = $locale;
    }

    public static function getLocale()
    {
        return static::$locale;
    }

    public static function instance(string $lang = 'en')
    {
        if ( ! static::$factory) {
            $loader = new FileLoader(
                new Filesystem(),'/Translations'
            );

            $translator = new Translator($loader, $lang);
            static::$factory = new Factory($translator);
        } else {
            $trans = static::$factory->getTranslator();
            if ($trans->getLocale() !== $lang) {
                $trans->setLocale($lang);
            }
        }

        return static::$factory;
    }

    public static function __callStatic($method, $args)
    {
        $instance = static::instance(self::getLocale());

        switch (count($args))
        {
            case 0:
                return $instance->$method();

            case 1:
                return $instance->$method($args[0]);

            case 2:
                return $instance->$method($args[0], $args[1]);

            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);

            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);

            default:
                return call_user_func_array(array($instance, $method), $args);
        }
    }
}