<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('user_role');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('logo')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->boolean('sponsor')->default(false);
            $table->string('invoice');
            $table->string('password');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE companies AUTO_INCREMENT = 1000001');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
