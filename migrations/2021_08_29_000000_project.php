<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class Project extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 20)->default('')->comment('项目名称');
            $table->string('description', 200)->default('')->comment('项目简介');
            $table->string('path', 200)->default('')->comment('项目域名地址');
            $table->string('remark', 40)->default('')->comment('备注');
            $table->unsignedTinyInteger('status')->default(80)->comment('状态 40冻结 44到期');
            $table->timestamp('expire_at')->nullable()->comment('到期时间');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('项目表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project');
    }
}
