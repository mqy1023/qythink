# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.26)
# Database: qythink
# Generation Time: 2020-03-15 13:43:56 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `config`;

CREATE TABLE `config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL COMMENT '名称',
  `type` int(11) NOT NULL COMMENT '配置类型 1网站配置，2公众号配置，3文件存储配置',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `value` text COMMENT '值',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;

INSERT INTO `config` (`id`, `name`, `type`, `title`, `value`, `remark`)
VALUES
	(1,'website',1,'网站名称','网站名称',NULL),
	(2,'http',1,'网站网址','网站网址',NULL),
	(3,'icp',1,'备案号','备案号',NULL),
	(4,'qq',1,'QQ','279371794',NULL),
	(5,'email',1,'email','279371794@qq.com',NULL),
	(6,'phone',1,'手机','13888888888',NULL),
	(7,'com_name',1,'公司名称','公司名称',NULL),
	(8,'com_address',1,'公司地址','公司地址',NULL),
	(9,'keyword',1,'网站关键字','网站关键字2',NULL),
	(10,'describe',1,'网站描述','网站描述2',NULL),
	(11,'com_intro',1,'公司简介','公司简介2',NULL),
	(16,'wx_name',2,'公众号名称','公众号名称',NULL),
	(17,'number',2,'微信号','微信号',NULL),
	(18,'appid',2,'微信开发者id','AppId',NULL),
	(19,'appsecret',2,'微信开发者秘钥','AppSecret',NULL),
	(20,'token',2,'当前token,用于微信接口调用','dfgdfmnghksdkjrtgidfnbniuret',NULL),
	(21,'token_time',2,'获取token时间','2020-01-03 22:14:41',NULL),
	(22,'token_url',2,'微信验证TOKEN','微信验证TOKEN',NULL),
	(23,'encoding_aeskey',2,'EncodingAESKey','',NULL),
	(24,'wx_img',2,'公众号二维码','http://qythink.com:7888/storage/uploads/20200315/761829dfb8fc78d84a6a8672c4995ffa.png',NULL),
	(29,'provider',3,'服务提供商','服务提供商1',NULL),
	(30,'accesskey',3,'accesskey','accessKey1',NULL),
	(31,'secretkey',3,'secretkey','secretkey1',NULL),
	(32,'endpoint',3,'endpoint','endpoint域名1',NULL);

/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table news
# ------------------------------------------------------------

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(11) DEFAULT '0' COMMENT '分类',
  `add_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `author` varchar(64) DEFAULT NULL COMMENT '作者',
  `label` varchar(64) DEFAULT NULL COMMENT '标签',
  `content` text COMMENT '内容',
  `is_del` tinyint(4) DEFAULT '0' COMMENT '0默认   1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='新闻表';

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;

