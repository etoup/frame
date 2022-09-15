<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class Rule extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(config('casbin.adapter.constructor.tableName'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('ptype')->nullable();
            $table->string('v0')->nullable();
            $table->string('v1')->nullable();
            $table->string('v2')->nullable();
            $table->string('v3')->nullable();
            $table->string('v4')->nullable();
            $table->string('v5')->nullable();
            $table->comment('适配器表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('casbin.adapter.constructor.table_name'));
    }
}
