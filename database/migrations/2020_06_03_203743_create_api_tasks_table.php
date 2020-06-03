<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('command', 250);
            $table->text('options');
            $table->text('arguments');
            $table->enum('status', ['idle', 'running'])->default('running');
            $table->timestamp('last_run');
            $table->text('last_error')->nullable();
            $table->bigInteger('last_run_time')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_tasks');
    }
}