INSERT INTO `news` (`id`, `type`, `add_time`, `title`, `author`, `label`, `content`, `is_del`)
VALUES
	(2,'科技2','2020-01-03 22:14:41','标题1','小黑','标签','内容7',1),
	(3,'科技3','2020-01-03 22:18:18','标题2','小黑','标签','内容8',1),
	(5,'科技1','2020-01-03 22:26:14','标题3','小黑','','内容9',1),
	(6,'金融3','2020-01-04 01:11:49','标题3','小黑','','内容10',1),
	(18,'科技','2020-01-04 01:13:00','标题1','234','32423','内容11',0),
	(30,'把你们','2020-01-04 01:14:56','更换','更换','钢化膜','内容1',0),
	(31,'23423','2020-01-04 01:15:01','42342','34234','2342342','内容2',1),
	(32,'43534','2020-01-04 01:15:08','456','54654','654654','内容3',1),
	(33,'18','2020-01-04 01:15:14','激活码','更换','更换','内容4',1),
	(34,'9','2020-01-04 15:31:35','标题1','小黑','标题1','内容5',1),
	(35,'9','2020-01-04 15:32:36','222','22','标签','内容6',0),
	(36,'17','2020-01-04 15:38:26','对方过后的','电饭锅','个人','内容6',0),
	(37,'8','2020-01-05 15:47:50','标题2','作者2','标签2','内容12',0),
	(38,'8','2020-01-05 15:52:03','标题','作者2','标签2','内容13',0),
	(39,'18','2020-01-05 15:53:46','标题2','作者2','标签','内容14',0),
	(40,'23','2020-01-05 16:53:58','2020年去哪儿旅游？看这一篇就够了，中国最美的地方','小黑','标签','2020，一起来旅行。2020年你知道哪个月份去中国哪里旅游最美吗？下面我们整理了一份中国最美旅游的月度攻略，带你去看看。一月去北极村看中国最美的冰雪世界，来一场极寒之旅。北极村是我国最北端边境小镇，坐落大兴安岭山脉北麓七星山脚下，隔黑龙江彼岸就是俄罗斯的阿穆尔州的伊格娜恩伊诺村。每年夏至前后，这儿有近二十个小时的可见太阳，如果是夜晚时分向北瞭望，天空泛白，恍若拂晓。有时异彩纷呈，艳丽多姿，这便是人们说的北极光。二月去海南看碧海蓝天，享受阳光与沙滩。二月里，大多数的城市都是寒风瑟瑟，此时的海南正以融融的阳光，广袤的椰林和白沙碧浪吸引着向往温暖的人们。而且在海南可参观的景点众多，如大东海，具有东方夏威夷之称亚龙湾沙滩，天涯海角，鹿回头山顶公园等等。三月去伊犁，漫山遍野的杏花、桃花，诠释着什么叫做世外桃源。如果说行走在新疆的信念是那抬头可见的自由放纵和展臂拥抱的四季风景，那么当冰雪消融时，伊犁就是它春天最美的样子，是那风摇叶子静待花开的绚烂。伴着袅袅炊烟，飘着淡淡清香，这里或许是世界上最幸福的地方，每年三月末到四月底，银色的雪山，嫩绿的草地，那粉白的杏花如烟似霞，仿佛是漂浮山间的绯红轻云，又如谪落凡尘的仙子，占尽春色。四月去苏州，烟雨朦胧，划着船，品着茶，梦回姑苏。苏州属亚热带季风性气候，气候温和、湿润，四季分明，全年皆宜旅游，尤以春天最佳。苏州温暖宜人的春天，无论是春光灿烂还是春雨缠绵，100多座大小园林内万物苏醒、生机勃勃，一幅动人画卷。五月去张家界，在十里画廊看落樱缤纷，站在栈道上俯瞰群山。张家界国家森林公园是全国著名的景区之一，也是许多游客来到张家界的首选目的地。这里险峰林立，气势雄伟，清晨，站在这里的最顶点，云烟缥缈的感觉让人仿佛置身于仙境。在这里，不仅有气势磅礴的山峰险峻，也有柔美的山海风光。你感受到的不止有惊险刺激，还有融入自然的美妙感觉。六月去丽江，在浪漫的古城上一喝杯小酒，在满是鲜花的民宿随意拍照。丽江古城景色秀美，有着独特的民族风俗。人们举行着奇特的仪式的景象热情奔放，让人忍不住的也想要融入其中。这座古城有山有水，风景秀丽这里的建筑就仿佛是迷宫一般，让来到这里的游客沉醉其中难以自拔。七月去内蒙古，草原湖泊，再来一只烤全羊。辽阔无边的大草原像是一块天工织就的绿色巨毯，走在草地上，那种柔软而富于弹性的感觉非常美妙。而绿草与蓝天相接处，牛羊相互追逐，牧人举鞭歌唱，处处都是“风吹草低见牛羊”的景致。八月去青海湖，看海天一色，在门源邂逅最美的油菜花。八月的青海湖是很美的，这里的水、这里的天是非常蓝的。而门源是比青海湖还美的一个地方。门源这里的油菜花是青藏高原的一块瑰宝，这里的景观是世界罕见的一个大奇观。这里的大片大片的花海都是开得非常的绚烂的，这一大片大片的花海真的是会让你震撼的。九月去九寨沟，层林浸染，看最美中国童话。秋天九寨沟进入颜值爆表模式，童话般的世界将迎来它最美的季节。秋高气爽，蓝天白云。静谧的海子像擦拭得一尘不染的玻璃，树木、青山在清澈的水中形成美丽的倒影，美到让人心醉。十月去婺源，秋天的婺源最有烟火气息，红色的枫叶与白色的瓦墙相映。树上的果子黄了，坡上的菊花黄了，山上的枫叶红了。婺源的建筑，有徽州建筑的沧桑与厚重，也有江南水乡的清新与哀愁。在秋色的一抹热烈中，一冰一火的碰撞，这时的婺源最有层次。十一月去喀纳斯，晨雾、湖泊、层林、村庄，这就是神的自留地。喀纳斯湖的周边都被山峰所包围，到处都是碧绿的树木，\"喀纳斯\"在蒙古语里面意为\"峡谷中的湖\"，也是由来于此。11月份的时候喀纳斯湖四周正是一片雪峰耸峙，绿坡墨林，山水相依，美不胜收的景色。十二月去长白山，看冰封天池，肆无忌惮在雪里狂欢。天池是一座休眠火山，火山口积水成湖，夏融池水比天还要蓝；冬冻冰面雪一样的白，被16座山峰环绕，仅在天豁峰和龙门峰间有一狭道池水溢出，飞泻成长白瀑布，是松花江的正源。天池像一块瑰丽的碧玉镶嵌在雄伟的长白山群峰之中，是中国最大的火山湖，也是世界上最深的高山湖泊。2020去旅行，你准备好了吗？',0),
	(41,'23','2020-01-05 23:12:29','浙江也有“九寨沟”，素有“天下第一水”的美称！网友：世外桃源','小黑','标签1','<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;霍山石斛发过火 霍山石斛发过火霍山石斛发过火霍山石斛发过火霍山石斛发过火霍山石斛发过火霍山石斛发过火霍山石斛发过火霍山石斛发过“醉翁之意不在酒，在乎山水之间也。”大文豪欧阳修曾在《醉翁亭记》中写下这句话，表达自己对山水美景的喜爱之情。在人类文明不断发展进步的今天，依然有一批与欧阳修一样的人，向往着山水田园之乐，想要逃离城市的喧嚣。位于浙江温州的楠溪江，是国家4A级景区，素有“天下第一水”的美称，很多来过这里的人也表示这里很像“九寨沟”，风景实在是漂亮，在这里，游客们都能找到心中的山水之乐。</p><p style=\"text-align: center;\"><img src=\"https://inews.gtimg.com/newsapp_bt/0/11168942321/1000\"></p><p style=\"text-align: center;\"><img src=\"https://inews.gtimg.com/newsapp_bt/0/11168942322/1000\"></p><p style=\"text-align: center;\"><img src=\"https://inews.gtimg.com/newsapp_bt/0/11168942323/1000\"></p><p>楠溪江的占地面积非常大，大大小小的景点总共有八百多个，短时间内这些景点肯定是没有办法一处一处游览的，挑选几处最值得观赏游玩的景点就可以了，既可以节省时间，也能让自己不枉此行。 狮子岩是楠溪江的一大奇景，来到这里的人无一不感叹造物主的鬼斧神工。凝灰岩在经过流水侵蚀和坍塌之后，形成具有垂直效果的崖。狮子岩全屿黑中透白，隔远看就像一座狮身石像，与另一座球状的小屿组成一个“狮子含球”的形状，除了用“奇”来形容，真的找不到别的形容词。</p><p style=\"text-align: center;\"><img src=\"https://inews.gtimg.com/newsapp_bt/0/11168942324/1000\"></p><p style=\"text-align: center;\"><img src=\"https://inews.gtimg.com/newsapp_bt/0/11168942325/1000\"></p><p style=\"text-align: center;\"><img src=\"https://inews.gtimg.com/newsapp_bt/0/11168942975/1000\"></p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;永嘉书院位于楠溪江的核心区域，有山、有水、有树、有石、有瀑布，可以说石集结了楠溪江所有的景致特征。永嘉书院内的植被非常丰富，进入书院，给人的第一印象就是绿意盎然，植被层次分明。溪水清澈见底，能够映衬出周围的建筑和树丛，一切都那么和谐安宁。 说是书院，当然以展示书香气息为主。书院中展览的书画颇多，在书画间摆设的一些石雕、案几都十分精致。进入室内，也是非常具有文墨气息的布置，从室内看向窗外，翠竹在风的鼓动下沙沙作响，雅趣十足。</p><p style=\"text-align: center;\"><img src=\"https://inews.gtimg.com/newsapp_bt/0/11168942976/1000\"></p><p>继续向东南方向行走，会看见百丈瀑。楠溪江东南部的断裂构造颇多，再加上山崖险峻，形成了五十多处瀑布。来到这里，你会见识到李白笔下“飞流直下三千尺，疑是银河落九天”的盛况，会在空谷幽响中获得更多精神上的满足。 楠溪江并非一个没有人迹的地方，这里的古村落是中国四大民居之一，有多达两百出古村落，说是一个古村落群完全不过分。位于楠溪江的古村落至今都保存着比较完整的历史痕迹，对于行家来说，就是一座有待深入考察的博物馆。不管是景色还是人文，对人的诱惑力都非常大。</p><p style=\"text-align: center;\"><img src=\"https://inews.gtimg.com/newsapp_bt/0/11168942977/1000\"></p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;来到楠溪江，也是吃货们的福利，这里有永嘉田鱼、沙岗粉干、楠溪素面等美食，保证你能大快朵颐，让味蕾享受最高级的待遇，一入楠溪终不悔！看到这里，留言告诉小编，你想去这里看看吗</p>',0),
	(42,'9','2020-01-05 23:16:47','这就是你向往的西安终南山隐居生活，看起来很美','小黑','标签2','当西安市民仍在等待第一场降雪的时候，秦岭的终南山已经下了六场雪。 为了满足女儿看雪的愿望，我和家人到秦岭终南山看雪，准备住在终南山的朋友家里，顺便体验隐居生活。我们还没有到达他的小院子， 他家的狗听到了我们的声音，跑去迎接我们，也许是这个家伙最近吃多了吧，所以我们误以为他是另一个隐士家庭的狗，最后叫他好名字，他看起来很胖。许多人羡慕山里的隐士，向往他们的隐居生活。确实，他们敢于远离世俗，敢于来到这里享受纯洁，敢于活出自己想要的样子，至少敢于勇敢，忠于自己..但并不是所有的南山隐居生活都像梅花的镜头那样浪漫和唯美。其实真正的隐居生活不是那么简单的。首先，你必须接受甚至享受独处。想象一下，一个人在山深处租了一个小院子，也许白天看到附近的一家人，夜晚的寂静，只看到星星，似乎很浪漫。如果各种鸟兽都哭了，那会是什么样的呢？假如遇到大风大雨怎么办呢，或者冬天遇到大雪封山，大风发出鬼哭狼嚎又怎么办呢，是什么样的感觉？也许想死的心已经有了。其他季节也可能是你自己在院子里点菜，买食物的好东西，但说到大山封雪，衣食可能是个问题。 今天，当我们走到山上时，我们给朋友带了一些蔬菜，下雪这些东西是最稀缺的。 她在这里早早给了我们准备一份好美食，一方面是展示一下她的好手艺，更重要的是他知道我爱吃，这是摇团做，只能说她这里没有别的蔬菜。吃完热拌团子后，全身暖和起来，那时女孩在院子里玩雪，捞鱼，和狗玩耍。 院子的池子里有几条大鱼，女儿用网捞上来，她突然兴奋起来，比她更兴奋的是狗，好像那也是久违了的精进，吓了我一跳，急忙让女儿把鱼放进池子里。一边欣赏着户外的雪，一边不沏一杯茶，怎么对得起这里的雪景，一边坐在雪地里喝着茶，一边欣赏着雪景，假如把这里拍成短视频，估计很多网友都会羡慕死..但其实，在室外的雪茶里，不到五分钟，就站不住了..一方面茶是冷的，另一方面，它真的太冷了。虽然山里不缺少柴火，这里主人很快为我们生火，开始烤火，泡茶，聊天，吃烤橘子，顺便欣赏雪景，看上去非常惬意。但实际上，由于没有木炭，我们用了柴火生火，柴火烟太呛人了，主人才叫朋友们赶紧停下来买木炭。其他时间我经常住在山里，毕竟在这里呆了十多年，还是很喜欢和向往的，这次是打算在终南山里住两天，体验一下这里的雪山的美，感受一下雪后的隐居生活，这次和女儿一起玩雪，不过她觉得这里太冷了，只能白天玩，晚上只能回到山脚下住，毕竟那里是取暖的地方。只能依依不舍地离开终南小院，告别的终南山的雪景，冒着雪徒步下山，还下山的时候，一部分区间已经被清洁工打扫干净了，但女儿今天在山上玩雪，捞鱼玩。 她说下一场雪就到秦岭终南山来玩雪',0),
	(43,'24','2020-02-29 20:50:57','金融市场','小黑哥','标签','<p>内容 内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容&nbsp;</p>',0),
	(44,'23','2020-02-29 20:51:52','23423','4234','23423','4234',0),
	(45,'8','2020-02-29 20:52:01','23423','23423','23423','234234',0),
	(46,'9','2020-02-29 20:52:11','23423','23423','423423','4234234',0),
	(47,'8','2020-02-29 20:52:20','23423','23423','4234','234234',0),
	(48,'9','2020-02-29 20:52:30','23423','423423','4234234','中国中国',0),
	(49,'8','2020-02-29 20:52:41','23423','423423','','23',0),
	(50,'24','2020-03-01 15:17:43','2343','2423','42343','23432',0),
	(51,'18','2020-03-01 16:52:08','345','34534','','34534534534534',0),
	(52,'3','2020-03-01 16:52:19','345','34534','5345','34534',0),
	(53,'3','2020-03-01 16:53:05','234','234','234234','23423234fg',0),
	(54,'20','2020-03-01 16:53:46','34543','5345','43','345343453534534',0),
	(55,'22','2020-03-01 16:54:06','bbb','4234','234','23423',0),
	(56,'18','2020-03-13 23:27:15','电影标题2','电影作者2','电影标签2','<h1><span style=\"font-weight: bold;\">电影内容2</span></h1>',0);

