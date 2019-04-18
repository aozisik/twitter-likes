<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetsTable extends Migration
{
    public function up()
    {
        Schema::create('targets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('screen_name')->unique();
            $table->string('avatar_url');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('targets');
    }
}
