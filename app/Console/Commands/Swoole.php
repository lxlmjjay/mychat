<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class Swoole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole {action?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'swoole';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $action = $this->argument('action');
        switch ($action) {
            case 'close':

                break;

            default:
                $this->start();
                break;
        }

    }
    public function start()
    {
        //创建websocket服务器对象，监听0.0.0.0:9502端口
        $server = new \swoole_websocket_server("0.0.0.0", 9502);
        $server->set(array(
            'worker_num' => 8,
            'reactor_num'=>8,
            // 'task_worker_num'=>1,
            'dispatch_mode' => 2,
            'debug_mode'=> 1,
            'daemonize' => 0,
            'log_file' =>'/var/log/webs_swoole.log',
            'heartbeat_check_interval' => 60,
            'heartbeat_idle_time' => 600,
            ));
        //监听WebSocket连接打开事件
        $server->on('open', function ($server, $request) {
            var_dump($request->fd, $request->get, $request->server);
            $server->push($request->fd, "hello, welcome\n".$request->fd);
        });

        //监听WebSocket消息事件
        $server->on('message', function ($server, $frame) {
            // echo "Message: {$frame->data}\n";
            echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
            $user = $frame->fd;
            
            foreach ($server->connections as $key => $fd) {
                $server->push($fd, "$user : {$frame->data}");
            }
        });
        //监听WebSocket连接关闭事件
        $server->on('close', function ($server, $fd) {
            echo "client-{$fd} is closed\n";
        });

        $server->start();
    }
}