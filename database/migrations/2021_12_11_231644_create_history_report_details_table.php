<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryReportDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_report_details', function (Blueprint $table) {
            // $table->id();
            $table->string('branch');
            $table->integer('trans');
            $table->double('amount', 10, 2);
            $table->string('phone');
            $table->datetime('startDate');
            $table->datetime('lastDate');
            $table->string('customerStatus');
            $table->integer('numberOfDays');
            $table->integer('lastDayOfUse');
            $table->integer('numberOfMonth');
            $table->string('useInMonth');
            $table->string('ActiveStatus');
            $table->string('dataOfYear');
            $table->string('dataOfMonth');
            // $table->string('summaryDate');
            
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
        Schema::dropIfExists('history_report_details');
    }
}
