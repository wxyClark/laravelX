# 开发流程

## migration

* 指定数据结构：字段、类型、索引

```bash
# 创建迁移文件
php artisan make:migration TBALE_NAME --path=/database/migrations/DIR_NAME

# 执行迁移文件
php artisan migrate --path=/database/migrations/AbcDemo

# 回滚
php artisan migrate:rollback --step=5 --path=/database/migrations/AbcDemo
```

## Model

* 指定数据表：表名

```bash
# 创建迁移文件
php artisan make:Model DIR_NAME/MODEL_NAME 
```

## Repository

## Service

```danger
基于一份数据取另一个份数据时，判定基础数据是否为空，避免扩展数据取全量
```

## Controller

## Middleware
