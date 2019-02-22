# 省市区编码表

[来源](https://lbs.amap.com/api/webservice/download)

# 使用方式
### 迁移数据库
```
php artisan migrate
```
### 执行命令
```
php artisan region:generate
```

- 从高德下载地址编码压缩包
- 解压
- 检查是否存在regions表以及是否存在数据
- 加载解压出的excel
- 写入数据库

# todo
- 自定义表名称
