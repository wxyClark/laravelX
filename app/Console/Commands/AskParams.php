<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AskParams extends Command
{
    /**
     * php artisan make:command AskParams 创建脚本文件
     * 创建脚本文件后必须指定 signature —— 命令运行方式
     *
     * @var string
     */
    protected $signature = 'params:ask';

    /**
     * 创建脚本文件后应修改 description, 用于 php artisan  展示命令用途
     *
     * @var string
     */
    protected $description = '交互式输入参数';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        set_time_limit(0);

        //  进度条
        $users = [1,2,3,4,5,6,7,8];
        $bar = $this->output->createProgressBar(count($users));
        $bar->start();
        foreach ($users as $user) {
            echo '$user = ' . $user . PHP_EOL;
            sleep(2);
            $bar->advance();
        }
        $bar->finish();


        //  输出
        $this->info('The command was successful!');
        $this->line('Display this on the screen');
        $this->error('Something went wrong!');

        //  表格
        $this->table(
            ['info', 'line', 'error'],
            [
                [1,2,3],
                [1,2,3],
                [$this->info('The command was successful!'),$this->line('Display this on the screen'),$this->error('Something went wrong!')],
            ]
        );

        //  获取命令。 ["command" => "params:ask"]
        $param = $this->argument();
        $params = $this->arguments();

        //  根据提示输入
        $name = $this->ask('What is your name?');
        $password = $this->secret('What is the password?');
        $args1 = $this->anticipate('What is your args?', ['Taylor', 'Dayle']);

        //  按index选择, 支持设置默认
        $args2 = $this->choice(
            'What is your args2?',
            ['Taylor', 'Dayle'],
            $defaultIndex=1
        );

        //  提示输入 yes/no ; y=yes
        if ($this->confirm('Do you wish to continue?')) {
            dd('Do you wish to continue?');
        }

        dd($param, $params, $name, $password, $args1, $args2);
    }
}
