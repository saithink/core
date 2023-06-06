<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitbd1d02a41004b0b74434e3507f7d73bd
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitbd1d02a41004b0b74434e3507f7d73bd', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitbd1d02a41004b0b74434e3507f7d73bd', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitbd1d02a41004b0b74434e3507f7d73bd::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
