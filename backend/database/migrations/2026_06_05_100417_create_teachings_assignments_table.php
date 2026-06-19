<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'teaching_assignments',
            function (Blueprint $table) {

                $table->id();

                $table->foreignId('teacher_id')
                    ->constrained('teachers')
                    ->cascadeOnDelete();

                $table->foreignId('subject_id')
                    ->constrained('subjects')
                    ->cascadeOnDelete();

                $table->foreignId('class_id')
                    ->constrained('classes')
                    ->cascadeOnDelete();

                $table->string('academic_year');

                $table->enum(
                    'semester',
                    [
                        'Ganjil',
                        'Genap',
                    ]
                );

                $table->timestamps();

                $table->unique(
                    [
                        'teacher_id',
                        'subject_id',
                        'class_id',
                        'academic_year',
                        'semester',
                    ],
                    'ta_unique'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'teaching_assignments'
        );
    }
};