<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('target')->index();
            $table->string('twitter_id')->index();

            $table->string('screen_name')->nullable();
            $table->string('avatar_url')->nullable();
            $table->boolean('interested')->nullable()->default(null);
            $table->string('not_interested_reason')->nullable();

            // Did we place a like on their tweets..
            $table->timestamp('engaged_at')->nullable();
            // Did they follow us back as a result?
            $table->timestamp('converted_at')->nullable();

            $table->timestamp('back_off_until')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('followers');
    }
}
