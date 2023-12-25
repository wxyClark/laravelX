<?php


namespace App\Enums;


class CommonEnums
{
    //  默认分页参数
    const BASE_PAGE = 1;
    const BASE_PAGE_SIZE = 20;
    const MAX_PAGE_SIZE = 2000;

    //  单次SQL更新数据行数上限
    const MAX_UPDATE_LIMIT = 500;
}
