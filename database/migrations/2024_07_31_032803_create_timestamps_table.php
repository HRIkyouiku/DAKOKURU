<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timestamps', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->index();
            $table->date('date')->index();
            $table->time('time');
            $table->tinyInteger('type');
            $table->bigInteger('work_place_id');
            $table->string('remark')->nullable();
            $table->boolean('approved');
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
            $table->datetime('deleted_at')->nullable();

            $table->unique(['user_id', 'date', 'time', 'type']); // Composite unique key
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timestamps');
    }
};
