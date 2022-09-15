<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class UserNotice extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_notice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->default(0)->comment('项目ID');
            $table->unsignedBigInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedBigInteger('notice_id')->default(0)->comment('公告ID');
            $table->unsignedTinyInteger('type')->default(10)->comment('类型 10普通 20置顶');
            $table->unsignedTinyInteger('status')->default(10)->comment('状态 10待阅 40撤回 80已阅');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('用户公告表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notice');
    }
}