/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table news_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `news_type`;

CREATE TABLE `news_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_del` tinyint(4) DEFAULT '0' COMMENT '默认0  删除1',
  `p_id` int(11) DEFAULT NULL COMMENT '父级id',
  `title` varchar(32) DEFAULT '0' COMMENT '分类名称',
  `p_title` varchar(32) DEFAULT NULL,
  `level` varchar(11) DEFAULT '1' COMMENT '级别',
  `seq` int(11) DEFAULT NULL COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '是否显示  默认1显示  0不显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='新闻分类表';

LOCK TABLES `news_type` WRITE;
/*!40000 ALTER TABLE `news_type` DISABLE KEYS */;

INSERT INTO `news_type` (`id`, `is_del`, `p_id`, `title`, `p_title`, `level`, `seq`, `status`)
VALUES
	(1,1,0,'生活杂谈','顶级','1',1,0),
	(2,0,0,'旅游','顶级','1',2,0),
	(3,0,0,'财经','顶级','1',3,-1),
	(4,0,0,'时尚','顶级','1',4,0),
	(5,0,0,'科技','顶级','1',5,-1),
	(6,0,1,'名言名言','生活杂谈','1',6,0),
	(7,0,1,'好文转载','生活杂谈','1',7,0),
	(8,0,2,'东南亚旅游','旅游','1',2,0),
	(9,0,2,'日韩旅游','旅游','1',9,0),
	(10,0,1,'疑难片段','生活杂谈',NULL,0,0),
	(13,0,1,'常用链接','生活杂谈',NULL,1,-1),
	(17,0,0,'娱乐','顶级','',0,0),
	(18,0,17,'电影','娱乐','',1,0),
	(19,1,NULL,NULL,NULL,NULL,NULL,NULL),
	(20,0,17,'电视剧','娱乐',NULL,2,0),
	(21,0,17,'音乐','娱乐',NULL,3,0),
	(22,0,17,'明星','娱乐',NULL,4,0),
	(23,0,2,'国内旅游','旅游',NULL,1,0),
	(24,0,3,'金融市场','财经',NULL,1,0),
	(25,0,3,'证券市场','财经',NULL,2,0),
	(26,0,3,'理财资讯','财经','',3,0),
	(27,0,4,'奢侈珠宝','时尚',NULL,1,0),
	(28,0,4,'化妆护肤','时尚',NULL,2,0),
	(29,0,4,'潮流时装','时尚',NULL,3,0),
	(30,0,5,'互联网','科技',NULL,1,0),
	(31,0,5,'人工智能','科技',NULL,2,0),
	(32,0,5,'创业创新','科技','',3,0),
	(33,1,0,'111111','顶级',NULL,0,0);

