<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('title'); // Assuming this should be a string, not a date
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location');
            $table->unsignedBigInteger('speaker_user_id'); // Change to unsignedBigInteger
            $table->unsignedBigInteger('speaker_company_id');
            $table->integer('max_registrations');
            $table->integer('available_seats');
            $table->text('description');
            $table->timestamps();

            $table->foreign('speaker_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('speaker_company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
