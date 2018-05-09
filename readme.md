本框架包含以下组建包：

1.filp/whoops 错误提示组建包
2.Twig 模板引擎
开发者手册见下：
https://twig.symfony.com/doc/2.x/api.html
3.  laravel框架 blade 模板引擎独立版 jenssegers/blade
    https://packagist.org/packages/jenssegers/blade
4.Macaw  路由组建包的载入

5.增加 Guzzle
Guzzle is a PHP HTTP client that makes it easy to send HTTP requests and trivial to integrate with web services

6.增加输入验证类 Respect/Validation 用于校验输入 2018-1-5
    https://github.com/Respect/Validation/blob/ee9e69776a8e875ac1b5b00d8cf2d2c801c42181/docs/README.md    
  不同数据类型的验证查看如下连接：
    https://github.com/Respect/Validation/blob/ee9e69776a8e875ac1b5b00d8cf2d2c801c42181/docs/VALIDATORS.md
7.增加  vlucas 组件，可以如同laravel 一样使用env（）函数 加载.env文件，并且读取配置文件，使得 多环境开发环境配置变得 easy
8.增加env文件，作为demo，实际使用的时候请重命名env 为.env  文件，配置会优先加载.env文件
9.增加 monolog/monolog  日志组件 ，增加日志处理相关二次封装辅助函数。