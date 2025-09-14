<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiPlatformPromptPivotTable extends Migration
{
    public function up()
    {
        Schema::create('ai_platform_prompt', function (Blueprint $table) {
            $table->unsignedBigInteger('prompt_id');
            $table->foreign('prompt_id', 'prompt_id_fk_10715181')->references('id')->on('prompts')->onDelete('cascade');
            $table->unsignedBigInteger('ai_platform_id');
            $table->foreign('ai_platform_id', 'ai_platform_id_fk_10715181')->references('id')->on('ai_platforms')->onDelete('cascade');
        });
    }
}
