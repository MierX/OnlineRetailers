use `or`;
set names utf8;

drop table if exists goods;
create table goods
(
    id                 int unsigned     not null auto_increment comment 'Id',
    goods_name         varchar(150)     not null comment '商品名称',
    market_price       decimal(10, 2)   not null default 0.00 comment '市场价格',
    shop_price         decimal(10, 2)   not null default 0.00 comment '本店价格',
    goods_desc         longtext comment '商品描述',
    is_on_sale         tinyint unsigned not null default 0 comment '是否上架：0是 1否',
    is_delete          tinyint unsigned not null default 0 comment '是否放到回收站: 0否 1是',
    addtime            datetime         not null comment '添加时间',
    logo               varchar(150)     not null default '' comment '原图',
    sm_logo            varchar(150)     not null default '' comment '小图',
    mid_logo           varchar(150)     not null default '' comment '中图',
    big_logo           varchar(150)     not null default '' comment '大图',
    mbig_logo          varchar(150)     not null default '' comment '更大图',
    brand_id           int unsigned     not null default 0 comment '品牌id',
    cat_id             int unsigned     not null default 0 comment '主分类Id',
    type_id            int unsigned     not null default 0 comment '类型Id',
    promote_price      decimal(10, 2)   not null default 0.00 comment '促销价格',
    promote_start_date datetime         not null default '0000-00-00 00:00:00' comment '促销开始时间',
    promote_end_date   datetime         not null default '0000-00-00 00:00:00' comment '促销结束时间',
    is_new             tinyint unsigned not null default 0 comment '是否新品：0否 1是',
    is_hot             tinyint unsigned not null default 0 comment '是否热卖：0否 1是',
    is_best            tinyint unsigned not null default 0 comment '是否精品：0否 1是',
    is_floor           tinyint unsigned not null default 0 comment '是否推荐楼层：0否 1是',
    sort_num           tinyint unsigned not null default 100 comment '排序的数字',
    is_updated         tinyint unsigned not null default 0 comment '是否被修改',
    c_time             datetime         not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time             datetime         not null default '0000-00-00 00:00:00' comment '修改时间',
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
    id         int unsigned not null auto_increment comment 'Id',
    brand_name varchar(30)  not null comment '品牌名称',
    site_url   varchar(150) not null default '' comment '官方网址',
    logo       varchar(150) not null default '' comment '品牌Logo图片',
    c_time     datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time     datetime     not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id)
) engine = InnoDB
  default charset = utf8 comment '品牌';

drop table if exists member_level;
create table member_level
(
    id           int unsigned not null auto_increment comment 'Id',
    level_name   varchar(30)  not null comment '级别名称',
    jifen_bottom int unsigned not null comment '积分下限',
    jifen_top    int unsigned not null comment '积分上限',
    c_time       datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time       datetime     not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id)
) engine = InnoDB
  default charset = utf8 comment '会员级别';

drop table if exists member_price;
create table member_price
(
    price    decimal(10, 2) not null comment '会员价格',
    level_id int unsigned   not null comment '级别Id',
    goods_id int unsigned   not null comment '商品Id',
    c_time   datetime       not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time   datetime       not null default '0000-00-00 00:00:00' comment '修改时间',
    key level_id (level_id),
    key goods_id (goods_id)
) engine = InnoDB
  default charset = utf8 comment '会员价格';

drop table if exists goods_pic;
create table goods_pic
(
    id       int unsigned not null auto_increment comment 'Id',
    pic      varchar(150) not null comment '原图',
    sm_pic   varchar(150) not null comment '小图',
    mid_pic  varchar(150) not null comment '中图',
    big_pic  varchar(150) not null comment '大图',
    goods_id int unsigned not null comment '商品Id',
    c_time   datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time   datetime     not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id),
    key goods_id (goods_id)
) engine = InnoDB
  default charset = utf8 comment '商品相册';

drop table if exists category;
create table category
(
    id        int unsigned     not null auto_increment comment 'Id',
    cat_name  varchar(30)      not null comment '分类名称',
    parent_id int unsigned     not null default 0 comment '上级分类的Id,0:顶级分类',
    is_floor  tinyint unsigned not null default 0 comment '是否推荐楼层：0否 1是',
    c_time    datetime         not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time    datetime         not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id)
) engine = InnoDB
  default charset = utf8 comment '分类';

