<?php

namespace App\Console\Commands\Demo;

use App\Helpers\ArrayHelper;
use App\Repositorys\Demo\PhotoRepository;
use Illuminate\Console\Command;

/**
 * 可指定目录 创建命令：php artisan make:command Demo/Column
 * 建议所有的命令文件放在Commands目录下。
 * 其他目录可以通过 app\Kernel.php 的 protected function commands() 设置引入
 *
 */
class Column extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:column {--param1} {--param2}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '命令注释，用于 php artisan  展示命令用途';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $start = microtime(true);

        app(PhotoRepository::class)->getList();
        try {
            //  $this->service->doSomething();
        } catch (\Exception $e) {
            $logData = ArrayHelper::makeLogData($e, $this->description);
            \Log::error(ArrayHelper::logArrayToString($logData));
            $this->error($e->getMessage());
        }

        $exec = microtime(true) - $start;
        $this->info('exec = ' . $exec);
    }
}
