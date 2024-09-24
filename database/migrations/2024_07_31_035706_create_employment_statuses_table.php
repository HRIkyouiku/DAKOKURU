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
        Schema::create('employment_statuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->index();
            $table->date('contract_start_date')->index();
            $table->date('contract_end_date')->nullable();
            $table->time('business_start_time');
            $table->time('business_end_time');
            $table->time('coretime_start_time')->nullable();
            $table->time('coretime_end_time')->nullable();
            $table->time('night_shift_start_time')->nullable();
            $table->time('night_shift_end_time')->nullable();
            $table->tinyInteger('break_minute')->nullable();
            $table->tinyInteger('holiday_type');
            $table->json('legal_holiday');
            $table->json('non_legal_holiday');
            $table->tinyInteger('work_deadline_date');
            $table->tinyInteger('initial_calculation_day_of_week');
            $table->tinyInteger('initial_calculation_month');
            $table->tinyInteger('initial_calculation_day');
            $table->tinyInteger('overtime_calculation_day');
            $table->tinyInteger('overtime_calculation_month');
            $table->tinyInteger('counted_unfilled_overtime');
            $table->tinyInteger('flex_type');
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
            $table->datetime('deleted_at')->nullable();

            $table->unique(['user_id', 'contract_start_date']); // Composite unique key
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employment_statuses');
    }
};
