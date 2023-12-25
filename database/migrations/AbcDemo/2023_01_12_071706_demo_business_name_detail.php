<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const TABLE_NAME = 'demo_business_name_detail';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id')->unsigned()->default(0)->comment('租户ID');
            $table->bigInteger('business_name_detail_code')->unsigned()->default(0)->unique()->comment('业务详情唯一编码');
            $table->bigInteger('business_name_code')->unsigned()->default(0)->comment('业务编码');
            $table->text('desc')->comment('详情介绍');
            $table->json('attributes')->comment('参数配置');
            $table->bigInteger('created_by_uniq_code')->unsigned()->default(0)->comment('创建人编码');
            $table->bigInteger('updated_by_uniq_code')->unsigned()->default(0)->comment('修改人编码');

            $table->timestamp('created_at')->useCurrent()->comment('创建时间');
            $table->timestamp('updated_at')->default(\App\Enums\DateTimeEnums::DEFAULT_DATETIME)->useCurrentOnUpdate()->comment('更新时间');

            $table->index(['tenant_id', 'business_name_code'], 'idx_tenant_id_business_name_code');
            $table->index(['updated_by_uniq_code'], 'idx_updated_by_uniq_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
