<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id')->nullable();  
            $table->foreign('post_id')->references('id')->on('posts')->cascadeOnDelete(); //se cancelliamo i record a cascata cancella anche la riga della relazione

            $table->unsignedBigInteger('tag_id')->nullable();  
            $table->foreign('tag_id')->references('id')->on('tags')->cascadeOnDelete(); //se cancelliamo il tag cancelliamo la relazione con il post
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_tag');
    }
}
