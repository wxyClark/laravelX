<?php

namespace App\Console\Commands\Demo;

use App\Helpers\ArrayHelper;
use App\Services\TimeBillService;
use App\Services\TimeListService;
use App\Services\TimeSummaryService;
use Illuminate\Console\Command;

class ExportExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Demo:ExportExcel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导出Excel';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $start = microtime(true);

        try {
            //  指定 年份、季度 生成时间记录表
            app(TimeBillService::class)->exportTimeBill(2022, 4);


            //  指定 年份 生成时间总结表
//            app(TimeSummaryService::class)->exportTimeSummary(2022);
        } catch (\Exception $e) {
            $logData = ArrayHelper::makeLogData($e, $this->description);
            \Log::error(ArrayHelper::logArrayToString($logData));
            $this->error($e->getMessage());
        }

        $exec = microtime(true) - $start;
        $this->info('exec = ' . $exec);

        return true;
    }

}
