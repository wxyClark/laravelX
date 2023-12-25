<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const TABLE_NAME = 'demo_business_name';

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
            $table->bigInteger('business_name_code')->unsigned()->unique()->comment('业务唯一编码');
            $table->tinyInteger('type')->unsigned()->default(0)->comment('类型(1：A; 2:B)');
            $table->smallInteger('status')->unsigned()->default(0)->comment('状态(1：A1; 2:B1)');
            $table->mediumInteger('percent')->unsigned()->default(0)->comment('百分之(保留3位小数，取值时除以1000)');

            $table->string('business_name', 255)->default('')->comment('业务名称');
            $table->char('color', 6)->default('000000')->comment('颜色');
            $table->bigInteger('created_by_uniq_code')->default(0)->comment('创建人编码');
            $table->bigInteger('updated_by_uniq_code')->default(0)->comment('修改人编码');
            $table->timestamp('created_at')->useCurrent()->comment('创建时间');
            $table->timestamp('updated_at')->default(\App\Enums\DateTimeEnums::DEFAULT_DATETIME)->useCurrentOnUpdate()->comment('更新时间');

            $table->unique(['tenant_id', 'business_name'], 'uk_tenant_id_business_name');
            $table->index(['business_name_code'], 'idx_business_name_code');
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
