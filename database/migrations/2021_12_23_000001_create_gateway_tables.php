<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\App;

class CreateGatewayTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (App::runningUnitTests()) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code');
                $table->string('webpage_url');
                $table->string('portal_url');
                $table->string('address');
                $table->string('email');
                $table->string('phone');
                $table->boolean('is_disabled')->default(0);
                $table->timestamps();
            });

            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name', 512);
                $table->string('email_h', 128)->unique();
                $table->string('email', 512);
                $table->string('role', 512);
                $table->boolean('is_new')->default(1);
                $table->boolean('is_disabled')->default(0);
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('access_token')->nullable();
                $table->string('password_reset_token')->nullable();
                $table->dateTime('password_reset_date')->nullable();
                $table->unsignedBigInteger('company_id')->nullable();
                $table->rememberToken();
                $table->timestamps();
    
                $table->foreign('company_id')->references('id')->on('companies');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
        Schema::dropIfExists('users');
    }
}
