<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

public function up()
{
    Schema::create('academic_years', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->date('start_date');
        $table->date('end_date');
        $table->date('application_opening')->nullable();
        $table->date('application_expiry')->nullable();
        $table->enum('status', ['pending', 'opened', 'completed']);
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('academic_years');
    }
};
