# CREATE DATABASE online_retailers;
USE online_retailers;
SET NAMES utf8;

# DROP TABLE IF EXISTS goods;
# CREATE TABLE goods
# (
#     id           mediumint unsigned not null auto_increment comment 'ID',
#     goods_name   varchar(150)       not null comment '商品名称',
#     market_price decimal(10, 2)     not null comment '市场价格',
#     shop_price   decimal(10, 2)     not null comment '本店价格',
#     goods_desc   longtext comment '商品描述',
#     is_on_sale   enum ('是', '否')    not null default '是' comment '是否上架',
#     is_delete    enum ('是', '否')    not null default '否' comment '是否放到回收站',
#     addtime      datetime           not null default CURRENT_TIMESTAMP comment '添加时间',
#     logo         varchar(150)       not null default '' comment '原图',
#     sm_logo      varchar(150)       not null default '' comment '小图',
#     mid_logo     varchar(150)       not null default '' comment '中图',
#     big_logo     varchar(150)       not null default '' comment '大图',
#     mbig_logo    varchar(150)       not null default '' comment '更大图',
#     brand_id     mediumint unsigned not null default '0' comment '品牌id',
#     primary key (id),
#     key shop_price (shop_price),
#     key addtime (addtime),
#     key brand_id (brand_id),
#     key is_on_sale (is_on_sale)
# ) engine = InnoDB
#   default charset = utf8 comment '商品表';
#
# DROP TABLE IF EXISTS brands;
# CREATE TABLE brands
# (
#     id         mediumint unsigned not null auto_increment comment 'ID',
#     brand_name varchar(150)       not null comment '品牌名称',
#     brand_desc longtext comment '品牌描述',
#     site_url   varchar(150)       not null default '' comment '官方网址',
#     logo       varchar(150)       not null default '' comment '品牌图片',
#     sm_logo    varchar(150)       not null default '' comment '品牌图片小图',
#     mid_logo   varchar(150)       not null default '' comment '品牌图片中图',
#     big_logo   varchar(150)       not null default '' comment '品牌图片大图',
#     mbig_logo  varchar(150)       not null default '' comment '品牌图片超大图',
#     addtime    datetime           not null default CURRENT_TIMESTAMP comment '添加时间',
#     primary key (id)
# ) engine = InnoDB
#   default charset = utf8 comment '品牌表';
#
# DROP TABLE IF EXISTS member_level;
# CREATE TABLE member_level
# (
#     id            mediumint unsigned not null auto_increment comment 'id',
#     level_name    varchar(30)        not null comment '级别名称',
#     integral_down mediumint unsigned not null comment '积分下限',
#     integral_up   mediumint unsigned not null comment '积分上限',
#     addtime       datetime           not null default CURRENT_TIMESTAMP comment '添加时间',
#     primary key (id)
# ) engine = InnoDB
#   default charset = utf8 comment '会员级别';
#
# DROP TABLE IF EXISTS member_price;
# CREATE TABLE member_price
# (
#     id       mediumint unsigned      not null auto_increment comment 'id',
#     price    decimal(10, 2) unsigned not null comment '价格',
#     level_id mediumint unsigned      not null comment '级别id',
#     goods_id mediumint unsigned      not null comment '商品id',
#     addtime  datetime                not null default CURRENT_TIMESTAMP comment '创建时间',
#     primary key (id)
# ) engine = InnoDB
#   default charset = utf8 comment '会员价格';