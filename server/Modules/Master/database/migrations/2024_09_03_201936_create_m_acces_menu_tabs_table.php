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
        Schema::create('m_acces_menu_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('m_menu_tabs_id');
            $table->unsignedInteger('m_access_tabs_id');
            $table->foreign('m_menu_tabs_id')->on('m_menu_tabs')->references('id')->cascadeOnDelete();
            $table->foreign('m_access_tabs_id')->on('m_access_tabs')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_acces_menu_tabs');
    }
};
