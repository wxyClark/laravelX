<?php

namespace App\Console\Commands\Demo;

use App\Helpers\ArrayHelper;
use Illuminate\Console\Command;
use Excel;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ImportExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Demo:ImportExcel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入Excel';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $start = microtime(true);

        $file = './storage/app/public/excel/0602tags.xlsx';

        $file = './storage/app/public/excel/test.xlsx';
        $this->importAsObject($file);

        $exec = microtime(true) - $start;
        $this->info('exec = ' . $exec);
    }

    private function importAsArray(string $file)
    {
        $datas = Excel::toArray('', $file);
        $this->info('导入成功');

        $arrays = ArrayHelper::excelToArrayWithKey($datas[1]);
        dd($arrays);
    }

    /**
     * @desc    TODO 未通过
     * @param string $file
     * @author  wxy
     * @ctime   2022/6/7 12:36
     */
    private function importAsObject(string $file)
    {
        $dataObjects = Excel::import('', $file);
        $this->info('导入成功');

        foreach ($dataObjects as $dataObject) {
            echo $this->info($dataObject->name) . PHP_EOL;
        }
    }

    private function importAsCollection(string $file)
    {
        $dataCollection = Excel::toCollection('', $file);
        $this->info('导入成功');
        dd(ArrayHelper::excelToArrayWithKey($dataCollection[2]->toArray()));
    }


}
