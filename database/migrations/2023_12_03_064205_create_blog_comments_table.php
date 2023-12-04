<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('blog_post_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
    
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('blog_post_id')->references('id')->on('blog_posts');
            $table->foreign('parent_id')->references('id')->on('blog_comments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_comments');
    }
};
