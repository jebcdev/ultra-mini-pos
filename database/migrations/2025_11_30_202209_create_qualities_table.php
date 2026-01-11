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
        Schema::create('qualities', function (Blueprint $table) {
          $table->id();


            $table->string('name')->unique()->index();
            $table->string('description')->nullable();
            $table->string('slug')->unique()->index();
            $table->string('image')->nullable()->default(asset('assets/img/qualities.png'));




            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualities');
    }
};
