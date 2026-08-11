# LanzouAPI‑WebUI

本项目基于 [hanximeng/LanzouAPI](https://github.com/hanximeng/LanzouAPI)，
在原有项目纯后端 php 源码的基础上，编写了简单易懂的前端界面，并且整合在一起，开箱即用。

## 使用说明
将源码一起复制到你的网站服务器目录下。
网站需要 PHP 环境，才能正常调用 LanzouAPI。

> 前端界面由 HTML + JavaScript 编写，无第三方依赖。

## 环境要求
- PHP 服务环境
- PHP curl 扩展

## 接口
接口地址：`/api/index.php`

| 参数 | 说明 |
| ---- | ---- |
| url | 蓝奏云分享链接 |
| pwd | 分享密码，无密码留空 |
| type=down | 设置该参数直接触发下载 |

## 致谢
后端源码：[hanximeng/LanzouAPI](https://github.com/hanximeng/LanzouAPI)

## License
MIT
