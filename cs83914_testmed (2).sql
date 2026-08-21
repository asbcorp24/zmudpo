-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Авг 21 2026 г., 10:20
-- Версия сервера: 5.7.40-43
-- Версия PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `cs83914_testmed`
--

-- --------------------------------------------------------

--
-- Структура таблицы `nmo_otm_pos`
--

CREATE TABLE IF NOT EXISTS `nmo_otm_pos` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `razdel` int(11) DEFAULT NULL,
  `dat` datetime DEFAULT NULL,
  PRIMARY KEY (`num`),
  KEY `ur1` (`user`,`razdel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `nmo_test_pass`
--

CREATE TABLE IF NOT EXISTS `nmo_test_pass` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media_razd` int(11) DEFAULT NULL,
  `passw` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tip_nag`
--

CREATE TABLE IF NOT EXISTS `tip_nag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazv` char(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tmo_nmo_test_dat`
--

CREATE TABLE IF NOT EXISTS `tmo_nmo_test_dat` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `test` int(11) DEFAULT NULL,
  `dat` datetime DEFAULT NULL,
  `ip` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`num`),
  KEY `nt` (`num`,`test`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_addr_otprav`
--

CREATE TABLE IF NOT EXISTS `tm_addr_otprav` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `inn` int(11) DEFAULT NULL,
  `oblast` varchar(500) DEFAULT NULL,
  `rayon` varchar(500) DEFAULT NULL,
  `gorod` varchar(500) DEFAULT NULL,
  `dom` varchar(500) DEFAULT NULL,
  `kv` varchar(200) DEFAULT NULL,
  `Fam` varchar(500) DEFAULT NULL,
  `Name` varchar(500) DEFAULT NULL,
  `Otch` varchar(500) DEFAULT NULL,
  `ind` varchar(500) DEFAULT NULL,
  `comment` tinytext,
  `ulica` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_admin`
--

CREATE TABLE IF NOT EXISTS `tm_admin` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `user` char(250) DEFAULT NULL,
  `pass` char(250) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AVG_ROW_LENGTH=16384 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_arh_ball`
--

CREATE TABLE IF NOT EXISTS `tm_arh_ball` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` int(11) DEFAULT NULL,
  `inn` int(11) DEFAULT NULL,
  `nazv` char(250) DEFAULT NULL,
  `ball` int(11) DEFAULT NULL,
  `chas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inn` (`inn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_arh_diplom`
--

CREATE TABLE IF NOT EXISTS `tm_arh_diplom` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `spec` varchar(500) DEFAULT NULL,
  `fio` char(255) DEFAULT NULL,
  `path` char(255) DEFAULT NULL,
  `god` int(11) DEFAULT NULL,
  `sfio` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_arh_dop_sv`
--

CREATE TABLE IF NOT EXISTS `tm_arh_dop_sv` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `valstr` varchar(255) DEFAULT NULL,
  `valint` int(11) DEFAULT NULL,
  `inn` int(11) DEFAULT NULL,
  `valdat` date DEFAULT NULL,
  `typ` int(11) DEFAULT NULL,
  `nazv` varchar(255) DEFAULT NULL,
  `dop` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`),
  KEY `inn` (`inn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_arh_spec`
--

CREATE TABLE IF NOT EXISTS `tm_arh_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` int(11) DEFAULT NULL,
  `naz` varchar(255) DEFAULT NULL,
  `din` date DEFAULT NULL,
  `dout` date DEFAULT NULL,
  `chas` int(11) DEFAULT NULL,
  `god` int(11) DEFAULT NULL,
  `specs` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_chat_kurator`
--

CREATE TABLE IF NOT EXISTS `tm_chat_kurator` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `kurator` int(11) DEFAULT NULL,
  `razdel` int(11) DEFAULT NULL,
  `txt` varchar(500) DEFAULT NULL,
  `dat` datetime DEFAULT NULL,
  `k_pr` int(11) DEFAULT NULL,
  `u_pr` int(11) DEFAULT NULL,
  `ku` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_docs`
--

CREATE TABLE IF NOT EXISTS `tm_docs` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `spec` int(11) DEFAULT NULL,
  `path` char(255) DEFAULT NULL,
  `dat` date DEFAULT NULL,
  `nazv` char(255) DEFAULT NULL,
  `comment` char(255) DEFAULT NULL,
  `comm` varchar(255) DEFAULT NULL,
  `typ_doc` int(11) DEFAULT NULL,
  `img` char(255) NOT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_doc_spec`
--

CREATE TABLE IF NOT EXISTS `tm_doc_spec` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `doc` int(11) DEFAULT NULL,
  `spec` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_grupp`
--

CREATE TABLE IF NOT EXISTS `tm_grupp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazv` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_irab_def_sp`
--

CREATE TABLE IF NOT EXISTS `tm_irab_def_sp` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `irab_spec` int(11) DEFAULT NULL,
  `spec` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AVG_ROW_LENGTH=5461 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_irab_spec`
--

CREATE TABLE IF NOT EXISTS `tm_irab_spec` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `spec` varchar(255) DEFAULT NULL,
  `urov` int(11) DEFAULT '1' COMMENT '0 промежуточная\r\n1 итоговая',
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_irab_stud`
--

CREATE TABLE IF NOT EXISTS `tm_irab_stud` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `student` int(11) DEFAULT NULL,
  `itog_rab` int(11) DEFAULT NULL,
  `path` char(200) DEFAULT NULL,
  `antiplagiat` int(11) DEFAULT NULL,
  `result` int(11) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AVG_ROW_LENGTH=16384 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_irab_tem`
--

CREATE TABLE IF NOT EXISTS `tm_irab_tem` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `inn` int(11) DEFAULT NULL,
  `nazv` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AVG_ROW_LENGTH=5461 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_konf_user_files`
--

CREATE TABLE IF NOT EXISTS `tm_konf_user_files` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `media` int(11) DEFAULT NULL,
  `path` char(255) DEFAULT NULL,
  `name` char(255) DEFAULT NULL,
  `yname` char(255) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_konf_user_files_arh`
--

CREATE TABLE IF NOT EXISTS `tm_konf_user_files_arh` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `media` int(11) DEFAULT NULL,
  `path` char(255) DEFAULT NULL,
  `name` char(255) DEFAULT NULL,
  `yname` char(255) DEFAULT NULL,
  `old` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AVG_ROW_LENGTH=4096 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_login_dat`
--

CREATE TABLE IF NOT EXISTS `tm_login_dat` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `dat` datetime DEFAULT NULL,
  `dop` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_media`
--

CREATE TABLE IF NOT EXISTS `tm_media` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `inn` int(11) DEFAULT NULL,
  `path` char(255) DEFAULT NULL,
  `nazv` char(255) DEFAULT NULL,
  `spec` int(11) DEFAULT '-1',
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_media_spec`
--

CREATE TABLE IF NOT EXISTS `tm_media_spec` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `media` int(11) DEFAULT NULL,
  `spec` int(11) DEFAULT NULL,
  `comment` char(255) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_menu`
--

CREATE TABLE IF NOT EXISTS `tm_menu` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  `path` char(255) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_menu_adm`
--

CREATE TABLE IF NOT EXISTS `tm_menu_adm` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `inn` int(11) DEFAULT NULL,
  `menu` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_news`
--

CREATE TABLE IF NOT EXISTS `tm_news` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `nazv` char(250) DEFAULT NULL,
  `content` text,
  `img` char(255) DEFAULT NULL,
  `dat` date DEFAULT NULL,
  `avtor` int(11) NOT NULL,
  `publ` int(11) NOT NULL,
  `spec` int(11) NOT NULL,
  `inst` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_news_image`
--

CREATE TABLE IF NOT EXISTS `tm_news_image` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `news` int(11) DEFAULT NULL,
  `path` char(255) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_bil`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_bil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `mrazdel` int(11) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_pract`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_pract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `razdel` int(11) DEFAULT NULL,
  `dat` date DEFAULT NULL,
  `chto_del` text,
  `otvets` varchar(100) DEFAULT NULL,
  `old` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ur` (`id`,`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_prepod_dat`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_prepod_dat` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `nmo_prepod_spec` int(11) DEFAULT NULL,
  `dat` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `vm_chel` int(11) DEFAULT NULL,
  `nomer_zan` int(11) DEFAULT '0',
  `comment` text,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_prepod_spec`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_prepod_spec` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `prepod` int(11) DEFAULT NULL,
  `predmet` varchar(500) DEFAULT NULL,
  `spec` int(11) DEFAULT NULL,
  `kol_raz` int(11) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_razd`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_razd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `spec` int(11) NOT NULL,
  `nazv` varchar(500) NOT NULL,
  `activ` int(11) NOT NULL,
  `comment` text,
  `num` int(11) DEFAULT '0',
  `img` varchar(300) NOT NULL DEFAULT '0',
  `prepod` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_razd_dop_prepod`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_razd_dop_prepod` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `razdel` int(11) DEFAULT NULL,
  `prepod` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_razd_media`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_razd_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tm_nmo_razd` int(11) NOT NULL,
  `path` varchar(500) DEFAULT NULL,
  `tip` int(11) DEFAULT NULL COMMENT '2 видео;1 документ; 1 тесты;4 контроьные',
  `act` int(11) DEFAULT '1',
  `obyaz` int(11) DEFAULT '1' COMMENT 'Если тест то пока не пройдешь дальше не видно',
  `num` int(11) DEFAULT NULL COMMENT 'нумерация',
  `comment` text,
  `dop_file` varchar(500) DEFAULT NULL COMMENT 'тут ответы к тесту если есть',
  `nazv` varchar(500) DEFAULT NULL,
  `povt` int(11) DEFAULT '0' COMMENT 'Могут ли повторятся вопросы',
  `data_act` datetime DEFAULT NULL,
  `data_okon` datetime DEFAULT NULL,
  `avtor` int(11) DEFAULT NULL,
  `tippr` int(11) NOT NULL DEFAULT '0',
  `kvn` int(11) NOT NULL,
  `pop` int(11) DEFAULT '0',
  `gal` tinyint(1) NOT NULL DEFAULT '0',
  `passw` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ri` (`tm_nmo_razd`,`tip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_razd_media_list`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_razd_media_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tm_nmo_razd_media` int(11) DEFAULT NULL,
  `tex` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_razd_media_user_act_test`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_razd_media_user_act_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `razd_media_test` int(11) DEFAULT NULL,
  `act` int(11) DEFAULT NULL,
  `datact` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_razd_user`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_razd_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `razdel` int(11) NOT NULL,
  `proydeno` int(11) DEFAULT '0',
  `dop_file` varchar(500) DEFAULT NULL COMMENT 'ссылка на документы загруженные пользователем',
  `dat` datetime DEFAULT NULL,
  `dop` varchar(500) DEFAULT NULL,
  `sp` float DEFAULT NULL,
  `psp` float DEFAULT NULL,
  `pop` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_razd_user_arh`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_razd_user_arh` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `razdel` int(11) NOT NULL,
  `proydeno` int(11) DEFAULT '0',
  `dop_file` varchar(500) DEFAULT NULL COMMENT 'ссылка на документы загруженные пользователем',
  `dat` datetime DEFAULT NULL,
  `dop` varchar(500) DEFAULT NULL,
  `sp` int(11) DEFAULT NULL,
  `psp` int(11) DEFAULT NULL,
  `pop` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_sert_test`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_sert_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(500) DEFAULT NULL,
  `text` text,
  `media` int(11) DEFAULT NULL,
  `chas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_user_dat`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_user_dat` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `dat` int(11) DEFAULT NULL,
  `zan` int(11) DEFAULT NULL,
  `prepod_spec` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_user_file`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_user_file` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `tip` int(11) DEFAULT NULL,
  `path` char(255) DEFAULT NULL,
  `comment` text,
  `inn` int(11) DEFAULT NULL,
  `dat` datetime DEFAULT NULL,
  `opt` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`num`),
  KEY `user` (`user`,`inn`,`tip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_user_media_opl`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_user_media_opl` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `media_razd` int(11) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `dat` date DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_nmo_user_sam`
--

CREATE TABLE IF NOT EXISTS `tm_nmo_user_sam` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `stash` int(11) DEFAULT NULL,
  `path` char(255) DEFAULT NULL,
  `filename` char(255) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AVG_ROW_LENGTH=16384 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_obiav`
--

CREATE TABLE IF NOT EXISTS `tm_obiav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tex` text,
  `dat` date DEFAULT NULL,
  `expir` datetime DEFAULT NULL,
  `kurator` int(11) DEFAULT NULL,
  `spec` int(11) DEFAULT NULL,
  `grupp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tm_obiav_idx1` (`kurator`,`spec`,`grupp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_otziv`
--

CREATE TABLE IF NOT EXISTS `tm_otziv` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `dat` date DEFAULT NULL,
  `nazv` char(255) DEFAULT NULL,
  `img` char(255) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AVG_ROW_LENGTH=1365 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_pract`
--

CREATE TABLE IF NOT EXISTS `tm_pract` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_pract_temy`
--

CREATE TABLE IF NOT EXISTS `tm_pract_temy` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `inn` int(11) DEFAULT NULL,
  `zadanie` text,
  `file` varchar(255) DEFAULT NULL,
  `nazv_zad` char(255) DEFAULT NULL,
  `ball` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`),
  KEY `inn` (`inn`)
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_pract_temy_file`
--

CREATE TABLE IF NOT EXISTS `tm_pract_temy_file` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `inn` int(11) DEFAULT NULL,
  `path` char(255) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_prepod`
--

CREATE TABLE IF NOT EXISTS `tm_prepod` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `fio` char(255) DEFAULT NULL,
  `text` text,
  `foto` char(255) DEFAULT NULL,
  `tel` char(20) DEFAULT NULL,
  `mail` char(20) DEFAULT NULL,
  `predmet` char(255) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_prepod_spec`
--

CREATE TABLE IF NOT EXISTS `tm_prepod_spec` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `spec` int(11) DEFAULT NULL,
  `prepod` int(11) DEFAULT NULL,
  `predmet` varchar(500) NOT NULL,
  `t` int(11) NOT NULL,
  `kol_raz` int(11) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_sert`
--

CREATE TABLE IF NOT EXISTS `tm_sert` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `nazv` char(255) DEFAULT NULL,
  `path` char(255) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_spec`
--

CREATE TABLE IF NOT EXISTS `tm_spec` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `nazv` char(255) DEFAULT NULL,
  `dat` date DEFAULT NULL,
  `img` char(255) DEFAULT NULL,
  `actiiv` int(11) DEFAULT '0',
  `zap` int(11) NOT NULL DEFAULT '0',
  `kr` int(11) NOT NULL DEFAULT '0',
  `razdel` char(255) NOT NULL,
  `kategor` char(255) NOT NULL,
  `chas` int(11) NOT NULL,
  `cena` int(11) NOT NULL,
  `about` text,
  `gl` int(11) DEFAULT NULL,
  `sert` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AVG_ROW_LENGTH=4096 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_spec_dop`
--

CREATE TABLE IF NOT EXISTS `tm_spec_dop` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(500) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_spec_pract`
--

CREATE TABLE IF NOT EXISTS `tm_spec_pract` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `inn` int(11) DEFAULT NULL,
  `spec` int(11) DEFAULT NULL,
  `pract` int(11) DEFAULT NULL,
  `d_in` date DEFAULT NULL,
  `d_out` date DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_spec_test`
--

CREATE TABLE IF NOT EXISTS `tm_spec_test` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `inn` int(11) DEFAULT NULL,
  `tm_test` int(11) DEFAULT NULL,
  `nazvanie` varchar(255) DEFAULT NULL,
  `otv_col` int(11) DEFAULT NULL,
  `activ` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`),
  KEY `tm_spec_test_fk1` (`inn`)
) ENGINE=InnoDB AVG_ROW_LENGTH=3276 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_spec_type`
--

CREATE TABLE IF NOT EXISTS `tm_spec_type` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `nazv` char(255) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_spec_zn`
--

CREATE TABLE IF NOT EXISTS `tm_spec_zn` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `spec_dop` int(11) DEFAULT NULL,
  `znach` text,
  `spec` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_stat`
--

CREATE TABLE IF NOT EXISTS `tm_stat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(255) DEFAULT NULL,
  `sql` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_string_array`
--

CREATE TABLE IF NOT EXISTS `tm_string_array` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(500) DEFAULT NULL,
  `razd` int(11) DEFAULT NULL,
  `grupp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_string_array_grupp`
--

CREATE TABLE IF NOT EXISTS `tm_string_array_grupp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(250) DEFAULT NULL,
  `act` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_string_array_razdel`
--

CREATE TABLE IF NOT EXISTS `tm_string_array_razdel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazv` varchar(250) DEFAULT NULL,
  `act` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_teg`
--

CREATE TABLE IF NOT EXISTS `tm_teg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(150) DEFAULT NULL,
  `act` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_teg_img`
--

CREATE TABLE IF NOT EXISTS `tm_teg_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img` int(11) DEFAULT NULL,
  `tag` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_test`
--

CREATE TABLE IF NOT EXISTS `tm_test` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `inn` int(11) DEFAULT NULL,
  `path` char(255) DEFAULT NULL,
  `dat` date DEFAULT NULL,
  `nazv` char(255) DEFAULT NULL,
  `img` char(255) DEFAULT NULL,
  `col_v` int(11) DEFAULT NULL,
  `tex` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AVG_ROW_LENGTH=2730 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_typsv`
--

CREATE TABLE IF NOT EXISTS `tm_typsv` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `nazv` char(255) DEFAULT NULL,
  `typ` int(11) DEFAULT NULL,
  `poi` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_typsv_konf`
--

CREATE TABLE IF NOT EXISTS `tm_typsv_konf` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `nazv` char(255) DEFAULT NULL,
  `typ` int(11) DEFAULT NULL COMMENT '0 текст\r\n1 дата\r\n2 число\r\n3 файл',
  `poi` int(11) DEFAULT '0' COMMENT 'поиск подобных',
  `konf` int(11) DEFAULT NULL,
  `polosh` int(11) DEFAULT NULL COMMENT 'порядок в появлении на экране',
  `list` text,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AVG_ROW_LENGTH=630 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_typsv_konf_user`
--

CREATE TABLE IF NOT EXISTS `tm_typsv_konf_user` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `ank` int(11) DEFAULT NULL,
  `value` char(255) DEFAULT NULL,
  `razdel` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_typsv_name`
--

CREATE TABLE IF NOT EXISTS `tm_typsv_name` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='название типов сведений в группе' PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_user`
--

CREATE TABLE IF NOT EXISTS `tm_user` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `fio` char(255) DEFAULT NULL,
  `spec` int(11) DEFAULT NULL,
  `passw` char(255) DEFAULT NULL,
  `act` int(11) DEFAULT '0',
  `mail` varchar(200) DEFAULT NULL,
  `mail_pod` int(11) DEFAULT '0',
  `rss` int(11) DEFAULT '0',
  `data_nach` date DEFAULT NULL,
  `podgruppa` varchar(255) DEFAULT NULL,
  `zav` int(11) DEFAULT NULL,
  `opl` int(11) DEFAULT NULL,
  `urlico` int(11) DEFAULT NULL,
  `ur_parent` int(11) DEFAULT NULL,
  `post` int(11) DEFAULT NULL,
  `post_addr` char(255) DEFAULT NULL,
  `personal` int(11) DEFAULT NULL,
  `oplata` int(11) DEFAULT NULL,
  `data_opl` char(50) DEFAULT NULL,
  `grupp` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AVG_ROW_LENGTH=5461 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_user_obiav`
--

CREATE TABLE IF NOT EXISTS `tm_user_obiav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `obiav` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tm_user_obiav_idx1` (`user`,`obiav`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_user_pract`
--

CREATE TABLE IF NOT EXISTS `tm_user_pract` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `tema_pr` int(11) DEFAULT NULL,
  `tema` int(11) DEFAULT NULL,
  `file` char(255) DEFAULT NULL,
  `img` char(255) DEFAULT NULL,
  `res` int(11) DEFAULT NULL,
  `otv` text,
  PRIMARY KEY (`num`),
  UNIQUE KEY `num` (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_user_sh`
--

CREATE TABLE IF NOT EXISTS `tm_user_sh` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `media` int(11) DEFAULT NULL,
  `path` varchar(250) DEFAULT NULL,
  `dat` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tm_user_sh_idx1` (`user`,`media`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_user_sv`
--

CREATE TABLE IF NOT EXISTS `tm_user_sv` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `inn` int(11) DEFAULT NULL,
  `tm_typsv` int(11) DEFAULT NULL,
  `value` char(255) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Структура таблицы `tm_user_test`
--

CREATE TABLE IF NOT EXISTS `tm_user_test` (
  `num` int(11) NOT NULL AUTO_INCREMENT,
  `inn` int(11) DEFAULT NULL,
  `test` int(11) DEFAULT NULL,
  `res` int(11) DEFAULT NULL,
  `dat` date DEFAULT NULL,
  `otv_col` int(11) DEFAULT NULL,
  PRIMARY KEY (`num`)
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ts_arh_stud`
--

CREATE TABLE IF NOT EXISTS `ts_arh_stud` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` int(11) DEFAULT NULL,
  `fam` char(250) DEFAULT NULL,
  `name` char(250) DEFAULT NULL,
  `otch` char(250) DEFAULT NULL,
  `itog_rab` char(255) DEFAULT NULL,
  `crasn_reg` char(255) DEFAULT NULL,
  `inn` int(11) DEFAULT NULL,
  `nreg` char(255) DEFAULT NULL,
  `datav` date DEFAULT NULL,
  `protocol` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `tm_pract_temy`
--
ALTER TABLE `tm_pract_temy`
  ADD CONSTRAINT `new_pract_temy_fk1` FOREIGN KEY (`inn`) REFERENCES `tm_pract` (`num`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tm_spec_test`
--
ALTER TABLE `tm_spec_test`
  ADD CONSTRAINT `tm_spec_test_fk1` FOREIGN KEY (`inn`) REFERENCES `tm_spec` (`num`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
