<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7d6828a8f97faa178ed9a3d00355b78d
{
    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'Vis\\ApplyForms\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Vis\\ApplyForms\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Vis\\ApplyForms\\Controllers\\ApplyFormController' => __DIR__ . '/../..' . '/src/Http/Controllers/ApplyFormController.php',
        'Vis\\ApplyForms\\Models\\AbstractApplyForm' => __DIR__ . '/../..' . '/src/Models/AbstractApplyForm.php',
        'Vis\\ApplyForms\\Models\\ApplyFormFactory' => __DIR__ . '/../..' . '/src/Models/ApplyFormFactory.php',
        'Vis\\ApplyForms\\Models\\ApplyFormInputCleaner' => __DIR__ . '/../..' . '/src/Models/ApplyFormInputCleaner.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7d6828a8f97faa178ed9a3d00355b78d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7d6828a8f97faa178ed9a3d00355b78d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7d6828a8f97faa178ed9a3d00355b78d::$classMap;

        }, null, ClassLoader::class);
    }
}
