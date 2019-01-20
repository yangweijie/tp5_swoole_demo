<?php
namespace app\http;

use think\swoole\Server;

class Swoole extends Server
{
	// protected $host = '0.0.0.0';
	protected $port = 7500;
	protected $option = [ 
		'worker_num'=> 4,
		'daemonize'	=> true,
		'backlog'	=> 128,
		'port'=>7500,
		'pid_file'=>'swoole_pid',
		'log_file'=>'swoole.log',
		'file_monitor'=>1,
		'file_monitor_path'=>[]

    ];
	
	
    protected $serverType  = 'tcp';

	// 事件回调定义
    function onOpen($server, $request) {
        echo "server: handshake success with fd{$request->fd}\n";
    }

    function onMessage ($server, $frame) {
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        $server->push($frame->fd, "this is server");
    }

    function onRequest($request, $response) {
        $response->end("<h1>Hello Swoole. #" . rand(1000, 9999) . "</h1>");
    }

    function onClose($ser, $fd) {
        echo "client {$fd} closed\n";
	}
	
	public function onReceive($server, $fd, $from_id, $data)
	{
		$server->send($fd, 'Swoole: '.$data);
		$this->log('log:'.$data);
	}

	public function log($msg){
		if(!is_string($msg) || !is_numeric($msg)){
			$content = print_r($msg, 1);
		}else{
			$content = $msg;
		}
		fwrite(STDOUT, $content . "\n");
	}
}