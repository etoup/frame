<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Model\SoftDeletes;

class User extends Migration
{
    use SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->default(0)->comment('项目ID');
            $table->unsignedBigInteger('department_id')->default(0)->comment('部门ID');
            $table->json('role')->nullable()->comment('角色');
            $table->string('username', 40)->unique()->nullable()->comment('用户名');
            $table->string('password', 80)->default('')->comment('密码');
            $table->char('mobile', 11)->unique()->nullable()->comment('手机号码');
            $table->string('open_id', 80)->unique()->nullable()->comment('openId');
            $table->string('union_id', 80)->unique()->nullable()->comment('unionId');
            $table->string('remember_token', 80)->default('')->comment('令牌');
            $table->string('real_name', 40)->default('')->comment('真实姓名');
            $table->string('nick_name', 40)->default('')->comment('昵称');
            $table->string('avatar_url')->default('')->comment('头像');
            $table->string('sex', 10)->default('')->comment('性别');
            $table->date('birthday')->nullable()->comment('出生日期');
            $table->string('email', 40)->default('')->comment('邮箱');
            $table->string('telephone', 20)->default('')->comment('座机号');
            $table->string('remark', 80)->default('')->comment('备注');
            $table->unsignedTinyInteger('agreed')->default(80)->comment('是否同意协议');
            $table->unsignedTinyInteger('type')->default(10)->comment('类型');
            $table->unsignedTinyInteger('super')->default(10)->comment('角色 10用户');
            $table->unsignedTinyInteger('status')->default(80)->comment('状态 40冻结');
            $table->timestamp('type_updated_at')->nullable()->comment('类型更新时间');
            $table->timestamp('last_login_at')->nullable()->comment('最后登录时间');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('用户表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
}
