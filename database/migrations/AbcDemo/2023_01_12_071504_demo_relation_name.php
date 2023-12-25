<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const TABLE_NAME = 'demo_relation_name';

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
            $table->bigInteger('relation_code')->unsigned()->default(0)->unique()->comment('唯一编码');
            $table->bigInteger('business_a_uniq_code')->unsigned()->default(0)->comment('business_uniq_code');
            $table->bigInteger('business_b_uniq_code')->unsigned()->default(0)->comment('business_uniq_code');
            $table->tinyInteger('is_main_relation', false, true)->comment('是否是主关系');
            $table->string('remark', 255)->comment('备注');
            $table->bigInteger('created_by_uniq_code')->unsigned()->default(0)->comment('创建人编码');
            $table->bigInteger('updated_by_uniq_code')->unsigned()->default(0)->comment('修改人编码');

            $table->timestamp('created_at')->useCurrent()->comment('创建时间');
            $table->timestamp('updated_at')->default(\App\Enums\DateTimeEnums::DEFAULT_DATETIME)->useCurrentOnUpdate()->comment('更新时间');

            $table->index(['business_a_uniq_code', 'business_b_uniq_code'], 'idx_business_a_business_b_relation');
            $table->index(['created_by_uniq_code'], 'idx_created_by_uniq_code');
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
