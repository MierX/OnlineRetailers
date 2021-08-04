# CREATE DATABASE online_retailers;
USE online_retailers;
SET NAMES utf8;

-- DROP TABLE IF EXISTS goods;
-- CREATE TABLE goods(
--     id mediumint unsigned not null auto_increment comment 'ID',
--     goods_name varchar(150) not null comment '商品名称',
--     market_price decimal(10,2) not null  comment '市场价格',
--     shop_price decimal(10,2) not null comment '本店价格',
--     is_on_sale enum('是', '否') not null  default '是' comment '是否上架',
--     is_delete enum('是', '否') not null default '否' comment '是否放到回收站',
--     addtime datetime not null comment '添加时间',
--     primary key (id),
--     key shop_price(shop_price),
--     key addtime(addtime),
--     key is_on_sale(is_on_sale)
-- )engine=InnoDB default charset=utf8 comment '商品表';

DROP TABLE IF EXISTS brands;
CREATE TABLE brands(
                      id mediumint unsigned not null auto_increment comment 'ID',
                      brand_name varchar(150) not null comment '商品名称',
                      site_url varchar(150) not null default '' comment '官方网址',
                      logo varchar(150) not null default '' comment '品牌图片',
                      sm_logo varchar(150) not null default '' comment '品牌图片小图',
                      mid_logo varchar(150) not null default '' comment '品牌图片中图',
                      big_logo varchar(150) not null default '' comment '品牌图片大图',
                      mbig_logo varchar(150) not null default '' comment '品牌图片超大图',
                      addtime datetime not null comment '添加时间',
                      primary key (id)
)engine=InnoDB default charset=utf8 comment '品牌表';