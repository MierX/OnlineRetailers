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
#     cat_id       mediumint unsigned not null default '0' comment '主分类id',
#     type_id      mediumint unsigned not null default '0' comment '类型id',
#     primary key (id),
#     key shop_price (shop_price),
#     key addtime (addtime),
#     key brand_id (brand_id),
#     key brand_id (cat_id),
#     key is_on_sale (is_on_sale),
#     key type_id (type_id)
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
#
# DROP TABLE IF EXISTS category;
# CREATE TABLE category
# (
#     id      mediumint unsigned not null auto_increment comment 'id',
#     name    varchar(30)        not null default '' comment '分类名称',
#     pid     mediumint          not null default '0' comment '上级分类',
#     addtime datetime           not null default CURRENT_TIMESTAMP comment '创建时间',
#     primary key (id)
# ) engine = InnoDB
#   default charset = utf8 comment '商品分类';
#
# DROP TABLE IF EXISTS goods_cat;
# CREATE TABLE goods_cat
# (
#     id       mediumint unsigned not null auto_increment comment 'id',
#     cat_id   mediumint unsigned not null comment '分类id',
#     goods_id mediumint unsigned not null comment '商品id',
#     addtime  datetime           not null default CURRENT_TIMESTAMP comment '创建时间',
#     primary key (id),
#     key goods_id (goods_id),
#     key cat_id (cat_id)
# ) engine = InnoDB
#   default charset = utf8 comment '商品扩展分类';
#
# DROP TABLE IF EXISTS type;
# CREATE TABLE type
# (
#     id        mediumint unsigned not null auto_increment comment 'id',
#     type_name varchar(30)        not null comment '类型名称',
#     addtime   datetime           not null default CURRENT_TIMESTAMP comment '创建时间',
#     primary key (id)
# ) engine = InnoDB
#   default charset = utf8 comment '商品类型';
#
# DROP TABLE IF EXISTS attribute;
# CREATE TABLE attribute
# (
#     id          mediumint unsigned not null auto_increment comment 'id',
#     attr_name   varchar(30)        not null comment '属性名称',
#     attr_type   enum ('唯一', '可选')  not null comment '属性类型',
#     attr_values varchar(300)       not null default '' comment '属性值',
#     type_id     mediumint unsigned not null comment '类型id',
#     addtime     datetime           not null default CURRENT_TIMESTAMP comment '创建时间',
#     primary key (id),
#     key type_id (type_id)
# ) engine = InnoDB
#   default charset = utf8 comment '类型属性';
#
# DROP TABLE IF EXISTS goods_attribute;
# CREATE TABLE goods_attribute
# (
#     id          mediumint unsigned not null auto_increment comment 'id',
#     attr_id     mediumint unsigned not null comment '属性id',
#     attr_values varchar(150)       not null default '' comment '属性值',
#     goods_id    mediumint unsigned not null comment '商品id',
#     addtime     datetime           not null default CURRENT_TIMESTAMP comment '创建时间',
#     primary key (id),
#     key attr_id (attr_id),
#     key goods_id (goods_id)
# ) engine = InnoDB
#   default charset = utf8 comment '商品属性';
#
# DROP TABLE IF EXISTS goods_inventory;
# CREATE TABLE goods_inventory
# (
#     id          mediumint unsigned not null auto_increment comment 'id',
#     goods_id    mediumint unsigned not null comment '商品id',
#     number      mediumint unsigned not null default 0 comment '库存量',
#     attr_id     varchar(150) not null comment '属性id，有多个则会用逗号隔开',
#     addtime     datetime           not null default CURRENT_TIMESTAMP comment '创建时间',
#     primary key (id),
#     key goods_id (goods_id)
# ) engine = InnoDB
#   default charset = utf8 comment '商品属性';
#
# DROP TABLE IF EXISTS privilege;
# CREATE TABLE privilege
# (
#     id              mediumint unsigned not null auto_increment comment 'id',
#     pri_name        varchar(150)       not null comment '权限名称',
#     module_name     varchar(30)        not null default '' comment '模块名称',
#     controller_name varchar(30)        not null default '' comment '控制器名称',
#     action_name     varchar(30)        not null default '' comment '方法名称',
#     parent_id       mediumint unsigned not null default 0 comment '上级权限id',
#     addtime         datetime           not null default CURRENT_TIMESTAMP comment '创建时间',
#     primary key (id)
# ) engine = InnoDB
#   default charset = utf8 comment '管理权限';
#
# DROP TABLE IF EXISTS role;
# CREATE TABLE role
# (
#     id        mediumint unsigned not null auto_increment comment 'id',
#     role_name varchar(150)       not null comment '角色名称',
#     addtime   datetime           not null default CURRENT_TIMESTAMP comment '创建时间',
#     primary key (id)
# ) engine = InnoDB
#   default charset = utf8 comment '管理角色';
#
# DROP TABLE IF EXISTS admin;
# CREATE TABLE admin
# (
#     id       mediumint unsigned not null auto_increment comment 'id',
#     username varchar(150)       not null comment '用户名',
#     password char(32)           not null comment '密码',
#     addtime  datetime           not null default CURRENT_TIMESTAMP comment '创建时间',
#     primary key (id)
# ) engine = InnoDB
#   default charset = utf8 comment '管理员';
# INSERT INTO admin(username, password)
# VALUES ('root', MD5('admin'));
#
# DROP TABLE IF EXISTS role_pri;
# CREATE TABLE role_pri
# (
#     id      mediumint unsigned not null auto_increment comment 'id',
#     pri_id  mediumint unsigned not null comment '权限id',
#     role_id mediumint unsigned not null comment '角色id',
#     addtime datetime           not null default CURRENT_TIMESTAMP comment '创建时间',
#     primary key (id),
#     key role_id (role_id),
#     key pri_id (pri_id)
# ) engine = InnoDB
#   default charset = utf8 comment '角色权限';
#
# DROP TABLE IF EXISTS admin_role;
# CREATE TABLE admin_role
# (
#     id       mediumint unsigned not null auto_increment comment 'id',
#     admin_id mediumint unsigned not null comment '管理员id',
#     role_id  mediumint unsigned not null comment '角色id',
#     addtime  datetime           not null default CURRENT_TIMESTAMP comment '创建时间',
#     primary key (id),
#     key role_id (role_id),
#     key admin_id (admin_id)
# ) engine = InnoDB
#   default charset = utf8 comment '管理员角色';