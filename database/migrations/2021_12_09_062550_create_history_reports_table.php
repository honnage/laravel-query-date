<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_reports', function (Blueprint $table) {
            // $table->id();
            $table->string('phone');
            $table->string('branch');
            $table->datetime('startDate');
            $table->datetime('lastDate');
            $table->integer('countTrans');
            $table->integer('countDate');
            $table->integer('dataDiff');
            $table->string('statusUser');
            $table->string('statusActive');
            $table->string('usageMonth');
            $table->datetime('summaryDate');
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
        Schema::dropIfExists('history_reports');
    }
}
