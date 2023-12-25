<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetParams extends Command
{
    /**
     * php artisan make:command GetParams 创建脚本文件
     * 创建脚本文件后必须指定 signature —— 命令运行方式
     *
     * @var string
     */
    protected $signature = 'GetParams {--id=} {--name=}';

    /**
     * 创建脚本文件后应修改 description, 用于 php artisan  展示命令用途
     *
     * @var string
     */
    protected $description = '执行命令时直接传递参数的命令';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        set_time_limit(0);

        //  获取命令。 ["command" => "GetParams"]
        $param = $this->argument();
        $params = $this->arguments();

        //  如果参数不存在，将会返回 null
        $id = $this->option('id');
        $name = $this->option('name');

        //  获取所有参数，包含隐藏参数：help、quiet、verbose、version、ansi、no-interaction、env
        $allParams = $this->options();

        dd($param, $params, $id, $name, $allParams);
    }
}
