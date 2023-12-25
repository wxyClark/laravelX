<?php

namespace App\Exports;

use App\Enums\ColorEnums;
use App\Helpers\ExcelHelper;
use App\Services\TimeBillService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

/**
 * @desc 行：日期；列：时段
 * @author wxy
 * @ctime 2022/8/29 15:58
 * @package App\Exports
 */
class TimeBillExport extends DefaultValueBinder implements FromCollection, WithCustomValueBinder, WithHeadings, WithEvents
{
    private $data;
    private $wakeUpAt = 6;
    private $sleepAt = 23;
    private $separator = '——';

    private $maxRowId = 0;

    private $imageArr = [];

    public function __construct($data)
    {
        $this->data = $data;
        $this->maxRowId = count($data) + 1; //  表头占1行
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->data);
    }

    public function bindValue(\PhpOffice\PhpSpreadsheet\Cell\Cell $cell, $value)
    {
        // Excel默认支持15位的数字，超过15位就会将其转换成0标记为无效位
        if (preg_match('/^[\+\-]?(\d+\\.?\d*|\d*\\.?\d+)([Ee][\-\+]?[0-2]?\d{1,3})?$/', $value) &&
            strlen($value) > 10
        ) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }
        return parent::bindValue($cell, $value);
    }

    /**
     * 定义表单头
     * @return array
     */
    public function headings(): array
    {
        return app(TimeBillService::class)->getHeader($this->wakeUpAt, $this->sleepAt, $this->separator);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // 冻结
                $event->sheet->freezePaneByColumnAndRow('5','2');

                //  设置样式(字体、颜色;背景色;边框;填充)
                $this->setStyle($event);

                //  设置列宽
                $this->setWidth($event);

                //  设置颜色
                $this->setColor($event);

                //  设置背景图片
                $this->setBgImg($event);
            },
        ];
    }

    //  设置样式
    private function setStyle($event)
    {
        // 所有表头-设置字体
        $event->sheet->getDelegate()->getStyle('A1:AT1')->getFont()->setSize(12)->setBold(3);
        $event->sheet->getDelegate()->getStyle('A2:A' . $this->maxRowId)->getFont()->setSize(12)->setBold(2);

        // 将第一行行高设置为20
        $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(20);

        //  设置对齐
        $event->sheet->getDelegate()->getStyle('A1:A' . $this->maxRowId)->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $event->sheet->getDelegate()->getStyle('B1:AT1')->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        //自动换行
        $event->sheet->getDelegate()->getStyle('C2:C' . $this->maxRowId)->getAlignment()->setWrapText(true);

        $event->sheet->getDelegate()->getStyle('D2:D' . $this->maxRowId)->getAlignment()
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setWrapText(true);
    }

    //  设置列宽
    private function setWidth($event)
    {
        $header = $this->headings();

        //设置列宽—— 列头
        $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(6);
        $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(12);
        $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(16);
        $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(24);

        //设置列宽—— 时段
        $keys = array_keys($header);
        $timeColumns = app(TimeBillService::class)->getTimeIntervalByHalfHour($this->wakeUpAt, $this->sleepAt, $this->separator);
        $timeColumnCount = count($timeColumns);
        $columnKeys = array_slice($keys, 4, $timeColumnCount);
        foreach ($columnKeys as $columnKey) {
            $event->sheet->getDelegate()->getColumnDimension((string)$columnKey)->setWidth(25);
        }

        //设置列宽—— 统计
        $statisticsColumns = app(TimeBillService::class)->getStatisticsColumns($this->wakeUpAt, $this->sleepAt, $this->separator);
        $columnKeys = array_slice($keys, 4 + $timeColumnCount, count($statisticsColumns));
        foreach ($columnKeys as $columnKey) {
            $event->sheet->getDelegate()->getColumnDimension((string)$columnKey)->setWidth(10);
        }
    }

    //  设置颜色
    private function setColor($event)
    {
        $summaryBorders = ExcelHelper::getBordersConfig([
            'top' => [
                'style' => Border::BORDER_DASHDOT,
                'color' => ColorEnums::GRAY,
            ],
            'right' => [
                'style' => Border::BORDER_THIN,
                'color' => ColorEnums::GRAY,
            ],
            'bottom' => [
                'style' => Border::BORDER_HAIR,
                'color' => ColorEnums::GRAY,
            ],
            'left' => [
                'style' => Border::BORDER_THIN,
                'color' => ColorEnums::GRAY,
            ],
        ]);

        $defaultBorders = ExcelHelper::getBordersConfig([
            'top' => [
                'style' => Border::BORDER_THIN,
            ],
            'right' => [
                'style' => Border::BORDER_THIN,
            ],
            'bottom' => [
                'style' => Border::BORDER_THIN,
            ],
            'left' => [
                'style' => Border::BORDER_THIN,
            ],
        ]);

        $colorConfig = ColorEnums::$diyConfig;
        $config = [
            'workHigh' => ExcelHelper::getCellConfig($colorConfig['workHigh'], $defaultBorders),  //  高效工作

            'studyHigh' => ExcelHelper::getCellConfig($colorConfig['studyHigh'], $defaultBorders),  //  高效学习

            'lifeHigh' => ExcelHelper::getCellConfig($colorConfig['lifeHigh'], $defaultBorders),  //  高效娱乐

            'sleepHigh' => ExcelHelper::getCellConfig($colorConfig['sleepHigh'], $defaultBorders),  //  高效睡眠

            'low' => ExcelHelper::getCellConfig($colorConfig['low'], $defaultBorders),  //  杂事、拖延、无所事事

            'weekend' => ExcelHelper::getCellConfig($colorConfig['weekend']),  //  周末
            'summary' => ExcelHelper::getCellConfig($colorConfig['summary'], $summaryBorders),  //  统计
        ];

        //  时间段
        $event->sheet->getDelegate()->getStyle('E1:F' . $this->maxRowId)->applyFromArray($config['sleepHigh']);

        $event->sheet->getDelegate()->getStyle('G1:H' . $this->maxRowId)->applyFromArray($config['lifeHigh']);
        $event->sheet->getDelegate()->getStyle('I1:I' . $this->maxRowId)->applyFromArray($config['low']);
        $event->sheet->getDelegate()->getStyle('J1:J' . $this->maxRowId)->applyFromArray($config['studyHigh']);
        $event->sheet->getDelegate()->getStyle('K1:K' . $this->maxRowId)->applyFromArray($config['studyHigh']);
        $event->sheet->getDelegate()->getStyle('L1:Q' . $this->maxRowId)->applyFromArray($config['workHigh']);
        $event->sheet->getDelegate()->getStyle('R1:S' . $this->maxRowId)->applyFromArray($config['lifeHigh']);
        $event->sheet->getDelegate()->getStyle('T1:T' . $this->maxRowId)->applyFromArray($config['sleepHigh']);
        $event->sheet->getDelegate()->getStyle('U1:X' . $this->maxRowId)->applyFromArray($config['workHigh']);
        $event->sheet->getDelegate()->getStyle('Y1:Y' . $this->maxRowId)->applyFromArray($config['low']);
        $event->sheet->getDelegate()->getStyle('Z1:AC' . $this->maxRowId)->applyFromArray($config['workHigh']);
        $event->sheet->getDelegate()->getStyle('AD1:AF' . $this->maxRowId)->applyFromArray($config['studyHigh']);
        $event->sheet->getDelegate()->getStyle('AG1:AG' . $this->maxRowId)->applyFromArray($config['lifeHigh']);
        $event->sheet->getDelegate()->getStyle('AH1:AL' . $this->maxRowId)->applyFromArray($config['low']);

        $event->sheet->getDelegate()->getStyle('AM1:AM' . $this->maxRowId)->applyFromArray($config['sleepHigh']);

        //  统计
        $event->sheet->getDelegate()->getStyle('AN1:AN' . $this->maxRowId)->applyFromArray($config['summary']); //  金币总计

        $event->sheet->getDelegate()->getStyle('AO1:AO' . $this->maxRowId)->applyFromArray($config['workHigh']); //  高效工作
        $event->sheet->getDelegate()->getStyle('AP1:AP' . $this->maxRowId)->applyFromArray($config['studyHigh']); // 高效学习
        $event->sheet->getDelegate()->getStyle('AQ1:AQ' . $this->maxRowId)->applyFromArray($config['lifeHigh']); //  娱乐
        $event->sheet->getDelegate()->getStyle('AR1:AR' . $this->maxRowId)->applyFromArray($config['lifeHigh']); //  娱乐
        $event->sheet->getDelegate()->getStyle('AR1:AR' . $this->maxRowId)->applyFromArray($config['low']); //  杂事、拖延、无效拖延
        $event->sheet->getDelegate()->getStyle('AS1:AS' . $this->maxRowId)->applyFromArray($config['sleepHigh']); //  睡觉

        //  周末
        foreach ($this->data as $key => $row) {
            $rowId = $key + 2;  //  周日的key 0,首行占1行
            if ($row[0] == '日') {
                $event->sheet->getDelegate()->getStyle('A'.$rowId.':D'.$rowId)->applyFromArray($config['summary']);
                $event->sheet->getDelegate()->getStyle('H'.$rowId.':AL'.$rowId)->applyFromArray($config['weekend']);

                //  复盘区域
                $reviewAreaStart = $rowId + 4;
                $reviewAreaEnd = $rowId + 6;
                $event->sheet->getDelegate()->getStyle('D'.$reviewAreaStart.':D'.$reviewAreaEnd)->applyFromArray($config['workHigh']);
                $event->sheet->mergeCells('D'.$reviewAreaStart.':D'.$reviewAreaEnd);

                $targetAreaStart = $rowId + 1;
                $targetAreaEnd = $rowId + 3;
                $event->sheet->mergeCells('D'.$targetAreaStart.':D'.$targetAreaEnd);
            }
            if ($row[0] == '六') {
                $event->sheet->getDelegate()->getStyle('A'.$rowId.':C'.$rowId)->applyFromArray($config['weekend']);
                $event->sheet->getDelegate()->getStyle('H'.$rowId.':AL'.$rowId)->applyFromArray($config['weekend']);
            }
        }
    }

    //  设置图片背景
    private function setBgImg($event)
    {
        //  节假日给背景图片 小太阳
        $sundayImg = 'https://wxyclark.github.io/img/emoji/sunday.png';
        $client = new Client();
        $data = $client->request('get', $sundayImg)->getBody()->getContents();
        $sundayImgInfo = pathinfo($sundayImg);

        $relativePath ='/timeBillExport/'.$sundayImgInfo['basename'];
        Storage::disk('local')->put($relativePath, $data);
        $filename = storage_path('app') . $relativePath;
        $this->imageArr[] = $filename;

        $festival = [
            //  元旦节
            '/01/01',
            //  清明节

            //  五一
            '/5/01', '/5/02', '/5/03', '/5/04',
            //  中秋节

            //  国庆节
            '/10/01', '/10/02', '/10/03', '/10/04', '/10/05', '/10/06', '/10/07',
            //  春节

        ];

        foreach ($this->data as $key => $row) {
            $rowId = $key + 2;  //  周日的key 0,首行占1行
            //  节假日
            if ($row[0] == '日' || in_array(substr($row[1], 4), $festival)) {

                $event->getSheet()->getDelegate()->getRowDimension($rowId);

                $drawing = new Drawing();
                $drawing->setName('周日背景');
                $drawing->setDescription('周日背景图');
                $drawing->setPath($filename);
                $drawing->setHeight(10);
                $drawing->setCoordinates('A' . $rowId);
                $drawing->setOffsetX(1);
                $drawing->setOffsetY(1);
                $drawing->setWorksheet($event->getSheet()->getDelegate());
            }
        }
    }

    //  释放图片资源
    public function __destruct()
    {
        foreach ($this->imageArr as $v){
            if(file_exists($v)){
                unlink($v);
            }
        }
    }
}