drop table if exists goods_cat;
create table goods_cat
(
    cat_id   int unsigned not null comment '分类id',
    goods_id int unsigned not null comment '商品Id',
    c_time   datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time   datetime     not null default '0000-00-00 00:00:00' comment '修改时间',
    key goods_id (goods_id),
    key cat_id (cat_id)
) engine = InnoDB
  default charset = utf8 comment '商品扩展分类';

/****************************** 属性相关表 ****************************************/
drop table if exists type;
create table type
(
    id        int unsigned not null auto_increment comment 'Id',
    type_name varchar(30)  not null comment '类型名称',
    c_time    datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time    datetime     not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id)
) engine = InnoDB
  default charset = utf8 comment '类型';

drop table if exists attribute;
create table attribute
(
    id                 int unsigned     not null auto_increment comment 'Id',
    attr_name          varchar(30)      not null comment '属性名称',
    attr_type          tinyint unsigned not null default 1 comment '属性类型：1唯一 2可选',
    attr_option_values varchar(300)     not null default '' comment '属性可选值',
    type_id            int unsigned     not null comment '所属类型Id',
    c_time             datetime         not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time             datetime         not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id),
    key type_id (type_id)
) engine = InnoDB
  default charset = utf8 comment '属性表';

drop table if exists goods_attr;
create table goods_attr
(
    id         int unsigned not null auto_increment comment 'Id',
    attr_value varchar(150) not null default '' comment '属性值',
    attr_id    int unsigned not null comment '属性Id',
    goods_id   int unsigned not null comment '商品Id',
    c_time     datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time     datetime     not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id),
    key goods_id (goods_id),
    key attr_id (attr_id)
) engine = InnoDB
  default charset = utf8 comment '商品属性';

drop table if exists goods_number;
create table goods_number
(
    goods_id      int unsigned not null comment '商品Id',
    goods_number  int unsigned not null default 0 comment '库存量',
    goods_attr_id varchar(150) not null comment '商品属性表的ID,如果有多个，就用程序拼成字符串存到这个字段中',
    c_time        datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time        datetime     not null default '0000-00-00 00:00:00' comment '修改时间',
    key goods_id (goods_id)
) engine = InnoDB
  default charset = utf8 comment '库存量';

/*********************** RBAC ***********************************/

drop table if exists privilege;
create table privilege
(
    id              int unsigned not null auto_increment comment 'Id',
    pri_name        varchar(30)  not null comment '权限名称',
    module_name     varchar(30)  not null default '' comment '模块名称',
    controller_name varchar(30)  not null default '' comment '控制器名称',
    action_name     varchar(30)  not null default '' comment '方法名称',
    parent_id       int unsigned not null default 0 comment '上级权限Id',
    c_time          datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time          datetime     not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id)
) engine = InnoDB
  default charset = utf8 comment '权限';

drop table if exists role_pri;
create table role_pri
(
    pri_id  int unsigned not null comment '权限id',
    role_id int unsigned not null comment '角色id',
    c_time  datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time  datetime     not null default '0000-00-00 00:00:00' comment '修改时间',
    key pri_id (pri_id),
    key role_id (role_id)
) engine = InnoDB
  default charset = utf8 comment '角色权限';

drop table if exists role;
create table role
(
    id        int unsigned not null auto_increment comment 'Id',
    role_name varchar(30)  not null comment '角色名称',
    c_time    datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time    datetime     not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id)
) engine = InnoDB
  default charset = utf8 comment '角色';

drop table if exists admin_role;
create table admin_role
(
    admin_id int unsigned not null comment '管理员id',
    role_id  int unsigned not null comment '角色id',
    c_time   datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time   datetime     not null default '0000-00-00 00:00:00' comment '修改时间',
    key admin_id (admin_id),
    key role_id (role_id)
) engine = InnoDB
  default charset = utf8 comment '管理员角色';

drop table if exists admin;
create table admin
(
    id       int unsigned not null auto_increment comment 'Id',
    username varchar(30)  not null comment '用户名',
    password char(32)     not null comment '密码',
    c_time   datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time   datetime     not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id)
) engine = InnoDB
  default charset = utf8 comment '管理员';

INSERT INTO admin(id, username, password)
VALUES (1, 'root', '63a9f0ea7bb98050796b649e85481845');

drop table if exists member;
create table member
(
    id       int unsigned not null auto_increment comment 'Id',
    username varchar(30)  not null comment '用户名',
    password char(32)     not null comment '密码',
    face     varchar(150) not null default '' comment '头像',
    jifen    int unsigned not null default 0 comment '积分',
    c_time   datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time   datetime     not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id)
) engine = InnoDB
  default charset = utf8 comment '会员';