/*!40000 ALTER TABLE `news_type` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sys_attach_file
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_attach_file`;

CREATE TABLE `sys_attach_file` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `add_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '上传时间',
  `file_code` varchar(64) CHARACTER SET utf8 NOT NULL COMMENT '业务id',
  `file_path` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '路径',
  `is_del` tinyint(4) DEFAULT '0' COMMENT '0默认  1删除',
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '文件真实名称',
  `add_user_id` int(11) DEFAULT NULL COMMENT '文件上传人',
  `file_size` bigint(20) DEFAULT NULL COMMENT '文件大小，KB单位',
  PRIMARY KEY (`id`),
  KEY `file_code` (`file_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='附件上传记录';



# Dump of table sys_dict
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_dict`;

CREATE TABLE `sys_dict` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_del` tinyint(4) DEFAULT '0' COMMENT '默认0，删除1',
  `title` varchar(64) DEFAULT NULL COMMENT '字典名称',
  `dict_code` varchar(64) DEFAULT NULL COMMENT '字典类型',
  `remark` varchar(64) DEFAULT NULL COMMENT '备注',
  `seq` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='数据字典';

LOCK TABLES `sys_dict` WRITE;
/*!40000 ALTER TABLE `sys_dict` DISABLE KEYS */;

INSERT INTO `sys_dict` (`id`, `is_del`, `title`, `dict_code`, `remark`, `seq`)
VALUES
	(1,1,'测试','ceshi','备注3',0),
	(3,1,'测试8888','策士8','备注2',0),
	(4,0,'新闻分类','news_type','',1),
	(5,0,'用户类型','user_type','',2),
	(6,1,'字典3','haha','23',0);

