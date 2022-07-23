-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 2021-07-06 18:05:15
-- 服务器版本： 5.7.34-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin_rbac_std`
--

-- --------------------------------------------------------

--
-- 表的结构 `rbac_std_admin_group`
--

CREATE TABLE `rbac_std_admin_group` (
  `id` int(11) NOT NULL,
  `title` char(60) NOT NULL,
  `description` char(60) NOT NULL,
  `rules` tinytext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `rbac_std_admin_group`
--

INSERT INTO `rbac_std_admin_group` (`id`, `title`, `description`, `rules`) VALUES
(1, 'supervisor', '超级管理员，请勿操作', '1,5,6,8,7,9');

-- --------------------------------------------------------

--
-- 表的结构 `rbac_std_admin_node`
--

CREATE TABLE `rbac_std_admin_node` (
  `id` int(11) NOT NULL,
  `hidden` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否对一般用户可见',
  `controller` char(60) NOT NULL,
  `method` char(60) NOT NULL,
  `unicode` char(60) NOT NULL,
  `title` char(60) NOT NULL,
  `sort` tinyint(2) NOT NULL,
  `level` tinyint(2) NOT NULL,
  `pid` int(2) NOT NULL,
  `remark` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `rbac_std_admin_node`
--

INSERT INTO `rbac_std_admin_node` (`id`, `hidden`, `controller`, `method`, `unicode`, `title`, `sort`, `level`, `pid`, `remark`) VALUES
(1, 0, 'Index', 'index', 'root', '首页根目录', 0, 0, -1, '请勿修改'),
(2, 1, '', '', 'system_center', '站长系统', 5, 1, 1, '非专业人士请勿更改'),
(3, 1, 'AdminNode', 'admin_node', 'admin_node_root', '操作结点管理', 1, 2, 2, ''),
(4, 1, 'AdminNode', 'post', 'admin_node_post', '操作结点数据库', 1, 3, 3, 'sensitive'),
(5, 0, '', '', 'admin_center', '团队管理中心', 1, 1, 1, ''),
(6, 0, 'AdminGroup', 'admin_group', 'admin_group_root', '职能管理', 3, 2, 5, ''),
(7, 0, 'AdminGroup', 'set_auth', 'admin_node_set_auth', '组权限配置', 2, 3, 6, 'sensitive'),
(8, 0, 'AdminUser', 'admin_user', 'admin_user_root', '管理员管理', 2, 2, 5, ''),
(9, 0, 'AdminUser', 'post', 'admin_user_post', '管理员职能变更', 1, 3, 8, 'sensitive'),
(100, 0, '', '', 'resource_center', '资源管理中心', 1, 1, 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `rbac_std_admin_user`
--

CREATE TABLE `rbac_std_admin_user` (
  `id` int(11) NOT NULL,
  `admin_group_id` int(11) NOT NULL,
  `username` char(60) NOT NULL,
  `password` char(60) NOT NULL,
  `email` char(60) NOT NULL,
  `last_login_time` char(60) DEFAULT NULL,
  `encrypt` char(10) DEFAULT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `last_login_ip` char(60) DEFAULT NULL,
  `login_num` char(60) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `rbac_std_admin_user`
--

INSERT INTO `rbac_std_admin_user` (`id`, `admin_group_id`, `username`, `password`, `email`, `last_login_time`, `encrypt`, `status`, `last_login_ip`, `login_num`) VALUES
(1, 1, 'admin', 'c93ccd78b2076528346216b3b2f701e6', '', '2021-07-07 01:54:47', '', 0, '91.164.223.56', '88');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rbac_std_admin_group`
--
ALTER TABLE `rbac_std_admin_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `rbac_std_admin_node`
--
ALTER TABLE `rbac_std_admin_node`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `name` (`unicode`);

--
-- Indexes for table `rbac_std_admin_user`
--
ALTER TABLE `rbac_std_admin_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `admin_group_id` (`admin_group_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `rbac_std_admin_group`
--
ALTER TABLE `rbac_std_admin_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `rbac_std_admin_node`
--
ALTER TABLE `rbac_std_admin_node`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
--
-- 使用表AUTO_INCREMENT `rbac_std_admin_user`
--
ALTER TABLE `rbac_std_admin_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 限制导出的表
--

--
-- 限制表 `rbac_std_admin_user`
--
ALTER TABLE `rbac_std_admin_user`
  ADD CONSTRAINT `rbac_std_admin_user_ibfk_1` FOREIGN KEY (`admin_group_id`) REFERENCES `rbac_std_admin_group` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
