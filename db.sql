CREATE DATABASE online_retailers;
USE online_retailers;
SET NAMES utf8;
DROP TABLE IF EXISTS goods;
CREATE TABLE goods(
    id mediumint unsigned not null auto_increment comment 'ID',
    goods_name varchar(150) not null comment '商品名称',
    market_price decimal(10,2) not null  comment '市场价格',
    shop_price decimal(10,2) not null comment '本店价格',
    is_on_sale enum('是', '否') not null  default '是' comment '是否上架',
    is_delete enum('是', '否') not null default '否' comment '是否放到回收站',
    addtime datetime not null comment '添加时间',
    primary key (id),
    key shop_price(shop_price),
    key addtime(addtime),
    key is_on_sale(is_on_sale)
)engine=InnoDB default charset=utf8 comment '商品表';