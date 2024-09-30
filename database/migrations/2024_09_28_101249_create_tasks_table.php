<?php

use App\Constant\StatusConstant;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('project_id');  // Make sure this is unsigned and matches the projects table
            $table->text('description');
            $table->string('status')->default(StatusConstant::PENDING);
            $table->timestamps();
            $table->softDeletes();
            // Adding the foreign key constraint
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('cascade'); // Optional: add cascade delete
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
