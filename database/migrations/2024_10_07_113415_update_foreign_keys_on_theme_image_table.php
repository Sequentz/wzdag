<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */ public function up()
    {
        Schema::table('theme_image', function (Blueprint $table) {
            $table->dropForeign(['theme_id']);
            $table->dropForeign(['image_id']);

            // Voeg cascade onDelete toe
            $table->foreign('theme_id')->references('id')->on('themes')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('theme_image', function (Blueprint $table) {
            $table->dropForeign(['theme_id']);
            $table->dropForeign(['image_id']);

            // Voeg de foreign keys zonder cascade terug
            $table->foreign('theme_id')->references('id')->on('themes');
            $table->foreign('image_id')->references('id')->on('images');
        });
    }
};
