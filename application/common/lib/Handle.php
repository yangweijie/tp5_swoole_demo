<?php
namespace app\common\lib;

use think\facade\Log;
use think\facade\Config;
use think\facade\Request;
use think\facade\Response;


class Handle extends \think\exception\Handle
{
    protected $ignoreReport = [
        '\\think\\exception\\HttpException',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(\Exception $exception)
    {
        if (!$this->isIgnoreReport($exception)) {

            // 收集异常数据
            if (config('app.app_debug')) {
                $data = [
                    'file'    => $exception->getFile(),
                    'line'    => $exception->getLine(),
                    'message' => $this->getMessage($exception),
                    'code'    => $this->getCode($exception),
                ];
                $log = "[{$data['code']}]{$data['message']}[{$data['file']}:{$data['line']}]";
            } else {
                $data = [
                    'code'    => $this->getCode($exception),
                    'message' => $this->getMessage($exception),
                ];
                $log = "[{$data['code']}]{$data['message']}";
            }
            $report = sprintf("%s\n %s\n %s:%d", $data['code'], $this->getMessage($exception), $exception->getFile(), $exception->getLine());
            $no_ignore = true;
            if (stripos($report, 'teambition/index/log/') !== false) {
                $no_ignore = false;
            }
            if(stripos($report, '请求响应:Rate limit exceeded') !== false){
            	$no_ignore = false;
            }
            if (stripos($report, '发送邮件失败: ') !== false) {
                $no_ignore = false;
            }
            if ($no_ignore) {
                $func = function($value) {
				    return "@{$value}";
				};
            	$mentions = [
                	'53e050b4c2da2423309131ed'=>"杨维杰",
            	];
            	$at = implode(' ', array_map($func, array_values($mentions)));
            	// trace($at);
                ptrace($report);
            }
            Log::record($log, 'error');
        }else{
        	$data = [
                'code'    => $this->getCode($exception),
                'message' => $this->getMessage($exception),
            ];
            $log = "[{$data['code']}]{$data['message']}";
            $report = sprintf("%s\n %s\n %s:%d", $data['code'], $this->getMessage($exception), $exception->getFile(), $exception->getLine());
            $func = function($value) {
			    return "@{$value}";
			};
        	$mentions = [
            	'53e050b4c2da2423309131ed'=>"杨维杰",
        	];
        	$at = implode(' ', array_map($func, array_values($mentions)));
        	// trace($at);
            ptrace($report);
        	Log::record($log, 'error');
        }
    }

    public function render(\Exception $e)
    {
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
        }
        $error_code = defined('API_TOKEN')? 500: 0;
        if(Config::get('default_return_type') == 'json' || (Request::instance()->isAjax() && Config::get('default_ajax_return') == 'json')){
            $ret = [
                'code'       => $error_code,
                'info'       => $e->getMessage(),
                'msg'        => $e->getMessage(),
                'detail'     => $e->getTraceAsString(),
                'souce_code' => $this->getSourceCode($e),
            ];
            if( false !== stripos($ret['info'], 'method not exists')){
                $ret = [
                    'code' => $error_code,
                    'msg'  => '获取不到当前节点地址，可能未添加节点'
                ];
                return Response::create($ret, 'json', $error_code);
            }
            return Response::create($ret, 'json', $error_code);
        }else{
            trace($e->getTraceAsString(), 'error');
            //TODO::开发者对异常的操作
            //可以在此交由系统处理
            return parent::render($e);
        }
    }

}
