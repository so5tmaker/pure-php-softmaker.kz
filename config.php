<?php
//����� � �������� ���� � ����� ������������ � ������ ������ �������,
//����� ���������� ����� ���� ��� ����� ��������� ��� ���������
$ROOT = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'); //$SERVER = $_SERVER['DOCUMENT_ROOT'];
$SCRIPT = filter_input(INPUT_SERVER, 'SCRIPT_NAME');
$SERVER = filter_input(INPUT_SERVER, 'SCRIPT_FILENAME'); //$SERVER = $_SERVER['SCRIPT_FILENAME'];
$USER_AGENT = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT'); // $_SERVER['HTTP_USER_AGENT'];
$HOST = filter_input(INPUT_SERVER, 'HTTP_HOST'); // $_SERVER['HTTP_HOST']
$rus = strstr($HOST, 'localhost'); //$rus = strstr($SERVER, 'localhost') OR strstr($SERVER, 'sites');
// ���� ����� �������� ����� ���������� �����
$PHP_SELF = filter_input(INPUT_SERVER, 'PHP_SELF');
$cut_slash = substr($PHP_SELF, 1); // ������� ������ ����
$pos = strpos($cut_slash, "/"); // ������ ������� ������� ��������� ������� "/" ."/" .'/'
$rest = substr($cut_slash, 0, $pos); // ����������, �������� "phpbloguser"
$rest1 = ($rest=="") ? $rest : "/".$rest;
$rest_ = ($rus !== false) ? $rest1 : '' ;

$d = dirname(__FILE__);
/** ���������� ���� � ���������� �������. */
if ( !defined('ABSPATH') )
	define('ABSPATH', str_replace('/blocks', '', $d) . '/');

// ������� ������ �������� ����
$DIR = ABSPATH;

