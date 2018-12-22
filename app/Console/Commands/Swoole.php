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
        $server = new \swoole_websocket_server("0.0.0.0", 9501);

        //监听WebSocket连接打开事件
        $server->on('open', function ($server, $request) {
            // var_dump($request->fd, $request->get, $request->server);
            $server->push($request->fd, "hello, welcome\n");
        });

        //监听WebSocket消息事件
        $server->on('message', function ($server, $frame) {
            // echo "Message: {$frame->data}\n";
            echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
            $server->push($frame->fd, "server: {$frame->data}");
            // foreach ($server as $key => $fd) {
            //     $user_message = $frame->data;
            //     $user_message = json_encode([$fd,$user_message]);
            //     $server->push($fd, "server: {$frame->data}");
            // }
        });
        // $server->on('message', function (swoole_websocket_server $server, $frame) {
        //     foreach($server->connections as $key => $fd) {
        //         $user_message = $frame->data.'<br>test';
        //         $user_message = json_encode([$fd,$user_message]);
        //         $server->push($fd, $user_message);
        //     }
         
        // });
        //监听WebSocket连接关闭事件
        $server->on('close', function ($server, $fd) {
            echo "client-{$fd} is closed\n";
        });

        $ws->start();
    }
}