/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50721
Source Host           : 127.0.0.1:3306
Source Database       : laravel

Target Server Type    : MYSQL
Target Server Version : 50721
File Encoding         : 65001

Date: 2018-09-07 08:38:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品名称',
  `volume` int(11) NOT NULL DEFAULT '0' COMMENT '分类容量',
  `position` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='分类表';

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES ('1', '茶底', '250', '1', '2018-08-23 16:10:12', '2018-08-23 16:10:14');
INSERT INTO `categories` VALUES ('2', '奶品', '250', '2', '2018-08-23 16:10:37', '2018-08-23 16:10:35');
INSERT INTO `categories` VALUES ('3', '其他', '250', '3', '2018-09-02 23:02:14', '2018-09-02 23:02:16');
INSERT INTO `categories` VALUES ('4', '一级可选', '50', '4', '2018-09-02 23:02:14', '2018-09-02 23:02:16');
INSERT INTO `categories` VALUES ('5', '二级品类', '150', '5', '2018-09-02 23:02:14', '2018-09-02 23:02:16');
INSERT INTO `categories` VALUES ('6', '二级可选', '0', '6', '2018-09-02 23:02:14', '2018-09-02 23:02:16');
INSERT INTO `categories` VALUES ('7', '三级品类', '0', '7', '2018-09-02 23:02:14', '2018-09-02 23:02:16');
INSERT INTO `categories` VALUES ('8', '四级品类', '25', '8', '2018-09-02 23:02:14', '2018-09-02 23:02:16');
INSERT INTO `categories` VALUES ('9', '五级品类', '50', '9', '2018-09-02 23:02:14', '2018-09-02 23:02:16');
INSERT INTO `categories` VALUES ('10', '奶盖撒料', '0', '10', '2018-09-02 23:02:14', '2018-09-02 23:02:16');

-- ----------------------------
-- Table structure for configs
-- ----------------------------
DROP TABLE IF EXISTS `configs`;
CREATE TABLE `configs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'J-Admin',
  `keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'http://',
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `statistics` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of configs
-- ----------------------------
INSERT INTO `configs` VALUES ('1', 'J-admin', null, null, 'http:://localhost', null, '1', null, null, null);

-- ----------------------------
-- Table structure for coupons
-- ----------------------------
DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL COMMENT '店铺ID',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '标题',
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片',
  `condition` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '使用条件',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '发放数量',
  `match_price` decimal(8,2) DEFAULT NULL COMMENT '满足金额',
  `reduced_price` decimal(8,2) DEFAULT NULL COMMENT '减免金额',
  `is_send` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发放',
  `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '有效开始时间',
  `stop_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '有效截止时间',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='优惠券表';

-- ----------------------------
-- Records of coupons
-- ----------------------------
INSERT INTO `coupons` VALUES ('1', '0', '满30减20', null, null, '0', '30.00', '20.00', '1', '2018-08-27 23:05:05', '2018-08-31 00:00:00', '2018-08-27 08:32:17', '2018-08-27 08:44:17');
INSERT INTO `coupons` VALUES ('2', '0', '代金券5元', null, null, '0', '0.00', '5.00', '1', '2018-08-27 00:00:00', '2018-08-27 00:00:00', '2018-08-27 08:43:35', '2018-08-27 08:46:17');

-- ----------------------------
-- Table structure for formulas
-- ----------------------------
DROP TABLE IF EXISTS `formulas`;
CREATE TABLE `formulas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '用户ID',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '配方名称',
  `likes` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='配方表';

-- ----------------------------
-- Records of formulas
-- ----------------------------

-- ----------------------------
-- Table structure for goods
-- ----------------------------
DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品名称',
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品图片地址',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `calorie` int(11) NOT NULL DEFAULT '0' COMMENT '商品卡路里值',
  `deploy` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '商品配置：少冰#多冰  多值#号隔开',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `online` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否上架：1是',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_category_id_index` (`category_id`),
  KEY `goods_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商品表';

-- ----------------------------
-- Records of goods
-- ----------------------------
INSERT INTO `goods` VALUES ('1', '1', '回甘普洱', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '19.00', '3', '', '2018-08-23 16:11:23', '2018-08-23 16:11:26', '1', null);
INSERT INTO `goods` VALUES ('3', '1', '茉莉绿茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '21.00', '3', '', '2018-08-23 17:40:01', '2018-08-23 17:40:03', '1', null);
INSERT INTO `goods` VALUES ('4', '1', '雨前龙井', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '21.45', '4', '', '2018-09-02 23:04:29', '2018-09-02 23:04:31', '1', null);
INSERT INTO `goods` VALUES ('5', '1', '正山小种', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '5', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('6', '1', '金凤茶王', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.30', '6', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('7', '4', '可可/黑巧/白巧', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '7', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('8', '4', '椰浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '8', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('9', '4', '抹茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '9', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('10', '3', '气泡水', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '10', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('11', '3', '乳酸菌', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '21', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('12', '3', '冰块', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '3', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('13', '3', '直饮水', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '4', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('14', '2', '脱脂奶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '5', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('15', '2', '全脂奶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '6', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('16', '4', '调和奶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '7', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('17', '4', '鲜奶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '8', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('18', '4', '蝶豆花', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '9', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('19', '4', '白朗姆酒', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '11', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('20', '5', '草莓', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '12', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('21', '5', '芒果', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '13', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('22', '5', '水蜜桃', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '14', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('23', '5', '猕猴桃', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '15', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('24', '5', '西瓜', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '16', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('25', '5', '葡萄', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '17', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('26', '5', '西柚', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '3', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('27', '5', '橙子', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '5', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('28', '5', '黄柠檬', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '7', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('29', '5', '青柠檬', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '8', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('30', '5', '红豆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '10', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('31', '5', '绿豆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '21.00', '10', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('32', '5', '玉米', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '20', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('33', '5', '燕麦', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '30', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('34', '6', '薄荷叶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '40', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('35', '6', '果醋', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '5', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('36', '6', '配茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '6', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('37', '7', '糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '7', '多糖#正常#少糖#半糖#无糖', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('38', '7', '黑糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '8', '多糖#正常#少糖#半糖#无糖', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('39', '7', '焦糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '9', '多糖#正常#少糖#半糖#无糖', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('40', '8', '琥珀珍珠', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '3', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('41', '8', '燕麦', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '4', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('42', '8', '椰果', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '5', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('43', '8', '布丁', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '6', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('44', '8', '雪晶灵', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '7', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('45', '8', '奇亚籽', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '8', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('46', '8', '奥利奥', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '9', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('47', '9', '海盐奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '0', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('48', '9', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '9', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('49', '10', '烤焦糖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '4', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('50', '10', '面包', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '5', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);
INSERT INTO `goods` VALUES ('51', '10', '坚果碎类', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9999', '20.00', '6', '', '2018-09-02 23:05:19', '2018-09-02 23:05:21', '1', null);

-- ----------------------------
-- Table structure for members
-- ----------------------------
DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL COMMENT '店铺ID',
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名称',
  `telephone` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '手机号',
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `age` int(11) NOT NULL DEFAULT '0' COMMENT '年龄',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='会员表';

-- ----------------------------
-- Records of members
-- ----------------------------
INSERT INTO `members` VALUES ('1', '1', '彰武巨蜥', '15260988332', 'http://7xru9n.com1.z0.glb.clouddn.com/%E6%99%AE%E6%B4%B1%E8%8C%B6.jpg', '20', '1', '2018-08-23 18:06:33', '2018-08-23 18:06:37');

-- ----------------------------
-- Table structure for member_coupons
-- ----------------------------
DROP TABLE IF EXISTS `member_coupons`;
CREATE TABLE `member_coupons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '用户ID',
  `coupon_id` int(11) NOT NULL COMMENT '优惠券ID',
  `used` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否使用',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='优惠券表';

