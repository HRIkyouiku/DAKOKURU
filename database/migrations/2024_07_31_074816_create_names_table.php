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
        Schema::create('names', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->index();
            $table->string('fn_jp');
            $table->string('fn_jp_hira');
            $table->string('fn_jp_kata');
            $table->string('fn_en');
            $table->string('ln_jp');
            $table->string('ln_jp_hira');
            $table->string('ln_jp_kata');
            $table->string('ln_en');
            $table->string('oln_jp')->nullable();
            $table->string('oln_jp_hira')->nullable();
            $table->string('oln_jp_kata')->nullable();
            $table->string('oln_en')->nullable();
            $table->string('mn_jp')->nullable();
            $table->string('mn_jp_hira')->nullable();
            $table->string('mn_jp_kata')->nullable();
            $table->string('mn_en')->nullable();
            $table->boolean('english_notation');
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
            $table->datetime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('names');
    }
};