/*!40000 ALTER TABLE `sys_dict` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sys_dict_detail
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_dict_detail`;

CREATE TABLE `sys_dict_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_del` tinyint(4) DEFAULT '0' COMMENT '默认0，删除1',
  `dict_code` varchar(64) DEFAULT NULL COMMENT 'sys_dict表中的dict_code',
  `name` varchar(64) DEFAULT NULL COMMENT '名称',
  `code` varchar(64) DEFAULT NULL COMMENT '数据值',
  `extra_code` varchar(64) DEFAULT NULL COMMENT '扩展标签',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `seq` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(2) DEFAULT '0' COMMENT '0默认 -1禁止',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='数字字典详情';

LOCK TABLES `sys_dict_detail` WRITE;
/*!40000 ALTER TABLE `sys_dict_detail` DISABLE KEYS */;

INSERT INTO `sys_dict_detail` (`id`, `is_del`, `dict_code`, `name`, `code`, `extra_code`, `remark`, `seq`, `status`)
VALUES
	(2,1,'ceshi','名称','5555','','备注',1,-1),
	(3,1,'ceshi','2342','666','','234',0,0),
	(4,0,'ceshi','名称222','00','','备注',2,0),
	(5,0,'user_type','超级管理员','1',NULL,'',1,0),
	(6,0,'user_type','管理员','2',NULL,'',2,0),
	(7,0,'user_type','普通会员','3',NULL,'',3,0),
	(8,0,'news_type','科技新闻','1','','',1,0),
	(9,0,'news_type','金融','2',NULL,'',2,0),
	(10,0,'news_type','娱乐','3','','',3,0);

/*!40000 ALTER TABLE `sys_dict_detail` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sys_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_log`;

CREATE TABLE `sys_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `add_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '操作时间',
  `add_user_id` int(11) DEFAULT NULL COMMENT '操作人id',
  `add_user_name` varchar(64) DEFAULT NULL COMMENT '操作人',
  `type` int(2) DEFAULT '0' COMMENT '0系统自动添加  1手动添加',
  `key_code` varchar(64) DEFAULT NULL COMMENT '操作该条记录的id',
  `ip` varchar(64) DEFAULT NULL COMMENT '操作电脑ip',
  `address` varchar(64) DEFAULT NULL COMMENT '操作地址',
  `descc` varchar(255) DEFAULT NULL COMMENT '描述',
  `content` text COMMENT '内容',
  `module` varchar(64) DEFAULT NULL COMMENT '模块',
  `controller` varchar(64) DEFAULT NULL COMMENT '控制器',
  `action` varchar(64) DEFAULT NULL COMMENT '方法',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统操作日志';

LOCK TABLES `sys_log` WRITE;
/*!40000 ALTER TABLE `sys_log` DISABLE KEYS */;

