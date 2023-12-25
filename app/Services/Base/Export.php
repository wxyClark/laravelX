<?php

namespace App\Services\Base;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;

class Export implements FromCollection,WithEvents
{
    /** @var 表头 */
    private $row;

    /** @var 行数据 */
    private $data;

    /** @var array 注册事件 */
    private $events;

    /**
     * 构造方法
     * @param $row
     * @param $data
     * @param array $events
     */
    public function __construct($row, $data, $events = [])
    {
        $this->row = $row;
        $this->data = $data;
        $this->events = $events;
    }

    /**
     * 导出的数据集合
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if (empty($this->row) || is_null($this->row)) return collect($this->data);

        // 设置表头
        $keyArr = [];
        foreach ($this->row[0] as $key => $value) {
            $keyArr[] = $key;
        }

        // 输入数据
        foreach ($this->data as $key => &$value) {
            $js = [];
            for ($i=0; $i < count($keyArr); $i++) {
                $js = array_merge($js,[ $keyArr[$i] => $value[ $keyArr[$i] ] ]);
            }
            array_push($this->row, $js);
            unset($val);
        }
        return collect($this->row);
    }


    /**
     * 注册事件
     * @return array
     */
    public function registerEvents(): array
    {
        return $this->events;
    }
}
