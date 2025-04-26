<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob');
            $table->string('profile_img')->nullable(); // to store the image file path
            $table->string('id_card_img')->nullable(); // to store the id card image file path
            $table->string('address');
            $table->string('parents_names');
            $table->string('parents_contact_numbers');
            $table->string('precertificate')->nullable(); // to store the file path of the pre-certificate
            $table->foreignId('grade_id')->nullable()->constrained('grades')->onDelete('set null');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->enum('status', ['pending', 'submitted', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
