# create database online_retailers;
use online_retailers;
set names utf8;

drop table if exists goods;
create table goods
(
    id                 mediumint unsigned not null auto_increment comment 'Id',
    goods_name         varchar(150)       not null comment '商品名称',
    market_price       decimal(10, 2)     not null comment '市场价格',
    shop_price         decimal(10, 2)     not null comment '本店价格',
    goods_desc         longtext comment '商品描述',
    is_on_sale         enum ('是','否')     not null default '是' comment '是否上架',
    is_delete          enum ('是','否')     not null default '否' comment '是否放到回收站',
    logo               varchar(150)       not null default '' comment '原图',
    sm_logo            varchar(150)       not null default '' comment '小图',
    mid_logo           varchar(150)       not null default '' comment '中图',
    big_logo           varchar(150)       not null default '' comment '大图',
    mbig_logo          varchar(150)       not null default '' comment '更大图',
    brand_id           mediumint unsigned not null default '0' comment '品牌id',
    cat_id             mediumint unsigned not null default '0' comment '主分类Id',
    type_id            mediumint unsigned not null default '0' comment '类型Id',
    promote_price      decimal(10, 2)     not null default '0.00' comment '促销价格',
    promote_start_date datetime           not null comment '促销开始时间',
    promote_end_date   datetime           not null comment '促销结束时间',
    is_new             enum ('是','否')     not null default '否' comment '是否新品',
    is_hot             enum ('是','否')     not null default '否' comment '是否热卖',
    is_best            enum ('是','否')     not null default '否' comment '是否精品',
    is_floor           enum ('是','否')     not null default '否' comment '是否推荐楼层',
    sort_num           tinyint unsigned   not null default '100' comment '排序的数字',
    addtime            datetime                    default CURRENT_TIMESTAMP not null comment '添加时间',
    primary key (id),
    key promote_price (promote_price),
    key promote_start_date (promote_start_date),
    key promote_end_date (promote_end_date),
    key is_new (is_new),
    key is_hot (is_hot),
    key is_best (is_best),
    key shop_price (shop_price),
    key addtime (addtime),
    key brand_id (brand_id),
    key cat_id (cat_id),
    key sort_num (sort_num),
    key is_on_sale (is_on_sale)
) engine = InnoDB
  default charset = utf8 comment '商品';

drop table if exists brand;
create table brand
(
    id         mediumint unsigned not null auto_increment comment 'Id',
    brand_name varchar(30)        not null comment '品牌名称',
    site_url   varchar(150)       not null default '' comment '官方网址',
    logo       varchar(150)       not null default '' comment '品牌Logo图片',
    addtime    datetime                    default CURRENT_TIMESTAMP not null comment '添加时间',
    primary key (id)
) engine = InnoDB
  default charset = utf8 comment '品牌';

drop table if exists member_level;
create table member_level
(
    id           mediumint unsigned                 not null auto_increment comment 'Id',
    level_name   varchar(30)                        not null comment '级别名称',
    jifen_bottom mediumint unsigned                 not null comment '积分下限',
    jifen_top    mediumint unsigned                 not null comment '积分上限',
    addtime      datetime default CURRENT_TIMESTAMP not null comment '添加时间',
    primary key (id)
) engine = InnoDB
  default charset = utf8 comment '会员级别';

drop table if exists member_price;
create table member_price
(
    price    decimal(10, 2)                     not null comment '会员价格',
    level_id mediumint unsigned                 not null comment '级别Id',
    goods_id mediumint unsigned                 not null comment '商品Id',
    addtime  datetime default CURRENT_TIMESTAMP not null comment '添加时间',
    key level_id (level_id),
    key goods_id (goods_id)
) engine = InnoDB
  default charset = utf8 comment '会员价格';

drop table if exists goods_pic;
create table goods_pic
(
    id       mediumint unsigned                 not null auto_increment comment 'Id',
    pic      varchar(150)                       not null comment '原图',
    sm_pic   varchar(150)                       not null comment '小图',
    mid_pic  varchar(150)                       not null comment '中图',
    big_pic  varchar(150)                       not null comment '大图',
    goods_id mediumint unsigned                 not null comment '商品Id',
    addtime  datetime default CURRENT_TIMESTAMP not null comment '添加时间',
    primary key (id),
    key goods_id (goods_id)
) engine = InnoDB
  default charset = utf8 comment '商品相册';

drop table if exists category;
create table category
(
    id        mediumint unsigned not null auto_increment comment 'Id',
    cat_name  varchar(30)        not null comment '分类名称',
    parent_id mediumint unsigned not null default '0' comment '上级分类的Id,0:顶级分类',
    is_floor  enum ('是','否')     not null default '否' comment '是否推荐楼层',
    addtime   datetime                    default CURRENT_TIMESTAMP not null comment '添加时间',
    primary key (id)
) engine = InnoDB
  default charset = utf8 comment '分类';

