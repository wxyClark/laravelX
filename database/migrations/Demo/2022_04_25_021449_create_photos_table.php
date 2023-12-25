<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('url')->comment('url');
            $table->string('size')->comment('大小');
            $table->tinyInteger('type')->comment('类型');
            $table->timestamps();   // 执行 migrate 将为 photos 表生成 created_at 和 updated_at 字段，类型为 timestamp，默认值为 NULL
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photos');
    }
};
