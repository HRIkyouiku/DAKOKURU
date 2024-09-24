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
        Schema::create('group_authorities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('group_id')->index();
            $table->bigInteger('target_group_id')->nullable();
            $table->json('authorities');
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
            $table->datetime('deleted_at')->nullable();

            $table->unique(['group_id', 'target_group_id']); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_authorities');
    }
};