INSERT INTO `sys_log` (`id`, `add_time`, `add_user_id`, `add_user_name`, `type`, `key_code`, `ip`, `address`, `descc`, `content`, `module`, `controller`, `action`)
VALUES
	(80,'2020-03-13 23:23:55',10,NULL,0,'75','127.0.0.1','',NULL,'{\"id\":75}','admin','syslog','del'),
	(81,'2020-03-13 23:27:16',10,NULL,0,'','127.0.0.1','','修改新闻','{\"type\":18,\"title\":\"电影标题1\",\"author\":\"电影作者1\",\"label\":\"电影标签1\",\"op\":\"add\",\"content\":\"<h1><span style=\\\"font-weight: bold;\\\">电影内容<\\/span><\\/h1>\"}','admin','news','addedit'),
	(82,'2020-03-13 23:27:52',10,NULL,0,'56','127.0.0.1','','修改新闻','{\"id\":56,\"type\":[\"18\"],\"add_time\":\"2020-03-13 23:27:15\",\"title\":\"电影标题2\",\"author\":\"电影作者2\",\"label\":\"电影标签2\",\"content\":\"<h1><span style=\\\"font-weight: bold;\\\">电影内容2<\\/span><\\/h1>\",\"is_del\":0,\"type_name\":\"电影\",\"_index\":0,\"_rowKey\":23,\"op\":\"edit\"}','admin','news','addedit'),
	(83,'2020-03-14 14:56:03',10,NULL,0,'','127.0.0.1','',NULL,'[]','index','login','logout'),
	(84,'2020-03-14 14:59:58',0,NULL,0,'','127.0.0.1','',NULL,'{\"account\":\"shenhuajuan\",\"password\":\"123456\"}','index','login','login'),
	(85,'2020-03-14 16:17:50',10,NULL,0,'','127.0.0.1','',NULL,'[]','index','login','logout'),
	(86,'2020-03-14 16:26:20',0,NULL,0,'','127.0.0.1','',NULL,'{\"account\":\"shenhuajuan\",\"password\":\"123456\"}','index','login','login'),
	(87,'2020-03-15 16:58:09',0,NULL,0,'','127.0.0.1','',NULL,'{\"account\":\"shenhuajuan\",\"password\":\"123456\"}','index','login','login'),
	(88,'2020-03-15 19:13:58',0,NULL,0,'','127.0.0.1','',NULL,'[]','index','index','uploadfile'),
	(89,'2020-03-15 19:15:24',0,NULL,0,'','127.0.0.1','',NULL,'[]','index','index','uploadfile'),
	(90,'2020-03-15 19:16:21',0,NULL,0,'','127.0.0.1','',NULL,'[]','index','index','uploadfile'),
	(91,'2020-03-15 19:16:50',0,NULL,0,'','127.0.0.1','',NULL,'[]','index','index','uploadfile'),
	(92,'2020-03-15 19:19:11',0,NULL,0,'','127.0.0.1','',NULL,'[]','index','index','uploadfile'),
	(93,'2020-03-15 19:26:00',0,NULL,0,'','127.0.0.1','',NULL,'[]','index','index','uploadfile'),
	(94,'2020-03-15 20:06:34',10,NULL,0,'','127.0.0.1','',NULL,'[]','index','login','logout'),
	(95,'2020-03-15 20:06:58',0,NULL,0,'','127.0.0.1','',NULL,'{\"account\":\"shenhuajuan\",\"password\":\"123456\"}','index','login','login'),
	(96,'2020-03-15 20:07:02',10,NULL,0,'','127.0.0.1','',NULL,'[]','index','login','logout'),
	(97,'2020-03-15 20:08:22',0,NULL,0,'','127.0.0.1','',NULL,'{\"account\":\"shenhuajuan\",\"password\":\"123456\"}','index','login','login'),
	(98,'2020-03-15 20:28:23',10,NULL,0,'','127.0.0.1','',NULL,'[]','index','login','logout'),
	(99,'2020-03-15 20:28:27',0,NULL,0,'','127.0.0.1','',NULL,'{\"account\":\"shenhuajuan\",\"password\":\"123456\"}','index','login','login'),
	(100,'2020-03-15 20:41:41',10,NULL,0,'','127.0.0.1','',NULL,'[]','index','login','logout'),
	(101,'2020-03-15 20:41:44',0,NULL,0,'','127.0.0.1','',NULL,'{\"account\":\"test\",\"password\":\"123456\"}','index','login','login'),
	(102,'2020-03-15 20:46:29',10,NULL,0,'','127.0.0.1','',NULL,'[]','index','login','logout'),
	(103,'2020-03-15 20:46:43',0,NULL,0,'','127.0.0.1','',NULL,'{\"account\":\"test\",\"password\":\"123456\"}','index','login','login'),
	(104,'2020-03-15 20:46:58',10,NULL,0,'','127.0.0.1','',NULL,'[]','index','login','logout'),
	(105,'2020-03-15 20:47:01',0,NULL,0,'','127.0.0.1','',NULL,'{\"account\":\"test\",\"password\":\"123456\"}','index','login','login'),
	(106,'2020-03-15 20:47:25',10,NULL,0,'','127.0.0.1','',NULL,'[]','index','login','logout'),
	(107,'2020-03-15 20:47:30',0,NULL,0,'','127.0.0.1','',NULL,'{\"account\":\"test\",\"password\":\"123456\"}','index','login','login'),
	(108,'2020-03-15 20:47:59',10,NULL,0,'','127.0.0.1','',NULL,'[]','index','login','logout'),
	(109,'2020-03-15 20:49:26',0,NULL,0,'','127.0.0.1','',NULL,'{\"account\":\"test\",\"password\":\"123456\"}','index','login','login');

