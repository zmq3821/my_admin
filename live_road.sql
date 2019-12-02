/*
Navicat MySQL Data Transfer

Source Server         : 本地数据库
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : live_road

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-12-02 17:00:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ts_user
-- ----------------------------
DROP TABLE IF EXISTS `ts_user`;
CREATE TABLE `ts_user` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL COMMENT '用户名',
  `password` varchar(255) NOT NULL COMMENT '用户密码的md5摘要',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `sex` tinyint(1) NOT NULL COMMENT '0：保密；1：男，2：女',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '电话',
  `intro` varchar(255) NOT NULL COMMENT '户用简介',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '用户最后一次登录时间',
  `is_del` tinyint(2) NOT NULL COMMENT '是否禁用，0不禁用，1：禁用',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of ts_user
-- ----------------------------
INSERT INTO `ts_user` VALUES ('12', 'zmq3821', '$2y$10$H/hhjwOEb5Orn97qRXlQcOEuCFfsXuACn7V.7TNb/C9pbI5Z90R7S', '1287090364@qq.com', '0', '18736829312', '', '0', '0', '0', '0');
INSERT INTO `ts_user` VALUES ('13', 'zmq1287', '$2y$10$QIQOVuSwbgJscBVmQbIGz.h9WVnXX./SeyXgmEW1bZAr2gsZtz.Z2', '849997846@qq.com', '0', '15188302310', '', '0', '0', '0', '0');
INSERT INTO `ts_user` VALUES ('14', 'admin', '$2y$10$yuc/HSloQwbRiGDA1.HXkOyz6QYMyogpiyh9zbU5XOgI0Up5CXx16', '123456@qq.com', '0', '17736829312', '', '0', '0', '0', '0');
INSERT INTO `ts_user` VALUES ('16', 'test', '$2y$10$/8GVjqHDP9VjzUZIgvzO5O8FftWUWVdtBkl7v9N0NBEUFxAXcGFGK', '825555@qq.com', '0', '15872545111', '', '1575270539', '0', '1575270539', '1575270539');
