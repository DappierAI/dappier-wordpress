<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit819c1f8de6f8a01ab89ea3ed7e02471d
{
    public static $files = array (
        'd05ecc14ff93fd612a81ec7e8ab4c2c9' => __DIR__ . '/..' . '/yahnis-elsts/plugin-update-checker/load-v5p4.php',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit819c1f8de6f8a01ab89ea3ed7e02471d::$classMap;

        }, null, ClassLoader::class);
    }
}
