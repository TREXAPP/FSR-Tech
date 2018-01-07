<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path_to_file', 255)->nullable();
            $table->string('filename', 255)->nullable();
            $table->string('original_name', 255)->nullable();
            $table->string('extension', 20)->nullable();
            $table->integer('size')->nullable();
            $table->integer('last_modified')->nullable();
            $table->string('purpose')->nullable();
            $table->string('for_user_type')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
