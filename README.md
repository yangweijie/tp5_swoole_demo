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




