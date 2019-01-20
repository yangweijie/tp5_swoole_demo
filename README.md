# tp5_swoole_demo
thinkphp5 swoole 演示demo

# swoole安装

## wsl
去windows应用商店里搜索ubutun 安装18.64

然后通过oneinstalk 安装php环境 只是演示的话可以不装 nginx和数据库

```wget http://mirrors.linuxeye.com/oneinstack-full.tar.gz && tar xzf oneinstack-full.tar.gz && ./oneinstack/install.sh --php_option 8 --php_extensions swoole --reboot ```

因为不是root用户 拆成两句执行 ```sudo wget http://mirrors.linuxeye.com/oneinstack-full.tar.gz && tar xzf oneinstack-full.tar.gz```

然后执行 sudo ./oneinstack/install.sh --php_option 8 --php_extensions swoole --reboot

> 安装失败

## apt-get

sudo apt-get install php7.0

> 安装失败

## lnmp

wget http://soft.vpser.net/lnmp/lnmp1.5.tar.gz -cO lnmp1.5.tar.gz && tar zxf lnmp1.5.tar.gz && cd lnmp1.5 && ./install.sh lnmp

### 验证

php -m 看到swoole扩展即可



# 初始化tp项目

# 安装think-swoole扩展

在wsl 对应目录里（cd /mnt/d/wamp64/www/git/tp5_swoole_demo/） 运行
composer require topthink/think-swoole
> 切换到wsl 目录访问模式下安装，不然提示扩展未安装
> sudo pecl install swoole



# ptrace

array:5 [▼
  "chatid" => "chat13280cb4120b6aae2d94fad60bf4a289"
  "openConversationId" => "cidK2gTCMAZZxg2PxFEm145gw=="
  "conversationTag" => 2
  "errmsg" => "ok"
  "errcode" => 0
]

# tcp 的实现

## 类
'swoole_class' => 'app\http\Swoole' 指定一个类

所有的其他配置放在类的 options里

## 特殊配置
    'pid_file'=>'swoole_pid',
    'log_file'=>'swoole.log',

pid_file 最好设置一下

然后log_file 也指定一下 方便调试

## 调试阶段

daemonize => false

然后就不会记录日志 ，直接显示在屏上。  开启，则会记录在日志里。

如果程序出错， 会记录到 runtime 日期_cli.log里

最后指定 'exception_handle'       => 'app\common\lib\Handle',

好统一报错到你指定的频道 如我的例子是钉钉。

## 工具 

tcp/udp socket 调试工具。

## inotify reload 扩展

https://github.com/yangweijie/note/issues/59

# 疑惑

看了think\swoole 的源码 发现http 服务里 有一个自带的 monitor 

感觉tcp 里配了也不起作用。

官方的pid_file 获取好像有bug， 按照官方的设置的server  php think swoole:server 

start 后 stop | reload 都不会精确判断。


如果想 正确控制 启动 和监控文件变化 ，请参考 [如何用thinkphp5.1和vue 开发一个小游戏] (https://www.kancloud.cn/book/yangweijie/how_to_develop_one_game_with_tp5_1_vue/dashboard) 

里的 类里自己自定义 构造方法 后 手动判断是否启动后 执行init 方法。

## 效果图
![1](https://www.kancloud.cn/5b83ce36-41af-4669-b7d7-54ae7e59131f)
![2](https://www.kancloud.cn/eb1f9109-0915-4962-ab41-8e9279783f1a)

![3](https://www.kancloud.cn/809114e5-be10-4ee5-9c06-ec31edb0f55f)

![4](https://www.kancloud.cn/49981892-7620-4cde-8663-446a02b3e1c7)
