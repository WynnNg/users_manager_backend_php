<?php

const _MODULE = 'home';
const _ACTION = 'dashboard';
const _CODE = true;

// Thiết lập host

define('_WEB_HOST', 'http://' . $_SERVER['HTTP_HOST'] . '/users_manager');
define('_WEB_HOST_TEMPLATES', _WEB_HOST . '/templates');

// Thiết lập path

define('_WEB_PATH', __DIR__);
define('_WEB_PATH_TEMPLATES', _WEB_PATH . '/templates');

// Thông tin kết nối Database
const _HOST = 'hostname';
const _DB = 'dbname';
const _USER = 'root';
const _PASS = '';

//Thông tin email
const _MAIL_USER = "yourmail@example.com";
const _MAIL_PASS = "mat_khau_ung_dung_gmail";
