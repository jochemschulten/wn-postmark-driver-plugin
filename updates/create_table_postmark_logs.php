<?php namespace SchultenMedia\Postmark\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Encryption\Encrypter;
use Backend\Models\User;
use Exception;
use System\Classes\PluginManager;

class CreateTablePostmarkLogs extends Migration
{

    public function up()
    {
        Schema::create('sm_postmark_logs', function ($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('type')->nullable()->index();
            $table->integer('server_id');
            $table->string('message_stream')->nullable()->index();
            $table->string('message_id')->nullable()->index();
            $table->string('recipient')->nullable()->index();
            $table->string('details')->nullable()->index();
            $table->text('metadata')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sm_postmark_logs');

    }

}
