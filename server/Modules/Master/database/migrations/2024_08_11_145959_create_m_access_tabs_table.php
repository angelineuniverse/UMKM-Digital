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
        Schema::create('m_access_tabs', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('title',30);
            $table->tinyInteger('active')->default(0)->comment('0 = not active, 1 = active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_access_tabs');
    }
};
