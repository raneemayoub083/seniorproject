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
        Schema::table('section_student', function (Blueprint $table) {
            $table->decimal('final_grade', 5, 2)->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('section_student', function (Blueprint $table) {
            $table->dropColumn('final_grade');
        });
    }
};
