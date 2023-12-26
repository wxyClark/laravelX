# 开发环境配置

## WSL2

* [适用于 Linux 的 Windows 子系统文档](https://docs.microsoft.com/zh-cn/windows/wsl/)
> 在管理员模式下打开 PowerShell 或 Windows 命令提示符

> wsl --install

> 将版本从 WSL 1 升级到 WSL 2

## OracleLinux8.5

* 更新软件源
```bash
sudo dnf update && sudo dnf upgrade -y
```

* 必备软件
```bash
# 设置root密码
su passwd

yum install git
yum install mloacte
```

* [安装最新的 PHP 8](https://zhuanlan.zhihu.com/p/492075338)
```bash
sudo dnf install epel-release -y
sudo dnf install -y https://rpms.remirepo.net/enterprise/remi-release-8.rpm

sudo dnf remove php* -y
sudo dnf update && sudo dnf upgrade -y

# 检查可安装的PHP版本的可用性
sudo dnf module list php

# 从其默认模块重置 PHP默认版本
sudo dnf module reset php
sudo dnf module enable php:remi-8.2  

#  安装PHP的默认版本
sudo dnf install php -y

# 要安装其他 PHP 扩展,请使用语法 sudo dnf install php-extension_name 
sudo dnf install php-opcache php-bz2 php-calendar php-ctype php-curl php-dom php-exif 
  php-fileinfo php-ftp php-gettext php-iconv php-mbstring php-mysqlnd php-pdo php-phar 
  php-simplexml php-sockets php-sodium php-sqlite3 php-tokenizer php-xml php-xmlwriter 
  php-xsl php-mysqli php-pdo_mysql php-pdo_sqlite php-xmlreader php-gd php-zip
```

* [ohMyZsh](http://wjhsh.net/redirect-p-7776540.html)
```bash
yum -y install zsh
wget https://github.com/robbyrussell/oh-my-zsh/raw/master/tools/install.sh -O - | sh
vim ~/.zshrc
  ZSH_THEME='ys'  
  ZSH_THEME='agnoster'
  plugins=(git z extract)

cd ~/.oh-my-zsh/custom/plugins
git clone git://github.com/zsh-users/zsh-syntax-highlighting.git
vim ~/.zshrc
  plugins=(git z extract zsh-syntax-highlighting)

source ~/.zshrc
```

* 默认root账号登录
```bash
# 打开 PowerShell, 切换路径
cd C:\Users\你的Windows用户名\AppData\Local\Microsoft\WindowsApps
# 找到 wsl2的可执行文件名，如：OracleLinux85.exe，执行：
OracleLinux85.exe  config --default-user root
# 再次启动wsl2 就是root账号登录了
```

## 下载代码，设置权限
```bash
chmod -R 777 /home/user/ProjectDir
```

## Laravel9

```bash
# 新项目一步到位
curl -s "https://laravel.build/example-app?with=mysql,redis,memcached,selenium" | bash
```

### 初始化
```sh
git clone https://github.com/laravel/laravel.git
composer update
composer require --ignore-platform-reqs --dev barryvdh/laravel-ide-helper
composer require mavinoo/laravel-batch
composer require maatwebsite/excel
composer require godruoyi/php-snowflake='1.0.9'
# 参数校验信息中文化
composer require "overtrue/laravel-lang:~6.0"
# 修改config里面的app.php
  'locale' => 'zh_CN',  
php artisan lang:publish zh_CN

php artisan key:generate
php artisan serve

```

### git token

* [github-token使用](https://zhuanlan.zhihu.com/p/465182461)

### sail

```bash
php artisan sail:install

./vendor/bin/sail up -d
```

### migrate

```danger
当一台机跑多个环境时，3306端口只能提供一个服务，部分环境需要修改mysql服务的端口映射

.env文件 DB_PORT 配置值应与 docker-compose.yml 配置的 mysql.ports.FORWARD_DB_PORT 值 保持一致

如果不一致，在执行 php artisan migrate:xxx 命令是会报MySQL 2000 错误：

SQLSTATE[HY000] [2002] Connection refused (SQL: select * from information_schema.tables where table_schema = laravel and table_name = migrations and table_type = 'BASE TABLE')
```

## 宿主机

* WSL2项目文件路径 \\wsl.localhost\OracleLinux_8_5
* WSL2项目文件路径 \\wsl.localhost\Ubuntu-22.04\home
