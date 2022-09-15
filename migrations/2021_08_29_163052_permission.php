<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class Permission extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permission', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->default(0)->comment('项目ID');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('PID');
            $table->string('title', 20)->unique()->comment('菜单名称');
            $table->string('name', 40)->default('')->comment('路由名称');
            $table->string('path', 40)->default('')->comment('路由地址');
            $table->string('component', 40)->default('')->comment('组件地址');
            $table->string('redirect', 40)->default('')->comment('重定向地址');
            $table->string('icon', 20)->default('')->comment('图标');
            $table->string('display_name', 40)->default('')->comment('名称');
            $table->string('url', 200)->default('')->comment('跳转地址');
            $table->string('guard_name')->default('web')->comment('守卫类型 如：web');
            $table->smallInteger('sort')->default(0)->comment('排序，正序排序');
            $table->unsignedTinyInteger('type')->default(10)->comment('类型 10菜单 20接口 30跳转');
            $table->unsignedTinyInteger('status')->default(80)->comment('状态 40冻结');
            $table->timestamps();
            $table->comment('资源权限表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission');
    }
}
