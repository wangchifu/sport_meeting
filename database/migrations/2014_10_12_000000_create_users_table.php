<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('uid')->nullable();//gsuite帶出uid
            $table->string('edu_key')->nullable();//gsuite及cloudschool帶出edu_key
            $table->string('name');//姓名
            $table->string('email')->nullable();//email
            $table->string('kind')->nullable();//gsuite帶出教職員、學生
            $table->string('title')->nullable();//gsuite帶出職稱 即 cloudschool帶出的title_name
            $table->string('code')->nullable();//gsuite帶出學校代碼 即 cloudschool 帶出的 school_no
            $table->string('school')->nullable();//gsuite帶出學校名稱
            $table->string('schools')->nullable();//gsuite帶出學校群組
            $table->string('username')->nullable();//gsuite帳號
            $table->string('password')->nullable();
            $table->string('cloudschool_username')->nullable();//cloudschool帳號
            $table->string('role')->nullable();//cloudschool 帶出 role
            $table->string('title_kind')->nullable();//cloudschool 帶出 title_kind
            $table->string('group')->nullable();//cloudschool 帶出 group
            $table->tinyInteger('admin')->nullable();//停用
            $table->tinyInteger('disable')->nullable();//停用
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
