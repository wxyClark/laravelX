<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 创建文件可指定目录(相对于应用程序的基本路径)，php artisan make:migration create_columns_table --path=database/migrations/Demo
     * 执行文件可指定目录(相对于应用程序的基本路径)，php artisan migrate --path=database/migrations/Demo
     *
     * @return void
     */
    public function up()
    {
        Schema::create('columns', function (Blueprint $table) {
            $table->id();
            $table->string('data_type', 20)->comment('字段类型');
            $table->tinyInteger('is_signed')->comment('有无符号。1：有符号;2:无符号;3:不存在符号区分');
            $table->bigInteger('memory_size')->comment('占用内存');
            $table->string('min_value', 100)->comment('最小值');
            $table->string('max_value', 100)->comment('最大值');
            $table->json('feature')->comment('特性');
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
        Schema::dropIfExists('columns');
    }
};
