# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.37)
# Database: LaiYogaDev
# Generation Time: 2018-01-15 16:01:13 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table Account
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Account`;

CREATE TABLE `Account` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `Name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `RecordType` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '瑜伽会所' COMMENT '瑜伽会所、企业客户',
  `Phone` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '联系号码',
  `Address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '地址',
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '该条是否已删除？',
  `OwnerId` int(11) DEFAULT NULL COMMENT '记录的OwnerId',
  `CreatedById` int(11) NOT NULL DEFAULT '0' COMMENT '创建人。系统字段，自动填写',
  `CreatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间。系统字段，自动填写',
  `LastModifiedById` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新人。系统字段，自动填写',
  `LastModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后更新时间。系统字段，自动填写',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='任何法律实体，例如一家瑜伽馆，一家公司。第1期先不用。';



# Dump of table Campaign
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Campaign`;

CREATE TABLE `Campaign` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `UniqueId` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '活动的唯一ID，用于定位活动',
  `Name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '活动的名称',
  `Type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '活动类型：工作坊、公开课、Retreat、户外、静修',
  `HostName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '主办方。可以是瑜伽馆，也可以是个人。',
  `IsFreeOfCharge` tinyint(1) NOT NULL DEFAULT '0' COMMENT '该活动是否是免费的活动？1:是 0不是',
  `MaxPerson` int(3) DEFAULT '0' COMMENT '最多允许的人数',
  `SignedUpPerson` int(3) DEFAULT '0' COMMENT '已报名的人数',
  `MinPerson` int(3) DEFAULT '0' COMMENT '最少的人数。少于这个人数自动取消活动。',
  `Description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '描述',
  `Poster` varchar(5000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '海报。保存的是PictureID，多个海报图片用英文逗号隔开。',
  `StartTime` datetime NOT NULL COMMENT '活动开始时间',
  `EndTime` datetime NOT NULL COMMENT '活动结束时间',
  `Location` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '活动地点的名称，例如：Pure Yoga环贸店',
  `Address` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '活动的具体地点，例如：上海市上海市徐汇区淮海中路999号',
  `Longitude` decimal(11,7) DEFAULT NULL COMMENT '活动地址的经度',
  `Latitude` decimal(11,7) DEFAULT NULL COMMENT '活动地址的纬度',
  `RegOpenTime` datetime NOT NULL COMMENT '报名开始时间',
  `RegCloseTime` datetime NOT NULL COMMENT '报名开始时间',
  `Status` tinyint(1) DEFAULT '0' COMMENT '活动状态：0草稿、1已发布、2:已开始、3已完成、4已取消',
  `IsHot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '热门活动？',
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '该条是否已删除？',
  `OwnerId` int(11) DEFAULT NULL COMMENT '记录的OwnerId',
  `CreatedById` int(11) NOT NULL DEFAULT '0' COMMENT '创建人。系统字段，自动填写',
  `CreatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间。系统字段，自动填写',
  `LastModifiedById` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新人。系统字段，自动填写',
  `LastModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后更新时间。系统字段，自动填写',
  PRIMARY KEY (`Id`),
  KEY `UniqueIdIndex` (`UniqueId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='任何一个活动，例如：工作坊、公开课、Retreat、户外、静修等。';

LOCK TABLES `Campaign` WRITE;
/*!40000 ALTER TABLE `Campaign` DISABLE KEYS */;

INSERT INTO `Campaign` (`Id`, `UniqueId`, `Name`, `Type`, `HostName`, `IsFreeOfCharge`, `MaxPerson`, `SignedUpPerson`, `MinPerson`, `Description`, `Poster`, `StartTime`, `EndTime`, `Location`, `Address`, `Longitude`, `Latitude`, `RegOpenTime`, `RegCloseTime`, `Status`, `IsHot`, `IsDeleted`, `OwnerId`, `CreatedById`, `CreatedDate`, `LastModifiedById`, `LastModifiedDate`)
VALUES
	(1,'sadsad','ss','s','',0,NULL,NULL,NULL,NULL,'1,2','0000-00-00 00:00:00','0000-00-00 00:00:00','','ssss',NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00',1,0,0,NULL,1,'2018-01-10 22:26:17',4,'2018-01-10 22:26:17'),
	(89,'e450d072e9ac64bf0037822afaa8992cf3f8504f','',NULL,'',0,NULL,NULL,NULL,'undefined',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,0,0,NULL,1,'2018-01-14 12:19:15',4,'2018-01-14 12:19:15'),
	(90,'9edffce907db627018650d77f71c75defaeff0af','',NULL,'',0,NULL,NULL,NULL,'undefined',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,0,0,NULL,4,'2018-01-14 12:19:15',4,'2018-01-14 12:19:15'),
	(91,'ae9078168b3f0c353f1df9de5d8060fa3b138968','',NULL,'',0,NULL,NULL,NULL,'undefined',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,0,0,NULL,4,'2018-01-14 12:19:15',4,'2018-01-14 12:19:15'),
	(92,'534d5e4fa596e548ef924e7f6747540425032b69','',NULL,'',0,NULL,NULL,NULL,'undefined',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,0,0,NULL,4,'2018-01-14 12:19:15',4,'2018-01-14 12:19:15'),
	(93,'b97656ae08eab49c3cb0d3146d72445e0a288222','asdasdasd','公开课','1212',0,50,0,0,'undefined',NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00','','sdsd',NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,0,0,NULL,4,'2018-01-14 12:32:36',4,'2018-01-14 12:32:36'),
	(94,'b17ff2300ce016cddb9cb915c6cad7e9f49389d1','',NULL,'',0,0,0,0,NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00','','',NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,0,0,NULL,4,'2018-01-14 12:52:15',4,'2018-01-14 12:52:15'),
	(95,'993f154af75150c1c5f4c55f2a0b9cfd18cf0b97','sd','公开课','sdsds',0,0,0,0,'sdfsdfsdf',NULL,'2018-01-14 00:00:00','2018-01-19 00:00:00','','sdsdddd',NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,0,0,NULL,4,'2018-01-14 12:57:47',4,'2018-01-14 12:57:47'),
	(96,'051967e5ecbc878d46278cf8e34696aa6819fd15','sfdsdfsd','公开课','rrrtt',0,30,0,0,'xcxcxcxcxcxc',NULL,'2018-01-14 00:00:00','2018-01-16 00:00:00','','assssssss',NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,0,0,NULL,4,'2018-01-14 12:59:03',4,'2018-01-14 12:59:03'),
	(97,'0609504a6b02413c7ae957362aa2fea791adb517','',NULL,'',0,0,0,0,NULL,NULL,'2018-01-15 00:00:00','2018-01-15 00:00:00','','',NULL,NULL,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,0,0,NULL,1,'2018-01-15 15:27:01',1,'2018-01-15 15:27:01');

/*!40000 ALTER TABLE `Campaign` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table CampaignFeedbackStudent
# ------------------------------------------------------------

DROP TABLE IF EXISTS `CampaignFeedbackStudent`;

CREATE TABLE `CampaignFeedbackStudent` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `CampaignId` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '活动的ID 关联UniqueId',
  `ContactId` int(11) NOT NULL COMMENT '填写反馈的User的ContactID',
  `UserId` int(11) DEFAULT NULL COMMENT '填写反馈的User的UserID',
  `AttendedCampaign` tinyint(1) NOT NULL DEFAULT '1' COMMENT '学生是否参加了活动？',
  `OverallRating` tinyint(2) DEFAULT NULL COMMENT '整体满意度：糟糕、一般、很好、推荐',
  `CampaignSummary` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '活动评价',
  `CampaignPhotos` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '活动图片。保存的是PictureID，多个图片用英文逗号隔开。',
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '该条是否已删除？',
  `OwnerId` int(11) DEFAULT NULL COMMENT '记录的OwnerId',
  `CreatedById` int(11) NOT NULL DEFAULT '0' COMMENT '创建人。系统字段，自动填写',
  `CreatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间。系统字段，自动填写',
  `LastModifiedById` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新人。系统字段，自动填写',
  `LastModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后更新时间。系统字段，自动填写',
  PRIMARY KEY (`Id`),
  KEY `CampaignIdIndex` (`CampaignId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='活动完成后，学生填写活动的小结。';



# Dump of table CampaignFeedbackTeacher
# ------------------------------------------------------------

DROP TABLE IF EXISTS `CampaignFeedbackTeacher`;

CREATE TABLE `CampaignFeedbackTeacher` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `CampaignId` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '活动的ID 关联UniqueId',
  `ContactId` int(11) NOT NULL COMMENT '填写反馈的User的ContactID',
  `UserId` int(11) DEFAULT NULL COMMENT '填写反馈的User的id',
  `CampaignCompleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '活动是否完成？',
  `PlatformRating` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '糟糕、一般、很好、推荐',
  `CampaignSummary` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '活动小结',
  `CampaignPhotos` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '活动结束后老师上载的图片，保存的是Picture记录的ID，多个图片用英文逗号隔开。',
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '该条是否已删除？',
  `OwnerId` int(11) DEFAULT NULL COMMENT '记录的OwnerId',
  `CreatedById` int(11) NOT NULL DEFAULT '0' COMMENT '创建人。系统字段，自动填写',
  `CreatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间。系统字段，自动填写',
  `LastModifiedById` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新人。系统字段，自动填写',
  `LastModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后更新时间。系统字段，自动填写',
  PRIMARY KEY (`Id`),
  KEY `CampaignIdIndex` (`CampaignId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='活动完成后，老师填写活动的小结。';



# Dump of table CampaignMember
# ------------------------------------------------------------

DROP TABLE IF EXISTS `CampaignMember`;

CREATE TABLE `CampaignMember` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `CampaignUniqueId` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '活动的ID 关联UniqueId',
  `CampaignId` int(11) DEFAULT NULL COMMENT '活动的自增ID',
  `OrderNo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单的ID',
  `UserId` int(11) DEFAULT NULL COMMENT '关联的用户ID',
  `ContactId` int(11) NOT NULL COMMENT '报名者User关联的ContactID',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1已提交、2已支付、0已取消',
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '该条是否已删除？',
  `OwnerId` int(11) DEFAULT NULL COMMENT '记录的OwnerId',
  `CreatedById` int(11) NOT NULL DEFAULT '0' COMMENT '创建人。系统字段，自动填写',
  `CreatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间。系统字段，自动填写',
  `LastModifiedById` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新人。系统字段，自动填写',
  `LastModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后更新时间。系统字段，自动填写',
  PRIMARY KEY (`Id`),
  KEY `OrderNoIndex` (`CampaignUniqueId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='活动参与者。每个报名参加Campaign的人。';

LOCK TABLES `CampaignMember` WRITE;
/*!40000 ALTER TABLE `CampaignMember` DISABLE KEYS */;

INSERT INTO `CampaignMember` (`Id`, `CampaignUniqueId`, `CampaignId`, `OrderNo`, `UserId`, `ContactId`, `Status`, `IsDeleted`, `OwnerId`, `CreatedById`, `CreatedDate`, `LastModifiedById`, `LastModifiedDate`)
VALUES
	(1,'sadsad',NULL,'',2,0,1,0,NULL,2,'2018-01-13 10:47:16',0,'2018-01-13 10:47:16'),
	(2,'sadsad',1,'1',4,2,1,0,NULL,4,'2018-01-15 11:34:56',4,'2018-01-15 11:34:56'),
	(3,'sadsad',1,'1',4,2,1,0,NULL,4,'2018-01-15 11:37:13',4,'2018-01-15 11:37:13');

/*!40000 ALTER TABLE `CampaignMember` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table CampaignShare
# ------------------------------------------------------------

DROP TABLE IF EXISTS `CampaignShare`;

CREATE TABLE `CampaignShare` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `CampaignId` int(11) NOT NULL COMMENT '活动的ID',
  `ShareFromUserId` int(11) DEFAULT NULL COMMENT '分享者的UserId',
  `ShareToUserId` int(11) DEFAULT NULL COMMENT '打开者的UserId',
  `OpenTimesCount` int(3) DEFAULT NULL COMMENT '用户打开了多少次？',
  `ShareMethod` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '微信分享、代言海报',
  `CampaignPoster` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '如果ShareMethod=代言海报，那么是哪一张Poster？这里保存的是PictureId',
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '该条是否已删除？',
  `OwnerId` int(11) DEFAULT NULL COMMENT '记录的OwnerId',
  `CreatedById` int(11) NOT NULL DEFAULT '0' COMMENT '创建人。系统字段，自动填写',
  `CreatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间。系统字段，自动填写',
  `LastModifiedById` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新人。系统字段，自动填写',
  `LastModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后更新时间。系统字段，自动填写',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户分享活动，每次有人打开的时候，写入这张表。第1期先不做。';



# Dump of table Contact
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Contact`;

CREATE TABLE `Contact` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `Name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `AccountId` int(11) DEFAULT '0' COMMENT '客户ID',
  `Mobile` int(11) DEFAULT '0' COMMENT '用户手机',
  `Email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '邮箱',
  `Sex` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '未知' COMMENT '男、女、未知',
  `Birthday` date DEFAULT NULL COMMENT '生日',
  `HomeAddress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '家庭住宅',
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '该条是否已删除？',
  `OwnerId` int(11) DEFAULT NULL COMMENT '记录的OwnerId',
  `CreatedById` int(11) NOT NULL DEFAULT '0' COMMENT '创建人。系统字段，自动填写',
  `CreatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间。系统字段，自动填写',
  `LastModifiedById` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新人。系统字段，自动填写',
  `LastModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后更新时间。系统字段，自动填写',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='客户数据。每个用户注册时，自动帮他生成一个联系人记录。我们也可以批量导入联系人。如果这是一个集团客户，那么需要关联到Account表。';



# Dump of table DiscountCode
# ------------------------------------------------------------

DROP TABLE IF EXISTS `DiscountCode`;

CREATE TABLE `DiscountCode` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `DiscountName` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '优惠码的名称',
  `DiscountCode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '优惠码',
  `CampaignId` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '活动Id',
  `AssignedToUserId` int(11) DEFAULT NULL COMMENT '这张优惠码分配给了哪个用户？',
  `DiscountType` tinyint(1) NOT NULL DEFAULT '1' COMMENT '优惠方式：1优惠金额、2优惠折扣、3免费',
  `DiscountAmount` decimal(16,2) DEFAULT '0.00' COMMENT '优惠金额',
  `DiscountPercent` decimal(2,1) DEFAULT '0.0' COMMENT '优惠折扣。保存的是例如 9、8.8、7.5 这样的数字。',
  `MaxUsage` int(9) NOT NULL DEFAULT '1' COMMENT '限制使用的次数',
  `UsedCount` int(9) DEFAULT '0' COMMENT '已使用次数',
  `StartTime` datetime NOT NULL COMMENT '开始时间',
  `EndTime` datetime NOT NULL COMMENT '结束时间',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '优惠码状态：0:未激活 1已激活',
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '该条是否已删除？',
  `OwnerId` int(11) DEFAULT NULL COMMENT '记录的OwnerId',
  `CreatedById` int(11) NOT NULL DEFAULT '0' COMMENT '创建人。系统字段，自动填写',
  `CreatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间。系统字段，自动填写',
  `LastModifiedById` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新人。系统字段，自动填写',
  `LastModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后更新时间。系统字段，自动填写',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `DiscountCodeUnique` (`DiscountCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='优惠码，每个活动可以创建一个或者多个优惠码。';

LOCK TABLES `DiscountCode` WRITE;
/*!40000 ALTER TABLE `DiscountCode` DISABLE KEYS */;

INSERT INTO `DiscountCode` (`Id`, `DiscountName`, `DiscountCode`, `CampaignId`, `AssignedToUserId`, `DiscountType`, `DiscountAmount`, `DiscountPercent`, `MaxUsage`, `UsedCount`, `StartTime`, `EndTime`, `Status`, `IsDeleted`, `OwnerId`, `CreatedById`, `CreatedDate`, `LastModifiedById`, `LastModifiedDate`)
VALUES
	(2,'优惠码测试1','9S51Q373','sadsad',NULL,3,10.00,0.0,10,1,'2018-01-01 00:00:00','2018-01-20 00:00:00',1,0,NULL,1,'2018-01-13 10:38:32',4,'2018-01-15 11:37:13'),
	(4,'优惠码测试2','9S51Q372','sadsad',NULL,2,0.00,7.8,10,0,'2018-01-01 00:00:00','2018-01-20 00:00:00',1,0,NULL,1,'2018-01-13 14:36:10',1,'2018-01-13 14:36:10'),
	(5,'rrrrrr','JL04716Q','sadsad',NULL,2,0.00,0.9,12,0,'2018-01-15 00:00:00','2018-01-16 00:00:00',1,0,NULL,1,'2018-01-14 17:09:58',1,'2018-01-14 17:09:58'),
	(6,'wewe','ND6TC401','sadsad',NULL,1,12.00,0.0,12,0,'2018-01-15 00:00:00','2018-01-16 00:00:00',1,0,NULL,1,'2018-01-14 17:10:53',1,'2018-01-14 17:10:53'),
	(7,'rrererer','1NCSRL0M','sadsad',NULL,1,12.00,0.0,13,0,'2018-01-15 00:00:00','2018-01-16 00:00:00',1,0,NULL,1,'2018-01-14 17:13:32',1,'2018-01-14 17:13:32');

/*!40000 ALTER TABLE `DiscountCode` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table File
# ------------------------------------------------------------

DROP TABLE IF EXISTS `File`;

CREATE TABLE `File` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `FileOriginalTitle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '上载图片时它本来的名称',
  `FileSystemTitle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '系统给每张上载的图片生成一个唯一的名称',
  `FileType` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '活动海报、老师小结图片、学生小结图片、老师代言海报、学生代言海报',
  `FilePath` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片在服务器上的路径',
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '该条是否已删除？',
  `OwnerId` int(11) DEFAULT NULL COMMENT '记录的OwnerId',
  `CreatedById` int(11) NOT NULL DEFAULT '0' COMMENT '创建人。系统字段，自动填写',
  `CreatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间。系统字段，自动填写',
  `LastModifiedById` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新人。系统字段，自动填写',
  `LastModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后更新时间。系统字段，自动填写',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='每次用户上载图片，都保存在这张表里。例如：活动海报、老师小结图片、学生小结图片、老师代言海报、学生代言海报';

LOCK TABLES `File` WRITE;
/*!40000 ALTER TABLE `File` DISABLE KEYS */;

INSERT INTO `File` (`Id`, `FileOriginalTitle`, `FileSystemTitle`, `FileType`, `FilePath`, `IsDeleted`, `OwnerId`, `CreatedById`, `CreatedDate`, `LastModifiedById`, `LastModifiedDate`)
VALUES
	(1,'','','2','upload/file_5a578999a46383.23863420.jpeg',0,NULL,1,'2018-01-11 15:58:17',1,'2018-01-11 15:58:17'),
	(2,'','','1','upload/file_5a5cc7a053b891.12793458.jpg',0,NULL,1,'2018-01-15 15:24:16',1,'2018-01-15 15:24:16'),
	(3,'','','1','upload/file_5a5cc7dc312497.27126126.jpg',0,NULL,1,'2018-01-15 15:25:16',1,'2018-01-15 15:25:16'),
	(4,'','','1','upload/file_5a5cc85531c8f3.46347286.jpg',0,NULL,1,'2018-01-15 15:27:17',1,'2018-01-15 15:27:17'),
	(5,'','','1','upload/file_5a5cc86b540777.88868172.jpg',0,NULL,1,'2018-01-15 15:27:39',1,'2018-01-15 15:27:39'),
	(6,'','','1','upload/file_5a5cc9080dbd76.25234296.jpg',0,NULL,1,'2018-01-15 15:30:16',1,'2018-01-15 15:30:16'),
	(7,'','','1','upload/file_5a5cc942b5adf7.96215128.jpg',0,NULL,1,'2018-01-15 15:31:14',1,'2018-01-15 15:31:14'),
	(8,'','','1','upload/file_5a5cca7dbe3382.93444186.jpg',0,NULL,1,'2018-01-15 15:36:29',1,'2018-01-15 15:36:29');

/*!40000 ALTER TABLE `File` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table Order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Order`;

CREATE TABLE `Order` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `OrderNo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单编号',
  `ContactId` int(11) unsigned NOT NULL COMMENT '购票人',
  `UserId` int(11) DEFAULT NULL COMMENT '购票 userID',
  `CampaignId` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '活动Unique ID',
  `OrderStatus` tinyint(1) NOT NULL COMMENT '订单状态：1已提交、2已支付、0已取消',
  `TicketId` int(11) unsigned NOT NULL COMMENT '门票',
  `TicketPrice` decimal(16,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '门票价格',
  `TicketQty` int(3) unsigned NOT NULL DEFAULT '1' COMMENT '门票数量',
  `TotalAmount` decimal(16,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总金额',
  `DiscountCodeId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '优惠码',
  `DiscountAmount` decimal(16,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
  `PayableAmount` decimal(16,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '应付金额',
  `PaidAmount` decimal(16,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实付金额',
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '该条是否已删除？',
  `OwnerId` int(11) DEFAULT NULL COMMENT '记录的OwnerId',
  `CreatedById` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建人。系统字段，自动填写',
  `CreatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间。系统字段，自动填写',
  `LastModifiedById` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新人。系统字段，自动填写',
  `LastModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后更新时间。系统字段，自动填写',
  PRIMARY KEY (`Id`),
  KEY `OrderNoIndex` (`OrderNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='订单。用户报名参加Campaign，产生一条Order记录。我们先不弄订单行，因为每次只能购买一张门票，就放在订单头上。';

LOCK TABLES `Order` WRITE;
/*!40000 ALTER TABLE `Order` DISABLE KEYS */;

INSERT INTO `Order` (`Id`, `OrderNo`, `ContactId`, `UserId`, `CampaignId`, `OrderStatus`, `TicketId`, `TicketPrice`, `TicketQty`, `TotalAmount`, `DiscountCodeId`, `DiscountAmount`, `PayableAmount`, `PaidAmount`, `IsDeleted`, `OwnerId`, `CreatedById`, `CreatedDate`, `LastModifiedById`, `LastModifiedDate`)
VALUES
	(1,'2018011349555756',2,2,'sadsad',0,2,100.00,1,100.00,0,0.00,100.00,0.00,0,NULL,2,'2018-01-13 14:30:09',2,'2018-01-13 14:30:09'),
	(2,'2018011399975210',2,2,'sadsad',0,2,100.00,1,100.00,0,0.00,100.00,0.00,0,NULL,2,'2018-01-13 14:37:48',2,'2018-01-13 14:37:48'),
	(3,'2018011353575157',2,2,'sadsad',0,2,100.00,1,100.00,0,0.00,100.00,0.00,0,NULL,2,'2018-01-13 14:39:33',2,'2018-01-13 14:39:33'),
	(4,'2018011310155995',2,2,'sadsad',0,2,150.00,1,150.00,4,10.00,140.00,0.00,0,NULL,2,'2018-01-13 14:41:34',2,'2018-01-13 14:41:34'),
	(5,'2018011398529910',2,2,'sadsad',0,2,150.00,1,150.00,4,10.00,140.00,0.00,0,NULL,2,'2018-01-13 14:54:51',2,'2018-01-13 14:54:51'),
	(13,'2018011548102481',2,4,'sadsad',1,2,150.00,1,0.00,2,0.00,0.00,0.00,0,NULL,4,'2018-01-15 11:34:56',4,'2018-01-15 11:34:56'),
	(14,'2018011557495599',2,4,'sadsad',1,2,150.00,1,0.00,2,0.00,0.00,0.00,0,NULL,4,'2018-01-15 11:37:13',4,'2018-01-15 11:37:13');

/*!40000 ALTER TABLE `Order` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table TeacherApplication
# ------------------------------------------------------------

DROP TABLE IF EXISTS `TeacherApplication`;

CREATE TABLE `TeacherApplication` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `ContactId` int(11) NOT NULL DEFAULT '0' COMMENT '老师的ID',
  `SubmittedTime` datetime NOT NULL COMMENT '用户提交申请的时间',
  `ApprovalTime` datetime NOT NULL COMMENT '后台批复的时间。批准或者拒绝的时间。',
  `Remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '审批时填写的备注',
  `Status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '已提交' COMMENT '老师申请的状态：已提交、已批准、已拒绝',
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '该条是否已删除？',
  `OwnerId` int(11) NOT NULL COMMENT '记录的OwnerId',
  `CreatedById` int(11) NOT NULL DEFAULT '0' COMMENT '创建人。系统字段，自动填写',
  `CreatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间。系统字段，自动填写',
  `LastModifiedById` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新人。系统字段，自动填写',
  `LastModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后更新时间。系统字段，自动填写',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用来保存申请成为老师的请求。用户每次提交，如果没有“已提交”的记录，那么就生成一条TecherApplication记录。';



# Dump of table Ticket
# ------------------------------------------------------------

DROP TABLE IF EXISTS `Ticket`;

CREATE TABLE `Ticket` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `Name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '门票名称',
  `CampaignId` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Campaign的ID',
  `Price` decimal(16,2) DEFAULT NULL COMMENT '价格',
  `StartTime` datetime NOT NULL COMMENT '门票开始时间',
  `EndTime` datetime NOT NULL COMMENT '门票结束时间',
  `Status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '1已生效、0已取消',
  `AndDiscountCode` tinyint(1) DEFAULT '0' COMMENT '是否和优惠券一起使用',
  `Level` tinyint(2) DEFAULT '1' COMMENT '首先使用的价格如早鸟价格 越大越优先',
  `IsDeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '该条是否已删除？',
  `OwnerId` int(11) DEFAULT NULL COMMENT '记录的OwnerId',
  `CreatedById` int(11) NOT NULL DEFAULT '0' COMMENT '创建人。系统字段，自动填写',
  `CreatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间。系统字段，自动填写',
  `LastModifiedById` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新人。系统字段，自动填写',
  `LastModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最后更新时间。系统字段，自动填写',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='活动门票。一个Campaign可以创建多种门票，例如：早鸟票、正常票。';

LOCK TABLES `Ticket` WRITE;
/*!40000 ALTER TABLE `Ticket` DISABLE KEYS */;

INSERT INTO `Ticket` (`Id`, `Name`, `CampaignId`, `Price`, `StartTime`, `EndTime`, `Status`, `AndDiscountCode`, `Level`, `IsDeleted`, `OwnerId`, `CreatedById`, `CreatedDate`, `LastModifiedById`, `LastModifiedDate`)
VALUES
	(1,'','sadsad',200.00,'2018-01-11 17:22:47','2018-02-13 17:22:47','1',0,1,0,NULL,0,'2018-01-13 17:22:47',0,'2018-01-13 17:22:47'),
	(2,'','sadsad',150.00,'2018-01-11 17:22:47','2018-01-20 17:22:47','1',1,2,0,NULL,0,'2018-01-13 17:24:25',0,'2018-01-13 17:24:25'),
	(31,'','e450d072e9ac64bf0037822afaa8992cf3f8504f',400.00,'2018-01-14 00:00:00','2018-01-14 00:00:00','1',0,1,0,NULL,4,'2018-01-14 12:19:15',4,'2018-01-14 12:19:15'),
	(32,'','e450d072e9ac64bf0037822afaa8992cf3f8504f',200.00,'2018-01-14 00:00:00','2018-01-14 00:00:00','1',0,2,0,NULL,4,'2018-01-14 12:19:15',4,'2018-01-14 12:19:15'),
	(33,'','9edffce907db627018650d77f71c75defaeff0af',400.00,'2018-01-14 00:00:00','2018-01-14 00:00:00','1',0,1,0,NULL,4,'2018-01-14 12:19:15',4,'2018-01-14 12:19:15'),
	(34,'','9edffce907db627018650d77f71c75defaeff0af',200.00,'2018-01-14 00:00:00','2018-01-14 00:00:00','1',0,2,0,NULL,4,'2018-01-14 12:19:15',4,'2018-01-14 12:19:15'),
	(35,'','ae9078168b3f0c353f1df9de5d8060fa3b138968',400.00,'2018-01-14 00:00:00','2018-01-14 00:00:00','1',0,1,0,NULL,4,'2018-01-14 12:19:15',4,'2018-01-14 12:19:15'),
	(36,'','ae9078168b3f0c353f1df9de5d8060fa3b138968',200.00,'2018-01-14 00:00:00','2018-01-14 00:00:00','1',0,2,0,NULL,4,'2018-01-14 12:19:15',4,'2018-01-14 12:19:15'),
	(37,'','534d5e4fa596e548ef924e7f6747540425032b69',400.00,'2018-01-14 00:00:00','2018-01-14 00:00:00','1',0,1,0,NULL,4,'2018-01-14 12:19:15',4,'2018-01-14 12:19:15'),
	(38,'','534d5e4fa596e548ef924e7f6747540425032b69',200.00,'2018-01-14 00:00:00','2018-01-14 00:00:00','1',0,2,0,NULL,4,'2018-01-14 12:19:15',4,'2018-01-14 12:19:15'),
	(39,'','b97656ae08eab49c3cb0d3146d72445e0a288222',200.00,'2018-01-14 01:01:00','2018-01-17 00:00:00','1',0,1,0,NULL,4,'2018-01-14 12:32:36',4,'2018-01-14 12:32:36'),
	(40,'','b97656ae08eab49c3cb0d3146d72445e0a288222',0.00,'2018-01-14 01:01:00','2018-01-15 01:01:00','1',1,2,0,NULL,4,'2018-01-14 12:32:36',4,'2018-01-14 12:32:36'),
	(41,'','b17ff2300ce016cddb9cb915c6cad7e9f49389d1',0.00,'2018-01-14 00:00:00','2018-01-14 00:00:00','1',0,1,0,NULL,4,'2018-01-14 12:52:15',4,'2018-01-14 12:52:15'),
	(42,'','b17ff2300ce016cddb9cb915c6cad7e9f49389d1',0.00,'2018-01-14 00:00:00','2018-01-14 00:00:00','1',0,2,0,NULL,4,'2018-01-14 12:52:15',4,'2018-01-14 12:52:15'),
	(43,'','993f154af75150c1c5f4c55f2a0b9cfd18cf0b97',300.00,'2018-01-14 00:00:00','2018-01-18 00:00:00','1',0,1,0,NULL,4,'2018-01-14 12:57:47',4,'2018-01-14 12:57:47'),
	(44,'','993f154af75150c1c5f4c55f2a0b9cfd18cf0b97',0.00,'2018-01-14 00:00:00','2018-01-15 00:00:00','1',0,2,0,NULL,4,'2018-01-14 12:57:47',4,'2018-01-14 12:57:47'),
	(45,'','051967e5ecbc878d46278cf8e34696aa6819fd15',200.00,'2018-01-14 00:00:00','2018-01-16 00:00:00','1',0,1,0,NULL,4,'2018-01-14 12:59:03',4,'2018-01-14 12:59:03'),
	(46,'','051967e5ecbc878d46278cf8e34696aa6819fd15',0.00,'2018-01-14 00:00:00','2018-01-14 00:00:00','1',1,2,0,NULL,4,'2018-01-14 12:59:03',4,'2018-01-14 12:59:03'),
	(47,'','0609504a6b02413c7ae957362aa2fea791adb517',0.00,'2018-01-15 00:00:00','2018-01-15 00:00:00','1',1,1,0,NULL,1,'2018-01-15 15:27:01',1,'2018-01-15 15:27:01'),
	(48,'','0609504a6b02413c7ae957362aa2fea791adb517',0.00,'2018-01-15 00:00:00','2018-01-15 00:00:00','1',0,2,0,NULL,1,'2018-01-15 15:27:01',1,'2018-01-15 15:27:01');

/*!40000 ALTER TABLE `Ticket` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table User
# ------------------------------------------------------------

DROP TABLE IF EXISTS `User`;

CREATE TABLE `User` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `Name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户姓名',
  `Nickname` varchar(255) CHARACTER SET utf32 NOT NULL COMMENT '为每个用户生成一个密码，需要先加密然后再保存。',
  `Username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '登陆账户名，直接用记录的ID。',
  `Password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '为每个用户生成一个密码，需要先加密然后再保存。',
  `Mobile` bigint(11) DEFAULT '0' COMMENT '用户手机',
  `Email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '邮箱',
  `Avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户头像URL',
  `WeChatOpenID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '微信OpenID，为了识别用户，每个用户针对每个公众号会产生一个安全的OpenID',
  `ContactID` int(11) DEFAULT '0' COMMENT '用户关联的Contact记录',
  `AccountID` int(11) DEFAULT '0' COMMENT '用户关联的Account记录',
  `UserType` tinyint(2) DEFAULT '1' COMMENT '1:学生 2：老师',
  `IsActive` tinyint(1) DEFAULT '1' COMMENT '1:激活状态 0：封号',
  `IsAxamine` tinyint(1) DEFAULT '0' COMMENT '1：已审核 0：未审核',
  `CreatedById` int(11) DEFAULT '0' COMMENT '创建人。系统字段，自动填写',
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间。系统字段，自动填写',
  `LastModifiedById` int(11) DEFAULT '0' COMMENT '最后更新人。系统字段，自动填写',
  `LastModifiedDate` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '最后更新时间。系统字段，自动填写',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='一个可以登陆系统的账号，用户注册时自动创建。';

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;

INSERT INTO `User` (`Id`, `Name`, `Nickname`, `Username`, `Password`, `Mobile`, `Email`, `Avatar`, `WeChatOpenID`, `ContactID`, `AccountID`, `UserType`, `IsActive`, `IsAxamine`, `CreatedById`, `CreatedDate`, `LastModifiedById`, `LastModifiedDate`)
VALUES
	(1,'sunny','','','',0,'0','','',0,0,1,1,0,0,'2017-12-27 22:38:45',0,'2017-12-27 22:38:45'),
	(2,'','sunny123','15216675457','',15216675457,'0','','123456',0,0,2,1,0,0,'2018-01-09 03:25:18',0,'2018-01-09 03:45:25'),
	(3,'','sunny123','','',0,'0','','',0,0,1,1,0,0,'2018-01-13 18:37:17',0,'2018-01-13 18:37:17'),
	(4,'','','15216675458','',15216675458,'0','','',0,0,2,1,0,2,'2018-01-14 05:10:50',2,'2018-01-14 05:10:50'),
	(5,'','','15216675459','',15216675459,'0','','',0,0,1,1,0,1,'2018-01-15 03:12:37',1,'2018-01-15 03:12:37');

/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
