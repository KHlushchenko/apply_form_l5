<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ApplyFormMigration
 */
class ApplyFormMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vis_apply_form_setting_emails', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->string('emails', 2000);
            $table->timestamps();
        });

        Schema::create('vis_apply_form_setting_messages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->string('message_title', 255);
            $table->string('message_description', 255);
            $table->timestamps();
        });

        DB::table('vis_apply_form_setting_messages')->insert([
            [
                'title'               => 'Ошибка капчи',
                'slug'                => 'oshibka-kapchi',
                'message_title'       => 'Ошибка',
                'message_description' => 'Капча не прошла проверку',
            ],
            [
                'title'               => 'Ошибка валидации',
                'slug'                => 'oshibka-validacii',
                'message_title'       => 'Ошибка',
                'message_description' => 'Данные не прошли валидацию',
            ],
            [
                'title'               => 'Ошибка сохранения',
                'slug'                => 'oshibka-sohraneniya',
                'message_title'       => 'Ошибка',
                'message_description' => 'Не удалось сохранить данные',
            ],
            [
                'title'               => 'Успех сохранения',
                'slug'                => 'uspeh-sohraneniya',
                'message_title'       => 'Успех',
                'message_description' => 'Заявка успешно сохранена',
            ],

        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vis_apply_form_setting_emails');
        Schema::dropIfExists('vis_apply_form_setting_messages');
    }
}
