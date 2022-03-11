<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 512);
            $table->string('email_h', 128)->unique();
            $table->string('email', 512);
            $table->string('role', 512);
            $table->tinyInteger('is_new')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        $this->insertInitialUsers();
    }

    private function insertInitialUsers() 
    {
        DB::table('users')->insert(
            array(
                'name' => encrypt('WMP Admin'),
                'email' => encrypt(config('custom.company_info.email')),
                'email_h' => hash('sha1', config('custom.company_info.email')),
                'role' => encrypt('admin'),
                'is_new' => 0,
                'password' => Hash::make('admin123'),
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
