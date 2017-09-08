<?php
return array(
    'db' => array(
        'table' => 'vis_apply_form_setting_emails',
        'order' => array(
            'id' => 'desc',
        ),
        'pagination' => array(
            'per_page' => 50,
            'uri' => '/admin/apply_form/vis_apply_form_setting_emails',
        ),
    ),
    'cache' => array(
        'tags' => array('vis_apply_form_setting_emails'),
    ),
    'options' => array(
        'caption' => 'Список E-mail адресов',
        'ident' => 'vis_apply_form_setting_emails',
        'form_ident' => 'vis_apply_form_setting_emails-form',
        'form_width' => '920px',
        'table_ident' => 'vis_apply_form_setting_emails-table',
        'action_url' => '/admin/handle/vis_apply_form_setting_emails',
        'not_found' => 'пусто',
        'model'     => 'Vis\ApplyForm\Models\ApplyFormSettingEmail',
        'handler'   => 'Vis\Builder\Helpers\SlugHandler'
    ),
    'fields' => array(
        'id' => array(
            'caption' => '#',
            'type' => 'readonly',
            'class' => 'col-id',
            'width' => '1%',
            'hide' => true,
            'is_sorting' => true
        ),
        'slug' => array(
            'caption' => 'Слаг',
            'type' => 'readonly',
            'filter' => 'text',
            'field' => 'string',
            'width' => "25%",
            'is_sorting' => true
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
        'emails' => array(
            'caption' => 'Список E-mail',
            'filter' => 'text',
            'type' => 'text',
            'is_sorting' => true,
            'field' => 'string',
            'width' => "50%",
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
                return Sentinel::hasAccess('admin.vis_apply_form_setting_emails.create');
            }
        ),
        'clone' => array(
            'caption' => 'Клонировать',
            'check' => function() {
                return Sentinel::hasAccess('admin.vis_apply_form_setting_emails.create');
            }
        ),
        'update' => array(
            'caption' => 'Редактировать',
            'check' => function() {
                return Sentinel::hasAccess('admin.vis_apply_form_setting_emails.update');
            }
        ),
        'delete' => array(
            'caption' => 'Удалить',
            'check' => function() {
                return Sentinel::hasAccess('admin.vis_apply_form_setting_emails.delete');
            }
        ),
    ),
);
