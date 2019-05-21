<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActorMovieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actor_movie', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('actor_id')->unsigned()->nullable();
      
            $table->bigInteger('movie_id')->unsigned()->nullable();
    
            $table->timestamps();        
        });

        Schema::table('actor_movie', function($table) {
            $table->foreign('actor_id')->references('id')
                  ->on('actors')->onDelete('cascade');
            $table->foreign('movie_id')->references('id')
                  ->on('movies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actor_movie');
    }
}
