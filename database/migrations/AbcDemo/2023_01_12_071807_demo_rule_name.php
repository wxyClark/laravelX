<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const TABLE_NAME = 'demo_rule_name';

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
            $table->bigInteger('rule_name_code')->unsigned()->default(0)->unique()->comment('规则唯一编码');
            $table->string('rule_name', 255)->default('')->comment('业务名称');
            $table->tinyInteger('type')->unsigned()->default(0)->comment('类型(1：A; 2:B)');
            $table->smallInteger('status')->unsigned()->default(0)->comment('状态(1：A1; 2:B1)');
            $table->mediumInteger('level')->unsigned()->default(0)->comment('优先级');
            $table->json('actions')->comment('执行动作');

            $table->bigInteger('created_by_uniq_code')->unsigned()->default(0)->comment('创建人编码');
            $table->bigInteger('updated_by_uniq_code')->unsigned()->default(0)->comment('修改人编码');
            $table->timestamp('created_at')->useCurrent()->comment('创建时间');
            $table->timestamp('updated_at')->default(\App\Enums\DateTimeEnums::DEFAULT_DATETIME)->useCurrentOnUpdate()->comment('更新时间');

            $table->unique(['tenant_id', 'rule_name'], 'uk_tenant_id_rule_name');
            $table->index(['status'], 'idx_status');
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