drop table if exists cart;
create table cart
(
    id            int unsigned not null auto_increment comment 'Id',
    goods_id      int unsigned not null comment '商品Id',
    goods_attr_id varchar(150) not null default '' comment '商品属性Id',
    goods_number  int unsigned not null comment '购买的数量',
    member_id     int unsigned not null comment '会员Id',
    c_time        datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time        datetime     not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id),
    key member_id (member_id)
) engine = InnoDB
  default charset = utf8 comment '购物车';

drop table if exists `order`;
create table `order`
(
    id           int unsigned     not null auto_increment comment 'Id',
    member_id    int unsigned     not null comment '会员Id',
    addtime      int unsigned     not null comment '下单时间',
    pay_status   tinyint unsigned not null default 0 comment '支付状态：0否 1是',
    pay_time     datetime         not null default '0000-00-00 00:00:00' comment '支付时间',
    total_price  decimal(10, 2)   not null comment '定单总价',
    shr_name     varchar(30)      not null comment '收货人姓名',
    shr_tel      varchar(30)      not null comment '收货人电话',
    shr_province varchar(30)      not null comment '收货人省',
    shr_city     varchar(30)      not null comment '收货人城市',
    shr_area     varchar(30)      not null comment '收货人地区',
    shr_address  varchar(30)      not null comment '收货人详细地址',
    post_status  tinyint unsigned not null default '0' comment '发货状态,0:未发货,1:已发货2:已收到货',
    post_number  varchar(30)      not null default '' comment '快递号',
    c_time       datetime         not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time       datetime         not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id),
    key member_id (member_id),
    key addtime (addtime)
) engine = InnoDB
  default charset = utf8 comment '定单基本信息';

drop table if exists order_goods;
create table order_goods
(
    id            int unsigned   not null auto_increment comment 'Id',
    order_id      int unsigned   not null comment '定单Id',
    goods_id      int unsigned   not null comment '商品Id',
    goods_attr_id varchar(150)   not null default '' comment '商品属性id',
    goods_number  int unsigned   not null comment '购买的数量',
    price         decimal(10, 2) not null comment '购买的价格',
    c_time        datetime       not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time        datetime       not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id),
    key order_id (order_id),
    key goods_id (goods_id)
) engine = InnoDB
  default charset = utf8 comment '定单商品表';

drop table if exists comment;
create table comment
(
    id          int unsigned      not null auto_increment comment 'Id',
    goods_id    int unsigned      not null comment '商品Id',
    member_id   int unsigned      not null comment '会员Id',
    content     varchar(200)      not null comment '内容',
    addtime     datetime          not null comment '发表时间',
    star        tinyint unsigned  not null comment '分值',
    click_count smallint unsigned not null default '0' comment '有用的数字',
    c_time      datetime          not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time      datetime          not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id),
    key goods_id (goods_id)
) engine = InnoDB
  default charset = utf8 comment '评论';

drop table if exists comment_reply;
create table comment_reply
(
    id         int unsigned not null auto_increment comment 'Id',
    comment_id int unsigned not null comment '评论Id',
    member_id  int unsigned not null comment '会员Id',
    content    varchar(200) not null comment '内容',
    addtime    datetime     not null comment '发表时间',
    c_time     datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time     datetime     not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id),
    key comment_id (comment_id)
) engine = InnoDB
  default charset = utf8 comment '评论回复';

drop table if exists yinxiang;
create table yinxiang
(
    id       int unsigned      not null auto_increment comment 'Id',
    goods_id int unsigned      not null comment '商品Id',
    yx_name  varchar(30)       not null comment '印象名称',
    yx_count smallint unsigned not null default '1' comment '印象的次数',
    c_time   datetime          not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time   datetime          not null default '0000-00-00 00:00:00' comment '修改时间',
    primary key (id),
    key goods_id (goods_id)
) engine = InnoDB
  default charset = utf8 comment '印象';

drop table if exists sphinx_id;
create table sphinx_id
(
    id     int unsigned not null default '0' comment '已经索引好索引的最后一件商品的ID',
    c_time datetime     not null default '0000-00-00 00:00:00' comment '添加时间',
    u_time datetime     not null default '0000-00-00 00:00:00' comment '修改时间'
) engine = InnoDB
  default charset = utf8 comment 'sphinx';
INSERT INTO sphinx_id
VALUES (0, '', '');