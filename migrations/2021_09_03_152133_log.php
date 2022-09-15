<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class Log extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->default(0)->comment('项目ID');
            $table->unsignedBigInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedBigInteger('department_id')->default(0)->comment('部门ID');
            $table->json('role')->nullable()->comment('角色');
            $table->string('host', 20)->default('')->comment('Host');
            $table->string('title', 40)->default('')->comment('标题');
            $table->text('remark')->comment('描述');
            $table->unsignedTinyInteger('type')->default(10)->comment('类型 10查询 20添加 30修改 40删除 50导入 60导出');
            $table->unsignedTinyInteger('status')->default(80)->comment('状态 40冻结');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('操作日志表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log');
    }
}
