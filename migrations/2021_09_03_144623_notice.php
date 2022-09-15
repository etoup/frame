<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class Notice extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->default(0)->comment('项目ID');
            $table->string('title', 80)->default('')->comment('标题');
            $table->text('content')->comment('内容');
            $table->string('remark', 100)->default('')->comment('备注');
            $table->json('files')->nullable()->comment('图片文件');
            $table->unsignedTinyInteger('type')->default(10)->comment('类型 10项目全员 20部门 30人员');
            $table->unsignedTinyInteger('status')->default(10)->comment('状态 10待发布 40已冻结 80已发布');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('公告表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notice');
    }
}
