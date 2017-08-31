<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7d6828a8f97faa178ed9a3d00355b78d
{
    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'Vis\\ApplyForm\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Vis\\ApplyForm\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Vis\\ApplyForm\\Controllers\\ApplyFormController' => __DIR__ . '/../..' . '/src/Http/Controllers/ApplyFormController.php',
        'Vis\\ApplyForm\\Helpers\\InputCleaner' => __DIR__ . '/../..' . '/src/Helpers/InputCleaner.php',
        'Vis\\ApplyForm\\Models\\AbstractApplyForm' => __DIR__ . '/../..' . '/src/Models/AbstractApplyForm.php',
        'Vis\\ApplyForm\\Models\\AbstractApplyFormSetting' => __DIR__ . '/../..' . '/src/Models/AbstractApplyFormSetting.php',
        'Vis\\ApplyForm\\Models\\ApplyFormFactory' => __DIR__ . '/../..' . '/src/Models/ApplyFormFactory.php',
        'Vis\\ApplyForm\\Models\\ApplyFormSettingEmail' => __DIR__ . '/../..' . '/src/Models/ApplyFormSettingEmail.php',
        'Vis\\ApplyForm\\Models\\ApplyFormSettingMessage' => __DIR__ . '/../..' . '/src/Models/ApplyFormSettingMessage.php',
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
