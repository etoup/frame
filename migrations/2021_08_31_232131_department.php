<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class Department extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('department', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父ID');
            $table->unsignedBigInteger('project_id')->default(0)->comment('项目ID');
            $table->string('name')->unique()->comment('部门名称');
            $table->string('description', 200)->default('')->comment('部门描述');
            $table->string('contact', 20)->default('')->comment('联系电话');
            $table->smallInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('level')->default(1)->comment('等级');
            $table->unsignedTinyInteger('status')->default(80)->comment('状态 40冻结');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('部门表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department');
    }
}