drop table if exists goods_cat;
create table goods_cat
(
    cat_id   mediumint unsigned                 not null comment '分类id',
    goods_id mediumint unsigned                 not null comment '商品Id',
    addtime  datetime default CURRENT_TIMESTAMP not null comment '添加时间',
    key goods_id (goods_id),
    key cat_id (cat_id)
) engine = InnoDB
  default charset = utf8 comment '商品扩展分类';

drop table if exists type;
create table type
(
    id        mediumint unsigned                 not null auto_increment comment 'Id',
    type_name varchar(30)                        not null comment '类型名称',
    addtime   datetime default CURRENT_TIMESTAMP not null comment '添加时间',
    primary key (id)
) engine = InnoDB
  default charset = utf8 comment '类型';

drop table if exists attribute;
create table attribute
(
    id                 mediumint unsigned not null auto_increment comment 'Id',
    attr_name          varchar(30)        not null comment '属性名称',
    attr_type          enum ('唯一','可选')   not null comment '属性类型',
    attr_option_values varchar(300)       not null default '' comment '属性可选值',
    type_id            mediumint unsigned not null comment '所属类型Id',
    addtime            datetime                    default CURRENT_TIMESTAMP not null comment '添加时间',
    primary key (id),
    key type_id (type_id)
) engine = InnoDB
  default charset = utf8 comment '属性表';

drop table if exists goods_attr;
create table goods_attr
(
    id         mediumint unsigned not null auto_increment comment 'Id',
    attr_value varchar(150)       not null default '' comment '属性值',
    attr_id    mediumint unsigned not null comment '属性Id',
    goods_id   mediumint unsigned not null comment '商品Id',
    addtime    datetime                    default CURRENT_TIMESTAMP not null comment '添加时间',
    primary key (id),
    key goods_id (goods_id),
    key attr_id (attr_id)
) engine = InnoDB
  default charset = utf8 comment '商品属性';

drop table if exists goods_number;
create table goods_number
(
    goods_id      mediumint unsigned not null comment '商品Id',
    goods_number  mediumint unsigned not null default '0' comment '库存量',
    goods_attr_id varchar(150)       not null comment '商品属性表的ID,如果有多个，就用程序拼成字符串存到这个字段中',
    addtime       datetime                    default CURRENT_TIMESTAMP not null comment '添加时间',
    key goods_id (goods_id)
) engine = InnoDB
  default charset = utf8 comment '库存量';

/*********************** RBAC ***********************************/

drop table if exists privilege;
create table privilege
(
    id              mediumint unsigned not null auto_increment comment 'Id',
    pri_name        varchar(30)        not null comment '权限名称',
    module_name     varchar(30)        not null default '' comment '模块名称',
    controller_name varchar(30)        not null default '' comment '控制器名称',
    action_name     varchar(30)        not null default '' comment '方法名称',
    parent_id       mediumint unsigned not null default '0' comment '上级权限Id',
    addtime         datetime                    default CURRENT_TIMESTAMP not null comment '添加时间',
    primary key (id)
) engine = InnoDB
  default charset = utf8 comment '权限';

drop table if exists role_pri;
create table role_pri
(
    pri_id  mediumint unsigned                 not null comment '权限id',
    role_id mediumint unsigned                 not null comment '角色id',
    addtime datetime default CURRENT_TIMESTAMP not null comment '添加时间',
    key pri_id (pri_id),
    key role_id (role_id)
) engine = InnoDB
  default charset = utf8 comment '角色权限';

drop table if exists role;
create table role
(
    id        mediumint unsigned                 not null auto_increment comment 'Id',
    role_name varchar(30)                        not null comment '角色名称',
    addtime   datetime default CURRENT_TIMESTAMP not null comment '添加时间',
    primary key (id)
) engine = InnoDB
  default charset = utf8 comment '角色';

drop table if exists admin_role;
create table admin_role
(
    admin_id mediumint unsigned                 not null comment '管理员id',
    role_id  mediumint unsigned                 not null comment '角色id',
    addtime  datetime default CURRENT_TIMESTAMP not null comment '添加时间',
    key admin_id (admin_id),
    key role_id (role_id)
) engine = InnoDB
  default charset = utf8 comment '管理员角色';

drop table if exists admin;
create table admin
(
    id       mediumint unsigned                 not null auto_increment comment 'Id',
    username varchar(30)                        not null comment '用户名',
    password char(32)                           not null comment '密码',
    addtime  datetime default CURRENT_TIMESTAMP not null comment '添加时间',
    primary key (id)
) engine = InnoDB
  default charset = utf8 comment '管理员';
INSERT INTO admin(id, username, password)
VALUES (1, 'root', '21232f297a57a5a743894a0e4a801fc3');
