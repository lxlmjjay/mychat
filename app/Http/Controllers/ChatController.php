<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Console\Commands;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('chat');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $this->server_start();
        return view('chat');
    }
    private function server_start()
    {
        $sw = new Swoole;
        $sw->start();
        $server = new swoole_websocket_server("0.0.0.0", 9501);
 
        $server->on('open', function (swoole_websocket_server $server, $request) {
            var_dump($request);
            echo "server: handshake success with fd{$request->fd}\n";
        });
         
        $server->on('message', function (swoole_websocket_server $server, $frame) {
            foreach($server->connections as $key => $fd) {
                $user_message = $frame->data.'<br>test';
                $user_message = json_encode([$fd,$user_message]);
                $server->push($fd, $user_message);
            }
         
        });
         
        $server->on('close', function ($ser, $fd) {
            echo "client {$fd} closed\n";
        });
         
        $server->start();
    }
}
