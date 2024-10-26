<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->enum('name', allowed: ['admin', 'supervisor', 'casher'])->unique();
            $table->softDeletes();
            $table->timestamps();
        });
        DB::insert('insert into roles (name, created_at, updated_at) values (?, ?, ?)', ['admin', now(), now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
