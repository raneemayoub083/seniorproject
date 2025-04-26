<?php

// 2025_03_22_123456_add_user_id_to_teachers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToTeachersTable extends Migration
{
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');  // Assuming 'id' is the primary key of the teacher table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');  // Foreign key to users table
        });
    }

    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
