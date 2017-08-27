# Articles
Пакет для Laravel 5.4+ предназначенный работы с формами. 
Поддерживает интеграцию с Google reCaptcha.

Разделы
1. [Установка](#Установка)
2. [Настройка](#Настройка)

## Установка
Выполняем
```json
    composer require "vis/apply_form_l5":"1.*"
```

Добавляем ApplyFormsServiceProvider в массив ServiceProviders в config/app.php
```php
   Vis\ApplyForm\ApplyFormsServiceProvider::class,
```

Публикуем config и js
```php
    php artisan vendor:publish --provider="Vis\ApplyForm\ApplyFormsServiceProvider" --force
```

## Настройка

