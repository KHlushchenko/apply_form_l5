<?php
return array(
    'db' => array(
        'table' => 'vis_apply_form_setting_messages',
        'order' => array(
            'created_at' => 'desc',
        ),
        'pagination' => array(
            'per_page' => 50,
            'uri' => '/admin/apply_form/vis_apply_form_setting_messages',
        ),
    ),
    'cache' => array(
        'tags' => array('vis_apply_form_setting_messages'),
    ),
    'options' => array(
        'caption' => 'Сообщения ответы',
        'ident' => 'vis_apply_form_setting_messages',
        'form_ident' => 'vis_apply_form_setting_messages-form',
        'form_width' => '920px',
        'table_ident' => 'vis_apply_form_setting_messages-table',
        'action_url' => '/admin/handle/vis_apply_form_setting_messages',
        'not_found' => 'пусто',
        'model'     => 'Vis\ApplyForm\Models\ApplyFormSettingMessage',
        'handler'   => 'Vis\Builder\Helpers\SlugHandler'
    ),
    'fields' => array(
        'id' => array(
            'caption' => '#',
            'type' => 'readonly',
            'class' => 'col-id',
            'width' => '1%',
            'hide' => true,
            'is_sorting' => false
        ),
        'slug' => array(
            'caption' => 'Слаг',
            'type' => 'readonly',
            'filter' => 'text',
            'field' => 'string',
            'width' => "25%",
        ),
        'title' => array(
            'caption' => 'Название',
            'filter' => 'text',
            'type' => 'text',
            'is_sorting' => true,
            'field' => 'string',
            'width' => "25%",
            "readonly_for_edit" => true,
        ),
        'message_title' => array(
            'caption' => 'Заглавие',
            'filter' => 'text',
            'type' => 'text',
            'is_sorting' => true,
            'field' => 'string',
            'width' => "25%",
            'tabs' => config('translations.config.languages'),
        ),
        'message_description' => array(
            'caption' => 'Описание',
            'filter' => 'text',
            'type' => 'text',
            'is_sorting' => true,
            'field' => 'string',
            'width' => "25%",
            'tabs' => config('translations.config.languages'),
        ),
        'created_at' => array(
            'caption' => 'Дата создания',
            'type' => 'readonly',
            'is_sorting' => true,
            'hide_list' => true,
            'hide'        => true,
            'field' => 'timestamp',
        ),
        'updated_at' => array(
            'caption' => 'Дата обновления',
            'type' => 'readonly',
            'hide_list' => true,
            'is_sorting' => true,
            'hide'        => true,
            'field' => 'timestamp',
        ),
    ),
    'filters' => function(&$db) {
    },
    'actions' => array(
        'insert' => array(
            'caption' => 'Добавить',
            'check' => function() {
                return Sentinel::hasAccess('admin.vis_apply_form_setting_messages.create');
            }
        ),
        'clone' => array(
            'caption' => 'Клонировать',
            'check' => function() {
                return Sentinel::hasAccess('admin.vis_apply_form_setting_messages.create');
            }
        ),
        'update' => array(
            'caption' => 'Редактировать',
            'check' => function() {
                return Sentinel::hasAccess('admin.vis_apply_form_setting_messages.update');
            }
        ),
        'delete' => array(
            'caption' => 'Удалить',
            'check' => function() {
                return Sentinel::hasAccess('admin.vis_apply_form_setting_messages.delete');
            }
        ),
    ),
);
