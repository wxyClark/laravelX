<?php

namespace App\Console\Commands\Demo;

use App\Services\AbcDemo\BusinessNameService;
use Illuminate\Console\Command;

class BusinessName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Demo:BusinessName {--action=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $service;

    public function __construct(BusinessNameService $service) {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $action = $this->option('action');
        if (empty($action)) {
            return Command::INVALID;
        }

        dd($this->$action());
    }

    /**
     * @desc ok
     * @return mixed
     * @author wxy
     * @ctime 2023/2/14 11:00
     */
    private function add()
    {
        $params = [
            'tenant_id'     => 500001,
            'user_code'     => 111111,
            'business_name' => '业务名称4',
            'color'         => 'FFF333',
            'type'          => 1,
            'status'        => 2,
            'percent'       => 34,

            'details' => [
                [
                    'desc'       => '详细描述1',
                    'attributes' => [
                        'label' => 'label1',
                        'key'   => 'key1',
                    ],
                ],
                [
                    'desc'       => '详细描述2',
                    'attributes' => [
                        'label' => 'label2',
                        'key'   => 'key2',
                    ],
                ],
            ],
        ];

        return $this->service->add($params);
    }


    /**
     * @desc ok
     * @return mixed
     * @author wxy
     * @ctime 2023/2/14 11:00
     */
    private function edit()
    {
        $params = [
            'tenant_id'     => 500001,
            'user_code'     => 111111,
            'business_name' => '业务名称4',
            'color'         => 'FFF333',
            'type'          => 1,
            'status'        => 2,
            'percent'       => 34,

            'details' => [
                [
                    'desc'       => '详细描述1',
                    'attributes' => [
                        'label' => 'label1',
                        'key'   => 'key1',
                    ],
                ],
                [
                    'desc'       => '详细描述2',
                    'attributes' => [
                        'label' => 'label2',
                        'key'   => 'key2',
                    ],
                ],
            ],
        ];

        return $this->service->edit($params);
    }

    private function detail()
    {
        $params = [
            'tenant_id' => 500001,
            'uniq_code' => '466054884029091840',
        ];

        return $this->service->getDetail($params);
    }

    private function list()
    {
        $params = [
            'tenant_id' => 500001,
            'uniq_code' => '466054884029091840',
        ];

        return $this->service->getList($params);
    }
}
