<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_application_templates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type'); // pdf, docx, xlsx

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_application_templates');
    }
};
