<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class Role extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->default(0)->comment('项目ID');
            $table->string('name')->default('')->comment('角色名称');
            $table->string('code')->unique()->comment('角色标识');
            $table->string('description', 200)->default('')->comment('角色描述');
            $table->string('guard_name')->default('web')->comment('守卫类型');
            $table->unsignedTinyInteger('status')->default(80)->comment('状态 40冻结');
            $table->timestamps();
            $table->comment('角色表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role');
    }
}
