-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Хост: localhost
-- Время создания: Мар 19 2008 г., 20:06
-- Версия сервера: 5.0.41
-- Версия PHP: 4.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- База данных: `aguryanov_testdb`
-- 

-- --------------------------------------------------------

-- 
-- Структура таблицы `testapp`
-- 

CREATE TABLE `testapp` (
  `sessionhash` varchar(40) NOT NULL,
  `userid` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `sessiontime` int(11) NOT NULL,
  `gnumber` tinyint(4) NOT NULL,
  `gstarttime` int(11) NOT NULL,
  `gtries` tinyint(4) NOT NULL,
  PRIMARY KEY  (`sessionhash`),
  KEY `sessiontime` (`sessiontime`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

-- 
-- Структура таблицы `testapp_results`
-- 

CREATE TABLE `testapp_results` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `time` int(11) NOT NULL,
  `gtries` tinyint(4) NOT NULL,
  `gtime` smallint(6) NOT NULL,
  `gpoints` int(11) NOT NULL,
  KEY `gpoints` (`gpoints`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 COMMENT='лучшие результаты';
