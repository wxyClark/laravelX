<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const TABLE_NAME = 'demo_rule_name_log';

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
            $table->bigInteger('rule_name_code')->unsigned()->default(0)->unique()->comment('业务情唯一编码');
            $table->tinyInteger('action_type')->unsigned()->default(0)->comment('操作类型(1：A; 2:B)');
            $table->string('remark')->default('')->comment('操作备注');

            //  只记录有变更的数据，每条记录记录整个规则+详情的变更。不做展示，只用于查问题
            $table->json('before_change')->comment('变更前数据');
            $table->json('after_change')->comment('变更后数据');

            $table->bigInteger('created_by_uniq_code')->unsigned()->default(0)->comment('创建人编码');
            $table->timestamp('created_at')->useCurrent()->comment('创建时间');
            $table->timestamp('updated_at')->default(\App\Enums\DateTimeEnums::DEFAULT_DATETIME)->useCurrentOnUpdate()->comment('更新时间');

            $table->unique(['tenant_id', 'rule_name_code'], 'uk_tenant_id_rule_name_code');
            $table->index(['action_type'], 'idx_action_type');
            $table->index(['created_by_uniq_code'], 'idx_created_by_uniq_code');
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
