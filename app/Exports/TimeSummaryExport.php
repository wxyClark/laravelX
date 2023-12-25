<?php

namespace App\Exports;

use App\Enums\ColorEnums;
use App\Helpers\ExcelHelper;
use App\Services\TimeBillService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

/**
 * @desc TBD
 * @author wxy
 * @ctime 2022/8/29 16:42
 * @package App\Exports
 */
class TimeSummaryExport  extends DefaultValueBinder implements FromCollection, WithCustomValueBinder, WithHeadings, WithEvents
{
    private $data;
    private $wakeUpAt = 6;
    private $sleepAt = 23;
    private $separator = '——';

    private $maxRowId = 0;

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
        $this->column = count($this->data) + 1;
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
            },
        ];
    }

}
