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
        Schema::create('sql_profilers', function (Blueprint $table) {
            $table->id();
            $table->string('env', 20)->nullable(true)->comment('Environment name');
            $table->decimal('duration', 4, 2)->comment('Request duration');
            $table->text('sql')->nullable(true)->comment('Request');
            $table->string('file_name')->nullable(true)->comment('Request source file name');
            $table->string('line')->nullable(true)->comment('Request source line');
            $table->string('file_path')->nullable(true)->comment('Request source file path');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sql_profilers');
    }
};