-- ----------------------------
-- Records of member_coupons
-- ----------------------------
INSERT INTO `member_coupons` VALUES ('1', '1', '2', '0', '2018-08-27 08:46:17', '2018-08-27 08:46:17');

-- ----------------------------
-- Table structure for member_likes
-- ----------------------------
DROP TABLE IF EXISTS `member_likes`;
CREATE TABLE `member_likes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '用户ID',
  `formula_id` int(11) NOT NULL COMMENT '配方ID',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='会员点赞配方表';

-- ----------------------------
-- Records of member_likes
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2014_10_12_100000_create_password_resets_table', '1');
INSERT INTO `migrations` VALUES ('2018_08_23_082728_create_permission_tables', '2');
INSERT INTO `migrations` VALUES ('2018_08_23_101051_create_table_goods', '3');
INSERT INTO `migrations` VALUES ('2018_08_23_101105_create_table_categories', '3');
INSERT INTO `migrations` VALUES ('2018_08_23_101112_create_table_orders', '3');
INSERT INTO `migrations` VALUES ('2018_08_23_101120_create_table_order_details', '3');
INSERT INTO `migrations` VALUES ('2018_08_23_101127_create_table_members', '3');
INSERT INTO `migrations` VALUES ('2018_08_23_101137_create_table_pushes', '3');
INSERT INTO `migrations` VALUES ('2018_08_23_101144_create_table_coupons', '3');
INSERT INTO `migrations` VALUES ('2018_08_23_101153_create_table_member_coupons', '3');
INSERT INTO `migrations` VALUES ('2018_08_23_101403_create_table_formulas', '3');
INSERT INTO `migrations` VALUES ('2018_08_23_101440_create_table_like_formulas', '3');
INSERT INTO `migrations` VALUES ('2018_08_23_110031_create_table_shops', '3');
INSERT INTO `migrations` VALUES ('2018_08_23_161951_alter_table_goods_online', '4');
INSERT INTO `migrations` VALUES ('2018_08_23_162625_alter_table_goods_delete', '5');
INSERT INTO `migrations` VALUES ('2018_08_24_101711_alter_table_order_detail', '6');
INSERT INTO `migrations` VALUES ('2018_08_27_082118_alter_table_coupons', '7');
INSERT INTO `migrations` VALUES ('2018_08_29_083910_alter_table_roles', '8');
INSERT INTO `migrations` VALUES ('2018_08_29_084234_alter_table_users', '9');
INSERT INTO `migrations` VALUES ('2018_09_03_143427_alter_table_user_shop_id', '10');
INSERT INTO `migrations` VALUES ('2018_09_03_143503_alter_table_order_detail_goods_id', '10');
INSERT INTO `migrations` VALUES ('2018_09_03_143905_alter_table_goods_add_calorie', '10');
INSERT INTO `migrations` VALUES ('2018_09_03_143917_alter_table_category_add_volume', '10');
INSERT INTO `migrations` VALUES ('2018_09_03_172218_alter_table_order_add_user_id', '11');
INSERT INTO `migrations` VALUES ('2018_09_04_164229_alter_table_goods_add_remark', '12');
INSERT INTO `migrations` VALUES ('2018_09_04_165257_alter_table_goods_add_temperature', '13');
INSERT INTO `migrations` VALUES ('2018_09_05_160003_alter_table_orders_add_difference', '14');

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL COMMENT '店铺ID',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `order_sn` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '订单号',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '订单实际价格',
  `original_price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '订单原价格',
  `reduced_price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '订单优惠价格',
  `pay_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付方式：0线下，1微信',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态：0待支付，1已支付，2待领取，3已完成，4已退单，5异常',
  `payed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '支付时间',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '店员下单时的ID',
  `operator` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '操作员',
  `coupon_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券ID',
  `remark` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '订单说明',
  `difference` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '订单修改后差额，负数：平台需给用户付款，正数：用户给平台付款',
  `temperature` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '温度说明',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `orders_shop_id_index` (`shop_id`),
  KEY `orders_member_id_index` (`member_id`),
  KEY `orders_order_sn_index` (`order_sn`),
  KEY `orders_coupon_id_index` (`coupon_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='订单表';

-- ----------------------------
-- Records of orders
-- ----------------------------
INSERT INTO `orders` VALUES ('1', '1', '1', '20180523432123452321', '30.00', '30.00', '0.00', '1', '3', '2018-08-24 09:14:43', '0', '10', '0', '', '', '', '2018-08-24 09:14:46', '2018-09-05 14:47:59');
INSERT INTO `orders` VALUES ('4', '1', '0', '201809030943431122', '201.00', '0.00', '0.00', '1', '3', '2018-09-03 09:43:43', '0', '10', '0', '', '', '', '2018-09-03 11:14:08', '2018-09-05 14:47:53');
INSERT INTO `orders` VALUES ('5', '1', '0', '201809030946338907', '160.00', '160.00', '0.00', '0', '3', '2018-09-03 09:46:33', '0', '10', '0', '', '', '', '2018-09-03 11:14:10', '2018-09-05 14:47:48');
INSERT INTO `orders` VALUES ('6', '1', '0', '201809031111038552', '40.00', '40.00', '0.00', '0', '3', '2018-09-03 11:11:03', '0', '10', '0', '', '', '', '2018-09-03 11:14:14', '2018-09-05 14:47:41');
INSERT INTO `orders` VALUES ('7', '1', '0', '201809031111374822', '19.00', '19.00', '0.00', '0', '3', '2018-09-03 11:11:37', '0', '10', '0', '', '', '', '2018-09-03 11:14:16', '2018-09-05 14:47:35');
INSERT INTO `orders` VALUES ('8', '1', '0', '201809031124295352', '42.00', '42.00', '0.00', '0', '3', '2018-09-03 11:24:29', '0', '10', '0', '', '', '', '2018-09-03 11:30:14', '2018-09-05 14:47:19');
INSERT INTO `orders` VALUES ('9', '1', '0', '201809031129559234', '82.00', '82.00', '0.00', '0', '3', '2018-09-03 11:29:55', '0', '10', '0', '', '', '', '2018-09-03 11:30:17', '2018-09-05 14:47:12');
INSERT INTO `orders` VALUES ('10', '1', '0', '201809031129577816', '82.00', '82.00', '0.00', '0', '3', '2018-09-03 11:29:57', '0', '10', '0', '', '', '', '2018-09-03 11:30:20', '2018-09-05 14:47:02');
INSERT INTO `orders` VALUES ('11', '1', '0', '201809031136252487', '42.00', '42.00', '0.00', '0', '3', '2018-09-03 11:36:25', '0', '10', '0', '', '', '', '2018-09-03 11:36:25', '2018-09-05 14:46:47');
INSERT INTO `orders` VALUES ('12', '1', '0', '201809031155407934', '180.00', '180.00', '0.00', '0', '3', '2018-09-03 11:55:40', '0', '10', '0', '', '', '', '2018-09-03 11:55:40', '2018-09-05 14:46:40');
INSERT INTO `orders` VALUES ('13', '1', '0', '201809031155424631', '180.00', '180.00', '0.00', '0', '3', '2018-09-03 11:55:42', '0', '10', '0', '', '', '', '2018-09-03 11:55:42', '2018-09-03 17:38:29');
INSERT INTO `orders` VALUES ('14', '1', '0', '201809031431272884', '181.00', '181.00', '0.00', '0', '3', '2018-09-03 14:31:27', '0', '10', '0', '', '', '', '2018-09-03 14:31:27', '2018-09-03 17:38:45');
INSERT INTO `orders` VALUES ('15', '1', '0', '201809041606369949', '20.00', '20.00', '0.00', '0', '3', '2018-09-04 16:06:36', '10', '10', '0', '', '', '', '2018-09-04 16:06:36', '2018-09-05 14:46:54');
INSERT INTO `orders` VALUES ('16', '1', '0', '201809041728078958', '120.00', '120.00', '0.00', '0', '3', '2018-09-04 17:28:07', '10', '10', '0', '', '', 'a:2:{i:1;s:6:\"热饮\";i:2;s:6:\"热饮\";}', '2018-09-04 17:28:07', '2018-09-05 14:46:30');
INSERT INTO `orders` VALUES ('17', '1', '0', '201809041733538405', '121.00', '121.00', '0.00', '0', '3', '2018-09-04 17:33:53', '10', '10', '0', '', '', 'a:2:{i:1;s:6:\"热饮\";i:2;s:6:\"热饮\";}', '2018-09-04 17:33:53', '2018-09-05 14:46:24');
INSERT INTO `orders` VALUES ('18', '1', '0', '201809050844169195', '180.00', '180.00', '0.00', '0', '3', '2018-09-05 08:44:16', '10', '10', '0', '', '', 'a:2:{i:1;s:6:\"热饮\";i:2;s:6:\"少冰\";}', '2018-09-05 08:44:16', '2018-09-05 14:42:59');
INSERT INTO `orders` VALUES ('19', '1', '0', '201809050845505507', '62.90', '62.90', '0.00', '0', '3', '2018-09-05 08:45:50', '10', '10', '0', '', '', 'a:1:{i:1;s:9:\"正常冰\";}', '2018-09-05 08:45:50', '2018-09-05 14:42:54');
INSERT INTO `orders` VALUES ('20', '1', '0', '201809050910099574', '140.00', '140.00', '0.00', '0', '3', '2018-09-05 09:10:09', '10', '10', '0', '', '', 'a:2:{i:1;s:6:\"热饮\";i:2;s:6:\"热饮\";}', '2018-09-05 09:10:09', '2018-09-05 14:42:49');
INSERT INTO `orders` VALUES ('21', '1', '0', '201809050916283157', '180.00', '180.00', '0.00', '0', '3', '2018-09-05 09:16:28', '10', '10', '0', '', '', 'a:2:{i:1;s:6:\"热饮\";i:2;s:6:\"热饮\";}', '2018-09-05 09:16:28', '2018-09-05 14:04:35');
INSERT INTO `orders` VALUES ('22', '1', '0', '201809050938528337', '201.00', '201.00', '0.00', '0', '3', '2018-09-05 09:38:52', '10', null, '0', '', '', 'a:2:{i:1;s:6:\"热饮\";i:2;s:6:\"热饮\";}', '2018-09-05 09:38:52', '2018-09-06 16:25:13');
INSERT INTO `orders` VALUES ('23', '1', '0', '201809050951599526', '201.00', '201.00', '0.00', '0', '3', '2018-09-05 09:51:59', '10', null, '0', '', '', 'a:2:{i:1;s:9:\"正常冰\";i:2;s:6:\"少冰\";}', '2018-09-05 09:51:59', '2018-09-06 16:25:46');
INSERT INTO `orders` VALUES ('24', '1', '0', '201809051148554822', '100.00', '100.00', '0.00', '0', '3', '2018-09-05 11:48:55', '10', null, '0', '', '', 'a:1:{i:1;s:6:\"热饮\";}', '2018-09-05 11:48:55', '2018-09-06 16:10:45');
INSERT INTO `orders` VALUES ('25', '1', '0', '201809051415471871', '120.00', '120.00', '0.00', '0', '3', '2018-09-05 14:15:47', '10', null, '0', '', '', 'a:1:{i:1;s:9:\"正常冰\";}', '2018-09-05 14:15:47', '2018-09-06 16:10:51');
INSERT INTO `orders` VALUES ('26', '1', '0', '201809051759001870', '122.45', '122.45', '0.00', '0', '3', '2018-09-05 17:59:00', '10', null, '0', '', '-20.45', 'a:1:{i:1;s:3:\"hot\";}', '2018-09-05 17:59:00', '2018-09-06 16:25:21');
INSERT INTO `orders` VALUES ('27', '1', '0', '201809051807415241', '121.00', '121.00', '0.00', '0', '3', '2018-09-05 18:07:41', '10', null, '0', '', '-19.00', 'a:1:{i:1;s:8:\"less_ice\";}', '2018-09-05 18:07:41', '2018-09-06 16:25:25');
INSERT INTO `orders` VALUES ('28', '1', '0', '201809061039164624', '121.00', '121.00', '0.00', '0', '3', '2018-09-06 10:39:16', '10', null, '0', '', '39.00', 'a:1:{i:1;s:3:\"hot\";}', '2018-09-06 10:39:16', '2018-09-06 16:25:28');
INSERT INTO `orders` VALUES ('29', '1', '0', '201809061040513563', '162.00', '162.00', '0.00', '0', '1', '2018-09-06 10:40:51', '10', null, '0', '', '104.00', 'a:1:{i:1;s:8:\"less_ice\";}', '2018-09-06 10:40:51', '2018-09-06 11:23:22');
INSERT INTO `orders` VALUES ('30', '1', '0', '201809061129162742', '121.45', '121.45', '0.00', '0', '1', '2018-09-06 11:29:16', '10', null, '0', '', '18.55', 'a:1:{i:1;s:8:\"less_ice\";}', '2018-09-06 11:29:16', '2018-09-06 11:30:22');
INSERT INTO `orders` VALUES ('31', '1', '0', '201809061132339773', '401.45', '401.45', '0.00', '0', '1', '2018-09-06 11:32:33', '10', null, '0', '', '0.00', 'a:3:{i:1;s:3:\"ice\";i:2;s:8:\"less_ice\";i:3;s:8:\"less_ice\";}', '2018-09-06 11:32:33', '2018-09-06 15:14:43');
INSERT INTO `orders` VALUES ('32', '1', '0', '201809061521205348', '220.00', '220.00', '0.00', '0', '1', '2018-09-06 15:21:20', '10', null, '0', '', '', 'a:2:{i:1;s:3:\"hot\";i:2;s:3:\"hot\";}', '2018-09-06 15:21:20', '0000-00-00 00:00:00');
INSERT INTO `orders` VALUES ('33', '1', '0', '201809061522169197', '203.90', '203.90', '0.00', '0', '1', '2018-09-06 15:22:16', '10', null, '0', '', '23.90', 'a:2:{i:1;s:3:\"ice\";i:2;s:8:\"less_ice\";}', '2018-09-06 15:22:16', '2018-09-06 16:07:31');

-- ----------------------------
-- Table structure for order_details
-- ----------------------------
DROP TABLE IF EXISTS `order_details`;
CREATE TABLE `order_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `goods_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品名称',
  `goods_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '商品图片',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `goods_num` int(11) NOT NULL COMMENT '商品数量',
  `goods_price` decimal(8,2) NOT NULL COMMENT '商品价格',
  `package_num` int(11) NOT NULL DEFAULT '1' COMMENT '口袋数',
  `deploy` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '商品配置',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=357 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='订单详情表';

-- ----------------------------
-- Records of order_details
-- ----------------------------
INSERT INTO `order_details` VALUES ('1', '0', '龙井茶', 'http://7xru9n.com1.z0.glb.clouddn.com/%E6%99%AE%E6%B4%B1%E8%8C%B6.jpg', '1', '1', '13.00', '1', null, '2018-08-24 09:32:00', '2018-08-24 09:32:02');
INSERT INTO `order_details` VALUES ('2', '0', '冷饮', 'http://7xru9n.com1.z0.glb.clouddn.com/%E6%99%AE%E6%B4%B1%E8%8C%B6.jpg', '1', '1', '7.00', '1', '多冰', '2018-08-24 09:32:48', '2018-08-24 09:32:51');
INSERT INTO `order_details` VALUES ('3', '0', '普洱茶', 'http://7xru9n.com1.z0.glb.clouddn.com/%E6%99%AE%E6%B4%B1%E8%8C%B6.jpg', '1', '1', '20.00', '2', null, '2018-08-24 09:45:05', '2018-08-24 09:45:08');
INSERT INTO `order_details` VALUES ('14', '0', '正山小种', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '5', '1', '20.00', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('15', '0', '调和奶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '5', '1', '20.00', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('16', '0', '猕猴桃', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '5', '1', '20.00', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('17', '0', '配茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '5', '1', '20.00', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('18', '0', '焦糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '5', '1', '20.00', '1', '无糖', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('19', '0', '奥利奥', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '5', '1', '20.00', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('20', '0', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '5', '1', '20.00', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('21', '0', '面包', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '5', '1', '20.00', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('22', '0', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '6', '1', '20.00', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('23', '0', '坚果碎类', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '6', '1', '20.00', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('24', '0', '回甘普洱', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '7', '1', '19.00', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('25', '0', '茉莉绿茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '8', '2', '21.00', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('26', '0', '雨前龙井', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9', '2', '21.00', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('27', '0', '果醋', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9', '1', '20.00', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('28', '0', '黑糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '9', '1', '20.00', '1', '少糖', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('29', '0', '雨前龙井', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '10', '2', '21.00', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('30', '0', '果醋', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '10', '1', '20.00', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('31', '0', '黑糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '10', '1', '20.00', '1', '少糖', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('32', '0', '茉莉绿茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '11', '2', '21.00', '1', '0', '2018-09-03 11:36:25', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('33', '0', '全脂奶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '12', '1', '20.00', '1', '0', '2018-09-03 11:55:40', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('34', '0', '白朗姆酒', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '12', '1', '20.00', '1', '0', '2018-09-03 11:55:40', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('35', '0', '水蜜桃', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '12', '1', '20.00', '1', '0', '2018-09-03 11:55:40', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('36', '0', '玉米', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '12', '1', '20.00', '1', '0', '2018-09-03 11:55:40', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('37', '0', '配茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '12', '1', '20.00', '1', '0', '2018-09-03 11:55:40', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('38', '0', '焦糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '12', '1', '20.00', '1', '少糖', '2018-09-03 11:55:40', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('39', '0', '燕麦', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '12', '1', '20.00', '1', '0', '2018-09-03 11:55:40', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('40', '0', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '12', '1', '20.00', '1', '0', '2018-09-03 11:55:40', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('41', '0', '面包', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '12', '1', '20.00', '1', '0', '2018-09-03 11:55:40', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('42', '0', '全脂奶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '13', '1', '20.00', '1', '0', '2018-09-03 11:55:42', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('43', '0', '白朗姆酒', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '13', '1', '20.00', '1', '0', '2018-09-03 11:55:42', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('44', '0', '水蜜桃', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '13', '1', '20.00', '1', '0', '2018-09-03 11:55:42', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('45', '0', '玉米', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '13', '1', '20.00', '1', '0', '2018-09-03 11:55:42', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('46', '0', '配茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '13', '1', '20.00', '1', '0', '2018-09-03 11:55:42', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('47', '0', '焦糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '13', '1', '20.00', '1', '少糖', '2018-09-03 11:55:42', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('48', '0', '燕麦', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '13', '1', '20.00', '1', '0', '2018-09-03 11:55:42', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('49', '0', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '13', '1', '20.00', '1', '0', '2018-09-03 11:55:42', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('50', '0', '面包', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '13', '1', '20.00', '1', '0', '2018-09-03 11:55:42', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('51', '0', '茉莉绿茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '14', '1', '21.00', '1', '', '2018-09-03 14:31:27', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('52', '0', '椰浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '14', '1', '20.00', '1', '', '2018-09-03 14:31:27', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('53', '0', '全脂奶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '14', '1', '20.00', '1', '', '2018-09-03 14:31:27', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('54', '0', '白朗姆酒', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '14', '1', '20.00', '1', '', '2018-09-03 14:31:27', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('55', '0', '黄柠檬', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '14', '1', '20.00', '1', '', '2018-09-03 14:31:27', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('56', '0', '玉米', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '14', '1', '20.00', '1', '', '2018-09-03 14:31:27', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('57', '0', '配茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '14', '1', '20.00', '1', '', '2018-09-03 14:31:27', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('58', '0', '椰果', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '14', '1', '20.00', '1', '', '2018-09-03 14:31:27', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('59', '0', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '14', '1', '20.00', '1', '', '2018-09-03 14:31:27', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('60', '0', '抹茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '15', '1', '20.00', '1', '', '2018-09-04 16:06:36', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('61', '0', '草莓', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '16', '1', '20.00', '1', '', '2018-09-04 17:28:07', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('62', '0', '芒果', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '16', '1', '20.00', '2', '', '2018-09-04 17:28:07', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('63', '0', '回甘普洱', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '17', '1', '19.00', '1', '', '2018-09-04 17:33:53', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('64', '0', '可可/黑巧/白巧', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '17', '1', '20.00', '1', '', '2018-09-04 17:33:53', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('65', '0', '草莓', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '17', '1', '20.00', '1', '', '2018-09-04 17:33:53', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('66', '0', '茉莉绿茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '17', '1', '21.00', '2', '', '2018-09-04 17:33:53', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('67', '0', '椰浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '17', '1', '20.00', '2', '', '2018-09-04 17:33:53', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('68', '0', '绿豆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '17', '1', '21.00', '2', '', '2018-09-04 17:33:53', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('69', '0', '回甘普洱', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '18', '1', '19.00', '1', '', '2018-09-05 08:44:16', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('70', '0', '可可/黑巧/白巧', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '18', '1', '20.00', '1', '', '2018-09-05 08:44:16', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('71', '0', '草莓', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '18', '1', '20.00', '1', '', '2018-09-05 08:44:16', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('72', '0', '糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '18', '1', '20.00', '1', '多糖', '2018-09-05 08:44:16', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('73', '0', '全脂奶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '18', '1', '20.00', '2', '', '2018-09-05 08:44:16', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('74', '0', '绿豆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '18', '1', '21.00', '2', '', '2018-09-05 08:44:16', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('75', '0', '果醋', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '18', '1', '20.00', '2', '', '2018-09-05 08:44:16', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('76', '0', '黑糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '18', '1', '20.00', '2', '正常', '2018-09-05 08:44:16', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('77', '0', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '18', '1', '20.00', '2', '', '2018-09-05 08:44:16', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('78', '0', '雨前龙井', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '19', '2', '21.45', '1', '', '2018-09-05 08:45:50', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('79', '0', '糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '19', '1', '20.00', '1', '正常', '2018-09-05 08:45:50', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('80', '0', '回甘普洱', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '20', '1', '19.00', '1', '', '2018-09-05 09:10:09', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('81', '0', '可可/黑巧/白巧', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '20', '1', '20.00', '1', '', '2018-09-05 09:10:09', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('82', '0', '葡萄', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '20', '1', '20.00', '1', '', '2018-09-05 09:10:09', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('83', '0', '茉莉绿茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '20', '1', '21.00', '2', '', '2018-09-05 09:10:09', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('84', '0', '椰浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '20', '1', '20.00', '2', '', '2018-09-05 09:10:09', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('85', '0', '芒果', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '20', '1', '20.00', '2', '', '2018-09-05 09:10:09', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('86', '0', '燕麦', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '20', '1', '20.00', '2', '', '2018-09-05 09:10:09', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('87', '0', '回甘普洱', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '21', '1', '19.00', '1', '', '2018-09-05 09:16:28', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('88', '0', '可可/黑巧/白巧', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '21', '1', '20.00', '1', '', '2018-09-05 09:16:28', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('89', '0', '葡萄', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '21', '1', '20.00', '1', '', '2018-09-05 09:16:28', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('90', '0', '黑糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '21', '1', '20.00', '1', '多糖', '2018-09-05 09:16:28', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('91', '0', '琥珀珍珠', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '21', '1', '20.00', '1', '', '2018-09-05 09:16:28', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('92', '0', '茉莉绿茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '21', '1', '21.00', '2', '', '2018-09-05 09:16:28', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('93', '0', '椰浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '21', '1', '20.00', '2', '', '2018-09-05 09:16:28', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('94', '0', '果醋', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '21', '1', '20.00', '2', '', '2018-09-05 09:16:28', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('95', '0', '焦糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '21', '1', '20.00', '2', '多糖', '2018-09-05 09:16:28', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('96', '0', '回甘普洱', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '22', '1', '19.00', '1', '', '2018-09-05 09:38:52', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('97', '0', '可可/黑巧/白巧', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '22', '1', '20.00', '1', '', '2018-09-05 09:38:52', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('98', '0', '草莓', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '22', '1', '20.00', '1', '', '2018-09-05 09:38:52', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('99', '0', '黑糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '22', '1', '20.00', '1', '多糖', '2018-09-05 09:38:52', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('100', '0', '奇亚籽', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '22', '1', '20.00', '1', '', '2018-09-05 09:38:52', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('101', '0', '茉莉绿茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '22', '1', '21.00', '2', '', '2018-09-05 09:38:52', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('102', '0', '椰浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '22', '1', '20.00', '2', '', '2018-09-05 09:38:52', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('103', '0', '绿豆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '22', '1', '21.00', '2', '', '2018-09-05 09:38:52', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('104', '0', '焦糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '22', '1', '20.00', '2', '多糖', '2018-09-05 09:38:52', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('105', '0', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '22', '1', '20.00', '2', '', '2018-09-05 09:38:52', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('106', '1', '回甘普洱', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '23', '1', '19.00', '1', '', '2018-09-05 09:51:59', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('107', '7', '可可/黑巧/白巧', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '23', '1', '20.00', '1', '', '2018-09-05 09:51:59', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('108', '20', '草莓', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '23', '1', '20.00', '1', '', '2018-09-05 09:51:59', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('109', '34', '薄荷叶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '23', '1', '20.00', '1', '', '2018-09-05 09:51:59', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('110', '37', '糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '23', '1', '20.00', '1', '多糖', '2018-09-05 09:51:59', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('111', '3', '茉莉绿茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '23', '1', '21.00', '2', '', '2018-09-05 09:51:59', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('112', '8', '椰浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '23', '1', '20.00', '2', '', '2018-09-05 09:51:59', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('113', '31', '绿豆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '23', '1', '21.00', '2', '', '2018-09-05 09:51:59', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('114', '39', '焦糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '23', '1', '20.00', '2', '正常', '2018-09-05 09:51:59', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('115', '48', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '23', '1', '20.00', '2', '', '2018-09-05 09:51:59', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('116', '9', '抹茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '24', '1', '20.00', '1', '', '2018-09-05 11:48:55', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('117', '15', '全脂奶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '24', '1', '20.00', '1', '', '2018-09-05 11:48:55', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('118', '32', '玉米', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '24', '1', '20.00', '1', '', '2018-09-05 11:48:55', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('119', '38', '黑糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '24', '1', '20.00', '1', '少糖', '2018-09-05 11:48:55', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('120', '42', '椰果', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '24', '1', '20.00', '1', '', '2018-09-05 11:48:55', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('121', '8', '椰浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '25', '1', '20.00', '1', '', '2018-09-05 14:15:47', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('122', '12', '冰块', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '25', '1', '20.00', '1', '', '2018-09-05 14:15:47', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('123', '21', '芒果', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '25', '1', '20.00', '1', '', '2018-09-05 14:15:47', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('124', '35', '果醋', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '25', '1', '20.00', '1', '', '2018-09-05 14:15:47', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('125', '39', '焦糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '25', '1', '20.00', '1', '正常', '2018-09-05 14:15:47', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('126', '50', '面包', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '25', '1', '20.00', '1', '', '2018-09-05 14:15:47', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('137', '8', '椰浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '27', '1', '20.00', '1', '', '2018-09-06 09:55:02', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('138', '15', '全脂奶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '27', '1', '20.00', '1', '', '2018-09-06 09:55:02', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('139', '31', '绿豆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '27', '1', '21.00', '1', '', '2018-09-06 09:55:02', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('140', '34', '薄荷叶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '27', '1', '20.00', '1', '', '2018-09-06 09:55:02', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('141', '39', '焦糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '27', '1', '20.00', '1', '无糖', '2018-09-06 09:55:02', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('142', '49', '烤焦糖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '27', '1', '20.00', '1', '', '2018-09-06 09:55:02', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('143', '4', '雨前龙井', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '26', '1', '21.45', '1', '', '2018-09-06 10:15:26', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('144', '8', '椰浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '26', '1', '20.00', '1', '', '2018-09-06 10:15:26', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('145', '31', '绿豆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '26', '1', '21.00', '1', '', '2018-09-06 10:15:26', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('146', '35', '果醋', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '26', '1', '20.00', '1', '', '2018-09-06 10:15:26', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('147', '38', '黑糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '26', '1', '20.00', '1', '多糖', '2018-09-06 10:15:26', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('148', '48', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '26', '1', '20.00', '1', '', '2018-09-06 10:15:26', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('158', '3', '茉莉绿茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '29', '1', '21.00', '1', '', '2018-09-06 11:23:22', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('159', '31', '绿豆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '29', '1', '21.00', '1', '', '2018-09-06 11:23:22', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('160', '35', '果醋', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '29', '1', '20.00', '1', '', '2018-09-06 11:23:22', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('161', '39', '焦糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '29', '1', '20.00', '1', '少糖', '2018-09-06 11:23:22', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('162', '41', '燕麦', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '29', '1', '20.00', '1', '', '2018-09-06 11:23:22', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('163', '42', '椰果', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '29', '1', '20.00', '1', '', '2018-09-06 11:23:22', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('164', '48', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '29', '1', '20.00', '1', '', '2018-09-06 11:23:22', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('165', '51', '坚果碎类', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '29', '1', '20.00', '1', '', '2018-09-06 11:23:22', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('166', '3', '茉莉绿茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '28', '1', '21.00', '1', '', '2018-09-06 11:25:16', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('167', '32', '玉米', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '28', '1', '20.00', '1', '', '2018-09-06 11:25:16', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('168', '36', '配茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '28', '1', '20.00', '1', '', '2018-09-06 11:25:16', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('169', '39', '焦糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '28', '1', '20.00', '1', '无糖', '2018-09-06 11:25:16', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('170', '43', '布丁', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '28', '1', '20.00', '1', '', '2018-09-06 11:25:16', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('171', '51', '坚果碎类', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '28', '1', '20.00', '1', '', '2018-09-06 11:25:16', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('176', '4', '雨前龙井', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '30', '1', '21.45', '1', '', '2018-09-06 11:30:22', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('177', '33', '燕麦', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '30', '1', '20.00', '1', '', '2018-09-06 11:30:22', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('178', '35', '果醋', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '30', '1', '20.00', '1', '', '2018-09-06 11:30:22', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('179', '39', '焦糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '30', '1', '20.00', '1', '正常', '2018-09-06 11:30:22', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('180', '48', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '30', '1', '20.00', '1', '', '2018-09-06 11:30:22', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('181', '51', '坚果碎类', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '30', '1', '20.00', '1', '', '2018-09-06 11:30:22', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('283', '1', '回甘普洱', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '19.00', '1', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('284', '18', '蝶豆花', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '1', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('285', '20', '草莓', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '1', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('286', '34', '薄荷叶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '1', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('287', '37', '糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '1', '多糖', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('288', '47', '海盐奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '1', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('289', '49', '烤焦糖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '1', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('290', '3', '茉莉绿茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '21.00', '2', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('291', '21', '芒果', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '2', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('292', '36', '配茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '2', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('293', '39', '焦糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '2', '正常', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('294', '42', '椰果', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '2', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('295', '48', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '2', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('296', '51', '坚果碎类', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '2', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('297', '4', '雨前龙井', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '21.45', '3', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('298', '33', '燕麦', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '3', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('299', '36', '配茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '3', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('300', '39', '焦糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '3', '少糖', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('301', '48', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '3', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('302', '51', '坚果碎类', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '31', '1', '20.00', '3', '', '2018-09-06 15:14:43', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('303', '1', '回甘普洱', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '32', '1', '19.00', '1', '', '2018-09-06 15:21:20', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('304', '7', '可可/黑巧/白巧', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '32', '1', '20.00', '1', '', '2018-09-06 15:21:20', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('305', '30', '红豆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '32', '1', '20.00', '1', '', '2018-09-06 15:21:20', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('306', '39', '焦糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '32', '1', '20.00', '1', '多糖', '2018-09-06 15:21:20', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('307', '47', '海盐奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '32', '1', '20.00', '1', '', '2018-09-06 15:21:20', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('308', '3', '茉莉绿茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '32', '1', '21.00', '2', '', '2018-09-06 15:21:20', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('309', '8', '椰浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '32', '1', '20.00', '2', '', '2018-09-06 15:21:20', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('310', '21', '芒果', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '32', '1', '20.00', '2', '', '2018-09-06 15:21:20', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('311', '35', '果醋', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '32', '1', '20.00', '2', '', '2018-09-06 15:21:20', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('312', '38', '黑糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '32', '1', '20.00', '2', '正常', '2018-09-06 15:21:20', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('313', '48', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '32', '1', '20.00', '2', '', '2018-09-06 15:21:20', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('348', '4', '雨前龙井', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '33', '2', '21.45', '1', '', '2018-09-06 16:07:31', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('349', '38', '黑糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '33', '1', '20.00', '1', '多糖', '2018-09-06 16:07:31', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('350', '3', '茉莉绿茶', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '33', '1', '21.00', '2', '', '2018-09-06 16:07:31', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('351', '8', '椰浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '33', '1', '20.00', '2', '', '2018-09-06 16:07:31', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('352', '21', '芒果', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '33', '1', '20.00', '2', '', '2018-09-06 16:07:31', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('353', '35', '果醋', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '33', '1', '20.00', '2', '', '2018-09-06 16:07:31', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('354', '37', '糖浆', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '33', '1', '20.00', '2', '正常', '2018-09-06 16:07:31', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('355', '48', '芝士奶盖', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '33', '1', '20.00', '2', '', '2018-09-06 16:07:31', '0000-00-00 00:00:00');
INSERT INTO `order_details` VALUES ('356', '50', '面包', 'https://s33.postimg.cc/gwax4fiwf/2345_20180903064600.jpg', '33', '1', '20.00', '2', '', '2018-09-06 16:07:31', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES ('1', 'manage_contents', 'web', '2018-08-22 14:48:21', '2018-08-22 14:48:21');
INSERT INTO `permissions` VALUES ('2', 'manage_users', 'web', '2018-08-22 14:48:21', '2018-08-22 14:48:21');
INSERT INTO `permissions` VALUES ('3', 'edit_settings', 'web', '2018-08-22 14:48:21', '2018-08-22 14:48:21');

-- ----------------------------
-- Table structure for pushes
-- ----------------------------
DROP TABLE IF EXISTS `pushes`;
CREATE TABLE `pushes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL COMMENT '店铺ID',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '标题',
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片',
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '链接地址',
  `content` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '内容',
  `position` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='推送表';

-- ----------------------------
-- Records of pushes
-- ----------------------------
INSERT INTO `pushes` VALUES ('1', '11', '推送标题', '/uploads/20180824165545-5b7fc811b9422.jpg', null, null, '0', '2018-08-24 16:57:58', '2018-08-24 16:57:58');
INSERT INTO `pushes` VALUES ('2', '11', '推送第二家', '/uploads/20180824171311-5b7fcc27da35d.png', null, null, '23', '2018-08-24 17:02:18', '2018-08-24 17:13:49');
INSERT INTO `pushes` VALUES ('4', '1', '第三条推送搜搜', '/uploads/20180824170439-5b7fca27555d0.jpg', null, null, '1', '2018-08-24 17:04:41', '2018-08-24 17:04:41');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '角色描述',
  `guard_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('1', 'Administrator', '管理员', 'web', '2018-08-22 14:48:21', '2018-08-29 09:15:55');
INSERT INTO `roles` VALUES ('3', 'Manager', '仓库管理员', '', '2018-08-29 09:06:37', '2018-08-29 09:52:14');
INSERT INTO `roles` VALUES ('9', 'Clerk', '店员管理系统', '', '2018-08-29 14:37:27', '2018-08-29 14:37:27');
INSERT INTO `roles` VALUES ('8', 'tester', 'shuoming', '', '2018-08-29 09:18:19', '2018-08-29 09:18:19');

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of role_has_permissions
-- ----------------------------
INSERT INTO `role_has_permissions` VALUES ('1', '1');
INSERT INTO `role_has_permissions` VALUES ('1', '2');
INSERT INTO `role_has_permissions` VALUES ('2', '1');
INSERT INTO `role_has_permissions` VALUES ('3', '1');

-- ----------------------------
-- Table structure for shops
-- ----------------------------
DROP TABLE IF EXISTS `shops`;
CREATE TABLE `shops` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `flag` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '店铺标识',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '店铺名称',
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地址',
  `contact` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '联系方式',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `shops_flag_unique` (`flag`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='店铺表';

-- ----------------------------
-- Records of shops
-- ----------------------------
INSERT INTO `shops` VALUES ('1', 'Shop10001', 'ABC', '德化县浔中镇高坂路20号', '152132312', '2018-08-23 14:41:56', '2018-08-23 16:01:04');
INSERT INTO `shops` VALUES ('11', 'Shop10011', '第一家店铺', '龙选择', '1532342332', '2018-08-23 15:03:41', '2018-08-23 15:03:41');
INSERT INTO `shops` VALUES ('12', 'Shop10012', '第1234', '德化县浔中镇训中小学', '15323423212', '2018-08-23 15:05:58', '2018-08-23 15:40:00');
INSERT INTO `shops` VALUES ('13', 'Shop10013', '之阿航旗舰店', '泉州市刺桐路温泉西路318号', '13852412432', '2018-08-28 10:46:02', '2018-08-28 10:46:02');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `real_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  `telephone` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型：0管理员，1店员，2仓储员',
  `role_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '角色ID',
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', '1', 'admin', '', '', 'j824hrU9Fi@gmail.com', '0', '1', '$2y$10$DvxoxxE5nDLSFIrOQs5bn.NS4R6n45ei0qjuLzDyFGixnNrIT/0Om', 'BweDXZpGowpO8ivfAtpK622uGKiQaIYmAViUAFnnxNjbxxVQRNMfw4SjTqo4', '0000-00-00 00:00:00', '2018-08-29 14:48:51');
INSERT INTO `users` VALUES ('10', '1', 'clerk', '客服1号', '15382124892', 'clerk@email.com', '0', '9', '$2y$10$RD5JUn5o/X8kZ4r669TUFOTw2Y2le5hVEDTYojeMyUh21zjCcO9Ce', 'e6FiX5ws0QMWRDJXihO5oRxVvDPPFKYEvPzO3TSxJHIXbXyazGhZpgB33Yn9', '2018-08-29 14:37:14', '2018-09-06 16:26:25');
INSERT INTO `users` VALUES ('9', '1', 'manager', '', '', 'manager@email.com', '0', '3', '$2y$10$QI4MYTiCW0zrxgeFWrfNvOSMJ7OuDNePY725ieaoHepyg5.7U60Z2', '2LkyBuEmKZV8qGBFL3h2kAw14OjbiJsnVdVAcTdCa6R21xiu1eGJVqJUee9t', '2018-08-29 09:54:49', '2018-08-29 14:40:48');
INSERT INTO `users` VALUES ('11', '11', 'zhangsan', '战三', '13850885921', 'zhangsan@email.com', '0', '9', '$2y$10$73mXxE5Sp3ph82tMDkboAOKQ/hE4fO74GM3SZ.oLqqAhhnekbFe2a', null, '2018-09-03 16:44:22', '2018-09-03 16:45:45');

-- ----------------------------
-- Table structure for user_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `user_has_permissions`;
CREATE TABLE `user_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`permission_id`,`user_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`user_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user_has_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for user_has_roles
-- ----------------------------
DROP TABLE IF EXISTS `user_has_roles`;
CREATE TABLE `user_has_roles` (
  `role_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`role_id`,`user_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`user_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of user_has_roles
-- ----------------------------
INSERT INTO `user_has_roles` VALUES ('1', '1', 'App\\Models\\User');
INSERT INTO `user_has_roles` VALUES ('1', '3', '');
INSERT INTO `user_has_roles` VALUES ('1', '4', '');
INSERT INTO `user_has_roles` VALUES ('1', '5', '');
INSERT INTO `user_has_roles` VALUES ('1', '6', '');
INSERT INTO `user_has_roles` VALUES ('2', '2', '');
