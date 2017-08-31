# Articles
Пакет для Laravel 5.4+ предназначенный работы с формами. 
Требует и автоматически устанавливает пакет https://github.com/arturishe21/mail_templates_l5 для работы с отправкой писем.
Поддерживает интеграцию с Google Invisible reCaptcha.

Разделы
1. [Установка](#Установка)
2. [Настройка](#Настройка)
3. [VIS-CMS](#VIS-CMS)
4. 

## Установка
Выполняем
```json
    composer require "vis/apply_form_l5":"1.*"
```

Добавляем ApplyFormsServiceProvider в массив ServiceProviders в config/app.php
```php
   Vis\ApplyForm\ApplyFormsServiceProvider::class,
```

Выполняем миграцию таблиц
```php
   php artisan migrate --path=vendor/vis/apply_form_l5/src/Migrations
```

Публикуем config, js, nodes
```php
    php artisan vendor:publish --provider="Vis\ApplyForm\ApplyFormsServiceProvider" --force
```

## Настройка
В файле config/apply_form/apply_form.php </br>

Заполняем список используемых классов форм заявок
```php
    /**
     * List of apply forms to be registered in application
     * Example: 'anonymous_message'      => App\Models\ApplyForm\ApplyFormAnonymousMessage::class,
     */
    'apply_forms' => [
        'anonymous_message'      => App\Models\ApplyForm\ApplyFormAnonymousMessage::class,
    ],
```

При необходимости включаем капчу и добавляем публичный и скрытый ключи
```php  
    /**
     * Defines usage of Google Invisible reCaptcha
     * @link https://www.google.com/recaptcha/admin
     */
    'grecaptcha' => [
        'enabled'    => true,
        'site_key'   => '',
        'secret_key' => ''
    ],
```
Включаем или выключаем сохранение формы заявки с использованием транзакции <br>
Желательно использовать значение false при разработке и true при експлутации приложения.
```php
    /**
     * Enables transaction in ApplyForm saving method
     * Recommended false in development and true in production
     */
    'transaction_enabled' => false,
```

## VIS-CMS
В \config\builder\admin.php дописываем массив
```php
    array(
        'title' => 'Заявки',
        'icon'  => 'list',
        'check' => function() {
            return Sentinel::hasAccess('admin.apply_form.view');
        },
        'submenu' => array(
            array(
                'title' => "Отправленные заявки",
                'check' => function() {
                    return Sentinel::hasAccess('admin.apply_form.view');
                },
                'submenu' => array(
                //определение tb-definitions для форм заявок
                )
            ),
            array(
                'title' => "Сообщения ответы",
                'link'  => '/vis_apply_form_setting_messages',
                'check' => function() {
                    return Sentinel::hasAccess('admin.vis_apply_form_setting_messages.view');
                }
            ),
            array(
                'title' => "E-mail адреса",
                'link'  => '/vis_apply_form_setting_emails',
                'check' => function() {
                    return Sentinel::hasAccess('admin.vis_apply_form_setting_emails.view');
                }
            ),
        )
    ),
```

Добавляем права доступа в config/builder/tb-definitions/groups.php и добавляем их к группам.
```php
    'Заявки' => array(
        'admin.apply_form.view'   => 'Просмотр',
        'admin.apply_form.create' => 'Создание',
        'admin.apply_form.update' => 'Редактирование',
        'admin.apply_form.delete' => 'Удаление',
    ),
    'Заявки - Сообщения' => array(
        'admin.vis_apply_form_setting_messages.view'   => 'Просмотр',
        'admin.vis_apply_form_setting_messages.create' => 'Создание',
        'admin.vis_apply_form_setting_messages.update' => 'Редактирование',
        'admin.vis_apply_form_setting_messages.delete' => 'Удаление',
    ),
    'Заявки - Имейлы' => array(
        'admin.vis_apply_form_setting_emails.view'   => 'Просмотр',
        'admin.vis_apply_form_setting_emails.create' => 'Создание',
        'admin.vis_apply_form_setting_emails.update' => 'Редактирование',
        'admin.vis_apply_form_setting_emails.delete' => 'Удаление',
    ),
```

    
