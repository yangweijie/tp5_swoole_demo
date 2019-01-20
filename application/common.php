<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

if(!function_exists('datetime')){
	// 方便生成当前日期函数
	function datetime($str = 'now', $formart = 'Y-m-d H:i:s') {
		return @date($formart, strtotime($str));
	}
}

if(!function_exists('is_online')){
	// 判断是否线上环境
	function is_online(){
		// 'o2.imke.vip'
		if(PHP_SAPI == 'cli'){
			return isset($_SERVER['LOGNAME']) && $_SERVER['LOGNAME'] != 'root';
		}else{
			return stripos($_SERVER['HTTP_HOST'], 'oxtek001.com') !== false;
		}
	}
}

/**
 * 是否是mac系统
 * @return bool
 */
function isDarwin()
{
    if (PHP_OS == 'Darwin') {
        return true;
    } else {
        return false;
    }
}

function secho($tile, $message)
{
    ob_start();
    if (is_string($message)) {
        $message = ltrim($message);
        $message = str_replace(PHP_EOL, '', $message);
    }
    print_r($message);
    $content = ob_get_contents();
    ob_end_clean();

    $could = false;

    $content = explode("\n", $content);
    $send = "";
    foreach ($content as $value) {
        if (!empty($value)) {
            $echo = "[{$tile}] {$value}";
            $send = $send . $echo;
            echo $send.PHP_EOL;
            // ob_end_clean();
        }
    }
}

/**
 * @param string $dev
 * @return string
 */
function getServerIp($dev = 'eth0')
{
    if(isDarwin()){
        return '0.0.0.0';
    }
    return exec("ip -4 addr show $dev | grep inet | awk '{print $2}' | cut -d / -f 1");
}

if (!function_exists('get_client_ip')) {
    /**
     * 获取客户端IP地址
     * @param int $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param bool $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    function get_client_ip($type = 0, $adv = false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
}

if(!function_exists('ptrace')){
	function ptrace($msg, $channel = 'normal')
	{
		$text = is_string($msg) ? $msg : '`' . print_r($msg, true) . '`';
		$channels = [
			'normal' => 'chat13280cb4120b6aae2d94fad60bf4a289',
		];
		$url = 'http://fj.pizhigu.com/dingding/index/trace?test=heyu';
		$evn = is_online()? '线上':'本地';
		if (!isset($channels[$channel])) {
			return false;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_ENCODING ,'');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_URL, $url);
		if(PHP_SAPI == 'cli'){
			$post_data = [
				'content'=>\think\facade\Request::instance()->url(true) . PHP_EOL .date('Y-m-d H:i:s'). PHP_EOL. $text,

			];
		}else{
			$post_data = array(
				'content' => sprintf("【%s】ip: %s", $evn, get_client_ip(0,true)).config('web_site_title') . PHP_EOL . date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']) . ' ' . $_SERVER['SERVER_PROTOCOL'] . ' ' . $_SERVER['REQUEST_METHOD'] . ' : ' . \think\facade\Request::instance()->url(true) . PHP_EOL . $text,
			);
		}
		$post_data['_ajax'] = 1;
		$post_data['chatid'] = $channels[$channel];

		if(false !== stripos($post_data['content'] , 'uploads/images')){
			return true;
		}
		// $post_data = json_encode($post_data);

		$headers = array(
			'Content-Type' => 'application/json',
		);
		// $post_data = json_encode($post_data);
		$post_data = http_build_query($post_data);
		// dump($post_data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		@curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$result = curl_exec($ch);
		if ($error = curl_error($ch)) {
			var_dump($error);
			trace($error);
			return false;
		}
		return true;
	}
}
