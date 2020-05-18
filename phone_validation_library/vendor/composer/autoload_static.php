<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita365d19358bf5300c7ceb8c2adbe49d6
{
    public static $prefixLengthsPsr4 = array (
        'l' => 
        array (
            'libphonenumber\\' => 15,
        ),
        'G' => 
        array (
            'Giggsey\\Locale\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'libphonenumber\\' => 
        array (
            0 => __DIR__ . '/..' . '/giggsey/libphonenumber-for-php/src',
        ),
        'Giggsey\\Locale\\' => 
        array (
            0 => __DIR__ . '/..' . '/giggsey/locale/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita365d19358bf5300c7ceb8c2adbe49d6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita365d19358bf5300c7ceb8c2adbe49d6::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}