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
        Schema::create('m_user_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('m_access_tabs_id')->nullable();
            $table->string('email', 50);
            $table->string('password');
            $table->tinyInteger('isactive')->default(0)->comment('0 = not active, 1 = active');
            $table->foreign('m_access_tabs_id')->references('id')->on('m_access_tabs')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_user_tabs');
    }
};
