<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            //aggiundo colonna category_id nella tabella posts
            $table->unsignedBigInteger('category_id')->nullable()->after('id');  //dopo id----creato colonna
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null'); //se cancellata null---aggiunto vincolo
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign('posts_category_id_foreign'); // elimino vincolo
            $table->dropColumn('category_id');  // elimino colonna
        });
    }
}
