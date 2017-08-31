# Apply Form
Пакет Laravel 5 предназначенный для работы с формами. 
Требует и автоматически устанавливает пакет https://github.com/arturishe21/mail_templates_l5 для работы с отправкой писем.
Поддерживает интеграцию с Google Invisible reCaptcha.

Разделы
1. [Установка](#Установка)
2. [VIS-CMS](#VIS-CMS)
3. [Настройка](#Настройка)
4. [Пример использования](#Пример-использования)
5. [Описание классов](#Описание-классов)

## Установка
Выполняем
```json
    composer require "vis/apply_form_l5":"1.*"
```

Добавляем ApplyFormServiceProvider в массив ServiceProviders в config/app.php
```php
   Vis\ApplyForm\ApplyFormServiceProvider::class,
```

Выполняем миграцию таблиц
```php
   php artisan migrate --path=vendor/vis/apply_form_l5/src/Migrations
```

Публикуем config, js, nodes
```php
    php artisan vendor:publish --provider="Vis\ApplyForm\ApplyFormserviceProvider" --force
```

## Настройка
В файле config/apply_form/apply_form.php </br>

Включаем капчу и добавляем публичный и скрытый ключи
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

В файле public/js/apply_form_rules.js переопределяем методы и указываем в них свои действия(например, вызов попапа с кастомным сообщением) по выполнению ajax запроса
```js
ApplyForm.successCallback = function (message) {
};

ApplyForm.failCallback = function (message) {
};
```

В этом же файле можно дописать дополнительные правила проверки полей, например добавить маску для телефонов. </br>
Для этого в класс ApplyFormRules нужно добавить свой метод, например:
```js
        initPhoneMask: function () {
            $('input[name=phone]').mask('+38 (000) 000-00-00', {clearIfNotMatch: true});
        },
``` 
И зарегистрировать его инициализацую в методе ApplyFormRules.init()
```js
        init: function () {
            ApplyFormRules.initPhoneMask();
        },
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

## Пример использования
1. Определяем класс, который рассширяет класс Vis\ApplyForm\Models\AbstractApplyForm

```php
namespace App\Models\ApplyForm;

use Vis\ApplyForm\Models\AbstractApplyForm;

class ApplyFormAnonymousMessage extends AbstractApplyForm
{
	protected $table = 'apply_form_anonymous_messages';

	protected $validationRules = [
        'message' => 'required|min:10|max:2000',
        'file'    => 'required|max:3072|mimes:pdf,doc,docx'
    ];
    
    protected $fileFieldName     = 'file';
    protected $fileStorageFolder = 'storage/apply_form_files/apply_form_anonymous_messages/';

    protected $mailTemplate    = 'shablon-zajavka-anonimnoe-obrashenie';
    protected $mailAddressSlug = 'email-zajavka-anonimnoe-obrashenie';

    protected $messageSlug     = 'soobshchenie-zajavka-anonimnoe-obrashenie';

    protected function prepareInputData(array $inputData): array
    {
        $this->inputCleaner()->setArray($inputData);

        $preparedData = [
            'message' => $this->inputCleaner()->getCleanStringFromArray('message'),
            'file'    => $this->inputCleaner()->getStringFromArray('file'),
        ];

        return $preparedData;
    }
    
    protected function prepareMailData(array $preparedData): array
    {
        $preparedData['file_url'] = asset($preparedData['file']);

        return $preparedData;
    }
        
    protected function customCallback(array $attributes)
    {
        //print_arr($attributes);
    } 
}
```

2. Добавляем его в массив форм apply_forms в config/apply_form/apply_form.php 

```php
    'apply_forms' => [
        'anonymous_message' => App\Models\ApplyForm\ApplyFormAnonymousMessage::class,
    ],
```

3. Создаем форму в шаблонах с названием 'название_формы_form'
```html
<form id="anonymous_message_form">
    <div class="form-field">
        <textarea name='message' placeholder="сообщение" ></textarea>
    </div>
    <div class="form-field">
        <input type="file" class="file-input" placeholder="file" id="file" name="file"
           accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/pdf">
    </div>
    <div class="form-button">
        <button type="submit" class="btn">отправить</button>
    </div>
</form>
```

4. Дописываем в файл public/js/apply_form_rules.js правила jquery validation </br>
Правила определяются как 'название_формы_rules' и 'название_формы_messages' 
```js
    ApplyForm.anonymous_message_rules = {
        'message': {required: true, rangelength: [10, 2000]},
        'file'   : {required: true},
    };
    
    ApplyForm.anonymous_message_messages = {
        'message': {required: '', rangelength: ''},
        'file'   : {required: ''},
    };
```


## Описание классов
1. Класс расширяемый классом Vis\ApplyForm\Models\AbstractApplyForm </br>
```php
namespace App\Models\ApplyForm;

use Vis\ApplyForm\Models\AbstractApplyForm;

class ApplyFormAnonymousMessage extends AbstractApplyForm
{

}
```

**Описание обязательных свойств:**

Имя используемой таблицы </br> 
Значение: строка'
```php
	protected $table = '';
```

**Описание дополнительных свойств:**

Правила валидации </br> 
Значение: массив
```php
	protected $validationRules = [
    ];
```

Название поля с файлом </br> 
Используется, если форма передает файл</br> 
Значение: строка
```php
    protected $fileFieldName = '';
```

Путь к папке, в которой будет хранится файл относительно публичной директории </br> 
Используется, если форма передает файл</br> 
Значение: строка
```php
    protected $fileStorageFolder = '';
```

Путь к папке, в которой будет хранится файл относительно публичной директории </br> 
Используется, если форма передает файл</br> 
Значение: строка
```php
    protected $fileStorageFolder = '';
```

Название шаблона письма </br> 
Используется, если после сохранения нужно отправить письмо на почту</br> 
Значение: строка с slug шаблона класса Vis\MailTemplates\MailT
```php
    protected $mailTemplate    = '';
```

Список имейлов для отправки письма </br> 
Используется, если после сохранения нужно отправить письмо на почту</br> 
Значение: строка с slug записи класса Vis\ApplyForm\Models\ApplyFormSettingEmail
```php
    protected $mailAddressSlug = '';
```

Возвращаемое сообщение после удачного сохранения заявки </br> 
Используется, если после сохранения нужно отправить письмо на почту</br> 
Значение: строка с slug записи класса Vis\ApplyForm\Models\ApplyFormSettingMessage
```php
    protected $messageSlug = '';
```

**Описание обязательных методов:**

Метод подготовки исходных данных</br>
Рекомендуется использовать класс-помошник Vis\ApplyForm\Helpers\InputCleaner для очистки данных
Значение: массив</br>
Возвращаемое значение: массив
```php
    protected function prepareInputData(array $inputData): array
```

**Описание дополнительных свойств:**

Метод преобразования подготовленных данных в данные для отправки на почту</br>
Значение: массив</br>
Возвращаемое значение: массив
```php
    protected function prepareMailData(array $preparedData): array
```

Метод вызова дополнительного функционала вызываемый после сохранения заявки, например передача данных в API</br>
Значение: массив</br>
```php
    protected function customCallback($attributes)
 ```
 
2. Класс Vis\ApplyForm\Helpers\InputCleaner

**Описание методов:**

Метод установки массива исходных данных</br>
Значение: массив</br>
```php
    public function setArray(array $array)
```

Метод получения массива данных</br>
Значение: массив</br>
```php
    public function getArray(): array
```

Метод получения значения по названию поля из массива данных</br>
Значение: строка</br>
Возвращаемое значение: значение из массива или null
```php
    public function getFromArray(string $field)
```

Метод получения целочисленного значения по названию поля из массива данных</br>
Значение: строка</br>
Возвращаемое значение: целое число
```php
    public function getIntFromArray(string $field): int
```

Метод получения дробногочисленного значения по названию поля из массива данных</br>
Значение: строка</br>
Возвращаемое значение: дробное число
```php
    public function getFloatFromArray(string $field): float
```

Метод получения строчного значения по названию поля из массива данных</br>
Значение: строка</br>
Возвращаемое значение: дробное число
```php
    public function getStringFromArray(string $field): string
```

Метод получения очищенного строчного значения по названию поля из массива данных</br>
Значение: строка</br>
Возвращаемое значение: дробное число
```php
    public function getCleanStringFromArray(string $field): string
```
