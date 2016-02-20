/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : wxtest

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-02-19 23:04:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for wx_member
-- ----------------------------
DROP TABLE IF EXISTS `wx_member`;
CREATE TABLE `wx_member` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `sex` tinyint(1) NOT NULL COMMENT '值为1时是男性，值为2时是女性，值为0时是未知',
  `city` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `subscribe_time` int(11) DEFAULT NULL COMMENT '订阅的时间',
  `unionid` varchar(200) NOT NULL,
  `unsubscribe_time` int(11) DEFAULT NULL COMMENT '取消关注的时间',
  `status` tinyint(1) NOT NULL COMMENT '1:订阅状态；2取消订阅状态',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for wx_subscribe
-- ----------------------------
DROP TABLE IF EXISTS `wx_subscribe`;
CREATE TABLE `wx_subscribe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '订阅情况：1订阅；2取消订阅',
  `create` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