/*!40000 ALTER TABLE `sys_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sys_menu
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_menu`;

CREATE TABLE `sys_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(64) NOT NULL COMMENT '名称',
  `p_id` int(11) DEFAULT '0' COMMENT '父级id',
  `type` int(2) DEFAULT '1' COMMENT ' 1一级菜单 2二级菜单  3操作按钮',
  `level` varchar(11) DEFAULT NULL,
  `path` varchar(64) DEFAULT '' COMMENT '路径   用于后台权限控制',
  `module` varchar(64) DEFAULT NULL COMMENT '模块   用于后台权限控制',
  `controller` varchar(64) DEFAULT NULL COMMENT '控制器   用于后台权限控制',
  `action` varchar(64) DEFAULT NULL COMMENT '方法   用于后台权限控制',
  `route_english` varchar(64) DEFAULT NULL COMMENT '路由英文名',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标,只在菜单中起作用',
  `component` varchar(64) DEFAULT NULL COMMENT '前端组件',
  `url` varchar(50) DEFAULT NULL COMMENT '第三方网页链接',
  `seq` float(11,3) DEFAULT NULL COMMENT '排序',
  `status` int(2) DEFAULT '0' COMMENT '0启用 -1禁用',
  `is_del` tinyint(4) DEFAULT '0' COMMENT '默认0，删除1',
  `expand` tinyint(4) DEFAULT '0' COMMENT '前端需要1,不需要0',
  `button_type` varchar(32) DEFAULT NULL COMMENT '按钮权限类型',
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='菜单 模块/控制器/方法表';

LOCK TABLES `sys_menu` WRITE;
/*!40000 ALTER TABLE `sys_menu` DISABLE KEYS */;

INSERT INTO `sys_menu` (`id`, `title`, `p_id`, `type`, `level`, `path`, `module`, `controller`, `action`, `route_english`, `icon`, `component`, `url`, `seq`, `status`, `is_del`, `expand`, `button_type`)
VALUES
	(10,'新闻管理',0,1,'1','',NULL,NULL,NULL,'/news','ios-albums','','',0.000,0,0,1,''),
	(11,'系统管理',0,1,'1','',NULL,NULL,NULL,'/sys','ios-cog','','',99.000,0,0,0,''),
	(12,'新闻列表',10,2,'2','admin/news/lst','admin','News','lst','list_news','ios-cloud-done','news/list_news','',1.000,0,0,1,''),
	(13,'修改新闻',12,3,'3','admin/news/addEdit','admin','News','addEdit','edit','','','',1.000,0,0,0,''),
	(18,'新闻分类',10,2,'2','admin/news/lstType','admin','News','lstType','news_type','md-document','news/news_type','',2.000,0,0,0,''),
	(19,'添加新闻',12,3,'3','admin/news/addEdit','admin','News','addEdit','','','','',0.000,0,0,0,''),
	(20,'用户列表',11,2,'2','admin/SysUser/lst','admin','SysUser','lst','list_user','ios-contact','sys/user/list_user','',0.000,0,0,0,''),
	(21,'角色权限',11,2,'2','admin/role/lst','admin','Role','lst','list_role','md-home','sys/role/list_role','',2.000,0,0,0,''),
	(22,'菜单权限',11,2,'2','admin/SysMenu/lst','admin','SysMenu','lst','list_menu','md-paper-plane','sys/menu/list_menu','',3.000,0,0,0,''),
	(23,'数字字典',11,2,'2','admin/SysDict/lstDict','admin','SysDict','lstDict','list_dict','ios-unlock','sys/dict/list_dict','',4.000,0,0,0,''),
	(24,'系统配置',11,2,'2','admin/Config/getSysConfig','admin','Config','getSysConfig','setting','ios-settings','sys/setting/setting','',5.000,0,0,0,''),
	(26,'表单分页',0,1,'1','',NULL,NULL,NULL,'table_page','ios-arrow-dropdown-circle',NULL,NULL,0.000,0,1,0,NULL),
	(27,'删除新闻',12,3,'3','admin/news/del','admin','News','del','','','','',3.000,0,0,NULL,''),
	(28,'添加分类',18,3,'3','admin/news/addEditType','admin','News','addEditType','','','','',0.000,0,0,0,''),
	(29,'修改分类',18,3,'3','admin/news/addEditType','admin','News','addEditType','','','','',0.000,0,0,0,''),
	(30,'删除分类',18,3,'3','admin/news/delType','admin','News','delType','','','','',0.000,0,0,0,''),
	(31,'添加用户',20,3,'3',NULL,NULL,NULL,NULL,'','','',NULL,0.000,0,0,0,''),
	(32,'修改用户',20,3,'3',NULL,NULL,NULL,NULL,'','','',NULL,0.000,0,0,0,''),
	(33,'删除用户',20,3,'3',NULL,NULL,NULL,NULL,'','','',NULL,0.000,0,0,0,''),
	(34,'添加角色',21,3,'3',NULL,NULL,NULL,NULL,'','','',NULL,0.000,0,0,0,''),
	(35,'修改角色',21,3,'3',NULL,NULL,NULL,NULL,'','','',NULL,0.000,0,0,0,''),
	(36,'删除角色',21,3,'3',NULL,NULL,NULL,NULL,'','','',NULL,0.000,0,0,0,''),
	(37,'编辑菜单',21,3,'3',NULL,NULL,NULL,NULL,'','','',NULL,0.000,0,0,0,''),
	(38,'添加菜单',22,3,'3',NULL,NULL,NULL,NULL,'','','',NULL,0.000,0,0,0,''),
	(39,'编辑菜单',22,3,'3',NULL,NULL,NULL,NULL,'','','',NULL,0.000,0,0,0,''),
	(40,'删除菜单',22,3,'3',NULL,NULL,NULL,NULL,'','','',NULL,0.000,0,0,0,''),
	(41,'添加字典',23,3,'3',NULL,NULL,NULL,NULL,'','','',NULL,0.000,0,0,0,''),
	(42,'修改字典',23,3,'3',NULL,NULL,NULL,NULL,'','','',NULL,0.000,0,0,0,''),
	(43,'删除字典',23,3,'3',NULL,NULL,NULL,NULL,'','','',NULL,0.000,0,0,0,''),
	(44,'修改系统配置',24,3,'3',NULL,NULL,NULL,NULL,'','','',NULL,0.000,0,0,0,''),
	(45,'操作日志',0,1,'1','','','','','/source','ios-cafe','','',3.000,0,0,0,''),
	(46,'日志',45,2,'2','','','','','list','md-cafe','source/log','',0.000,0,0,0,'');

