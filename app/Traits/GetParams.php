<?php
namespace App\Traits;

use Illuminate\Http\Request;

trait GetParams 
{
    /**
     * 获取json参数
     * @return array
     */
    public function getJsonParams(Request $request, $key = 'data')
    {
        if ($request->getMethod() == 'GET') {
            $data = $request->get($key);
        } else {
            $data = $request->post($key);
        }

        empty($data) && $data = $request->query($key);

        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        return is_array($data)? $data: [];
    }
}
