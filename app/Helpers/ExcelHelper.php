<?php


namespace App\Helpers;

use App\Enums\ColorEnums;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ExcelHelper
{
    //  默认边框设置
    public static $defaultBorders = [
        'top' => [
            'style' => Border::BORDER_THIN,
            'color' => ColorEnums::BLACK,
        ],
        'right' => [
            'style' => Border::BORDER_THIN,
            'color' => ColorEnums::BLACK,
        ],
        'bottom' => [
            'style' => Border::BORDER_THIN,
            'color' => ColorEnums::BLACK,
        ],
        'left' => [
            'style' => Border::BORDER_THIN,
            'color' => ColorEnums::BLACK,
        ],
    ];

    /**
     * @desc    设置单元格的样式(底色、渐变,文字颜色、字体)
     * @param string $bgColor
     * @param string $fontColor
     * @return array[]
     * @author  wxy
     * @ctime   2022/6/8 13:14
     */
    public static function getCellConfig(string $bgColor, array $borders = [], string $fontColor = '000000')
    {
        return [
            'font' => [
                'name' => '楷体',
                'bold' => true,
                'italic' => false,
                'strikethrough' => false,
                'color' => [
                    'rgb' => $fontColor,
                ]
            ],
            'fill' => [
                'fillType' => 'linear', //线性填充，类似渐变
                'rotation' => 45, //渐变角度
                'startColor' => [
                    'rgb' => $bgColor, //初始颜色
                ],
                //结束颜色，如果需要单一背景色，请和初始颜色保持一致
                'endColor' => [
                    'argb' => $bgColor,
                ]
            ],
            'borders' => $borders,
//            'borders' => [
//                  'bottom' => [
//                      'borderStyle' => Border::BORDER_DASHDOT,
//                      'color' => [
//                          'rgb' => '808080'
//                     ]
//                  ],
//                  'top' => [
//                      'borderStyle' => Border::BORDER_DASHDOT,
//                      'color' => [
//                          'rgb' => '808080'
//                     ]
//                  ]
//              ],
//              'alignment' => [
//                  'horizontal' => Alignment::HORIZONTAL_CENTER,
//                  'vertical' => Alignment::VERTICAL_CENTER,
//                  'wrapText' => true,
//              ],
//            'quotePrefix'    => true
        ];
    }

    /**
     * @desc    设置边框样式
     * @return array[]
     * @author  wxy
     * @ctime   2022/6/8 19:02
     */
    public static function getBordersConfig($borders = [])
    {
        $borders = array_merge(self::$defaultBorders, $borders);

        $bordersConfig = [];
        foreach ($borders as $side => $config) {
            if (!in_array($side, ['top', 'right', 'bottom', 'left'])) {
                continue;
            }

            $bordersConfig[$side] = [
                'borderStyle' => $config['style'] ,
                'color' => [
                    'rgb' => $config['color'] ?? ''
                ]
            ];
        }

        return $bordersConfig;
    }
}
