# $Author: blogware $
# $Date: 2003/07/04 20:55:59 $
# $Revision: 1.2 $

# Banco de Dados : `blogware`

# Estrutura da tabela `BW_BANNED`

CREATE TABLE `BW_BANNED` (
  `ID` int(14) NOT NULL default '0',
  `USERIP` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) TYPE=MyISAM;


# Estrutura da tabela `BW_CHAT`

CREATE TABLE `BW_CHAT` (
  `ID` int(14) NOT NULL default '0',
  `USERID` int(14) NOT NULL default '0',
  `LABEL` varchar(250) default NULL,
  `MESSAGE` text NOT NULL,
  PRIMARY KEY  (`ID`)
) TYPE=MyISAM;


# Estrutura da tabela `BW_COMMENTS`

CREATE TABLE `BW_COMMENTS` (
  `ID` int(14) NOT NULL default '0',
  `POSTID` int(14) NOT NULL default '0',
  `USERID` int(14) NOT NULL default '0',
  `LABEL` varchar(250) default NULL,
  `TITLE` varchar(250) default NULL,
  `MESSAGE` text NOT NULL,
  `TYPE` varchar(100) NOT NULL default '',
  `USERIP` varchar(100) NOT NULL default '',
  `PUBLISH` int(1) NOT NULL default '1',
  PRIMARY KEY  (`ID`)
) TYPE=MyISAM;


# Estrutura da tabela `BW_FILES`

CREATE TABLE `BW_FILES` (
  `ID` int(14) NOT NULL default '0',
  `TITLE` varchar(200) NOT NULL default '',
  `URL` varchar(200) NOT NULL default '',
  `DESCRIPTION` text,
  `TYPE` varchar(100) NOT NULL default '',
  `SIZE` int(12) unsigned NOT NULL default '0',
  `MIMETYPE` varchar(200) default NULL,
  `CONTENT` blob,
  PRIMARY KEY  (`ID`)
) TYPE=MyISAM;


# Estrutura da tabela `BW_LINKS`

CREATE TABLE `BW_LINKS` (
  `ID` int(14) NOT NULL default '0',
  `URL` varchar(200) NOT NULL default '',
  `URLTITLE` varchar(200) NOT NULL default '',
  `DESCRIPTION` text,
  `TYPE` varchar(100) NOT NULL default '',
  `VISITS` int(10) unsigned NOT NULL default '0',
  `PUBLISH` int(1) unsigned NOT NULL default '1',
  `PUBLISH_HOME` int(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`ID`)
) TYPE=MyISAM;


# Estrutura da tabela `BW_LOGS`

CREATE TABLE `BW_LOGS` (
  `ID` int(14) NOT NULL default '0',
  `MESSAGE` varchar(250) NOT NULL default '',
  `USERID` int(14) NOT NULL default '0',
  `PAGEURL` varchar(200) NOT NULL default '',
  `REFFURL` varchar(200) NOT NULL default '',
  `USERIP` varchar(100) NOT NULL default '',
  `DESCRIPTION` text,
  `TYPE` varchar(100) default NULL,
  PRIMARY KEY  (`ID`)
) TYPE=MyISAM;


# Estrutura da tabela `BW_POSTS`

CREATE TABLE `BW_POSTS` (
  `ID` int(14) NOT NULL default '0',
  `TITLE` varchar(250) NOT NULL default '',
  `MESSAGE` text NOT NULL,
  `OTHER` varchar(250) default NULL,
  `RELATEDLINK` text,
  `RELATEDFILE` text,
  `TYPE` varchar(100) NOT NULL default 'post',
  `UID` int(14) unsigned NOT NULL default '0',
  `COMMENTS` int(4) NOT NULL default '0',
  `PUBLISH` int(1) NOT NULL default '1',
  `LEVEL` int(4) unsigned NOT NULL default '10',
  `UPDATED` int(14) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) TYPE=MyISAM;


# Estrutura da tabela `BW_USERS`

CREATE TABLE `BW_USERS` (
  `ID` int(14) NOT NULL default '0',
  `LOGIN` varchar(50) NOT NULL default '',
  `NAME` varchar(250) NOT NULL default '',
  `PASSWD` varchar(16) NOT NULL default '',
  `EMAIL` varchar(200) NOT NULL default '',
  `URL` varchar(200) default NULL,
  `URLTITLE` varchar(200) default NULL,
  `AVATAR` varchar(255) default NULL,
  `COMMENTS` int(8) NOT NULL default '0',
  `IP` text NOT NULL,
  `LASTVISIT` int(14) NOT NULL default '0',
  `POSTVISIT` text,
  `LEVEL` int(3) default '0',
  `LANGUAGE` varchar(6) NOT NULL default 'pt-br',
  `DATEFORMAT` varchar(100) default 'd/m/Y H:i',
  `ICQ` int(12) default NULL,
  `YAHOO` varchar(250) default NULL,
  `MSN` varchar(250) default NULL,
  `AIM` varchar(250) default NULL,
  PRIMARY KEY  (`ID`)
) TYPE=MyISAM;

# Extraindo dados da tabela `BW_USERS`

INSERT INTO `BW_USERS` VALUES (7, 'anonymous', 'anônimo', '', 'anonymous@none.com.br', 'http://blogware.none.com.br', 'blogware.none', NULL, 0, '', 0, NULL, 10, 'pt-br', 'd/m/Y H:i', NULL, NULL, NULL, NULL);
INSERT INTO `BW_USERS` VALUES (1337, 'jao', 'jao', 'mellon13', 'joao@none.com.br', 'http://blog.none.com.br', 'none', 'http://none.com.br/blog/img/avatar/none.gif', 0, '', 0, NULL, 1337, 'pt-br', 'd/m/Y H:i', 2206917, 'jpsama', 'jpsama@hotmail.com', 'jpsama');