/*!40000 ALTER TABLE `sys_menu` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sys_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_role`;

CREATE TABLE `sys_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `add_time` datetime DEFAULT NULL COMMENT '添加时间',
  `is_del` tinyint(4) DEFAULT '0' COMMENT '0默认 1删除',
  `name` varchar(64) NOT NULL COMMENT '权限组名',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `role_ids` text COMMENT 'sys_menu表中的id组合 菜单权限',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='权限组';

LOCK TABLES `sys_role` WRITE;
/*!40000 ALTER TABLE `sys_role` DISABLE KEYS */;

INSERT INTO `sys_role` (`id`, `add_time`, `is_del`, `name`, `remark`, `role_ids`)
VALUES
	(1,'2020-02-13 23:37:50',0,'超级管理员','开发者','10,12,13,19,18,11,20,21'),
	(2,'2020-02-22 23:37:54',0,'管理员','备注','10,12,13,19,27,18,28,29,30,11,20,31,32,33,21,34,35,36,37,22,38,39,40,23,41,42,43'),
	(8,'2020-02-22 23:38:08',0,'开发者','备注6','10,12,11,20,21,22,23,24,45');

/*!40000 ALTER TABLE `sys_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sys_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sys_user`;

CREATE TABLE `sys_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `add_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `status` int(2) DEFAULT '0' COMMENT '0默认  停用1',
  `is_del` tinyint(4) DEFAULT '0' COMMENT '0默认   1删除',
  `name` varchar(255) NOT NULL COMMENT '用户名',
  `account` varchar(64) DEFAULT NULL COMMENT '账号',
  `password` varchar(32) DEFAULT NULL COMMENT '密码',
  `role_id` int(11) NOT NULL COMMENT '角色id sys_role表中的id',
  `mobile` varchar(32) DEFAULT NULL COMMENT '手机',
  `email` varchar(32) DEFAULT NULL COMMENT '邮箱',
  `sex` tinyint(4) DEFAULT '0' COMMENT '0男  1女  2未知',
  `head_img` varchar(128) DEFAULT NULL COMMENT '头像',
  `depart_id` int(11) DEFAULT NULL COMMENT '部门id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

LOCK TABLES `sys_user` WRITE;
/*!40000 ALTER TABLE `sys_user` DISABLE KEYS */;

INSERT INTO `sys_user` (`id`, `add_time`, `status`, `is_del`, `name`, `account`, `password`, `role_id`, `mobile`, `email`, `sex`, `head_img`, `depart_id`)
VALUES
	(10,'2020-03-14 22:01:27',0,0,'测试1','test','3ce19592e1d7a145e4aefeada173d7d2',1,'13132787221','',1,'https://profile.csdnimg.cn/4/5/7/3_mqy1023',0);

/*!40000 ALTER TABLE `sys_user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
