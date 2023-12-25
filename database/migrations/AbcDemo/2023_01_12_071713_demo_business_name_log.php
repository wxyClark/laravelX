<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const TABLE_NAME = 'demo_business_name_log';

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
            $table->bigInteger('business_name_code')->unsigned()->default(0)->comment('业务编码');
            $table->tinyInteger('action_type')->unsigned()->default(0)->comment('操作类型(1：新增; 2:编辑)');
            $table->string('remark', 255)->default('')->comment('操作备注');

            //  只记录有变更的数据，每个字段一条记录，便于前段展示，查询；详情作为一条记录 column_name = 'detail_list'
            $table->string('column_name')->comment('变更字段');
            $table->json('before_change')->comment('变更前的值[value => 1, transform => 状态1]');
            $table->json('after_change')->comment('变更前的值[value => 2, transform => 状态2]');

            $table->bigInteger('operator_uniq_code')->unsigned()->default(0)->comment('操作人编码');
            $table->timestamp('created_at')->useCurrent()->comment('创建时间');
            $table->timestamp('updated_at')->default(\App\Enums\DateTimeEnums::DEFAULT_DATETIME)->useCurrentOnUpdate()->comment('更新时间');

            $table->index(['tenant_id', 'business_name_code'], 'idx_tenant_id_business_name_code');
            $table->index(['action_type'], 'idx_action_type');
            $table->index(['operator_uniq_code'], 'idx_operator_uniq_code');
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
