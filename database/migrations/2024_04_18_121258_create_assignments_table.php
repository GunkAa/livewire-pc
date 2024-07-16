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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('pc_id');
            $table->enum('day_of_week', ['Monday Morning', 'Monday Afternoon', 'Tuesday Morning', 'Tuesday Afternoon', 'Wednesday Morning', 'Wednesday Afternoon', 'Thursday Morning', 'Thursday Afternoon', 'Friday Morning', 'Friday Afternoon']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pc_id')->references('id')->on('pcs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
