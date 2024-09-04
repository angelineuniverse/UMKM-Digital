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
        Schema::create('m_menu_tabs', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name');
            $table->tinyInteger('isactive')->default(0)->comment("0 = not active, 1 = active");
            $table->string('url');
            $table->string('icon')->nullable();
            $table->mediumInteger('parent_id')->default(0)->comment("0 = parent menu");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_menu_tabs');
    }
};
