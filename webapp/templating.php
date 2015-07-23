<?php

/* The List powered by Creative Commons

   Copyright (C) 2014 Creative Commons Corporation

   based on:

   GNU FM -- a free network service for sharing your music listening habits
   GNU Archie Framework -- a web application framework derived from GNU FM

   Copyright (C) 2009, 2015 Free Software Foundation, Inc

   This program is free software: you can redistribute it and/or modify
   it under the terms of either the GNU Affero General Public License or
   the GNU General Public License as published by the
   Free Software Foundation, either version 3 of the Licenses, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  

   You should have received a copy of the GNU General Public License and
   the GNU Affero General Public License along with this program.  

   If not, see <http://www.gnu.org/licenses/>.
*/

define('SMARTY_DIR', '/usr/share/php/smarty3/');
require_once('config.php');
require_once('auth.php');
require_once(SMARTY_DIR . 'Smarty.class.php');
require_once "data/User.php";

function displayError($error_title, $error_message) {
	global $smarty;
	$smarty->assign('pagetitle', $error_title);
	$smarty->assign('pageheading', $error_title); #librefm theme compat, may be removed after switch to BS3 theme
	$smarty->assign('error_message', $error_message);
	$smarty->display('error.tpl');
	die();
}

if (isset($_GET['lang'])) {
	$languages = array($_GET['lang'] . '.UTF-8');
 	setcookie('lang', $_GET['lang'] . '.UTF-8', time() + 31536000, '/');
} else if (isset($_COOKIE['lang'])) {
 	$languages = array($_COOKIE['lang']);
} else {
	// Attempt to mangle browser language strings in to valid gettext locales (needs a big lookup table to be 100% accurate)
	$languages = preg_split('/,/', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	for ($i = 0; $i < count($languages); $i++) {
		$languages[$i] = preg_replace('/;q=\d\.\d/', '', $languages[$i]);
		if (strlen($languages[$i]) == 2) {
			$languages[$i] = $languages[$i] . '_' . strtoupper($languages[$i]);
		} else if (stristr($languages[$i], '-')) {
			$lcomponents = preg_split('/-/', $languages[$i]);
			$languages[$i] = $lcomponents[0] . '_' . strtoupper($lcomponents[1]);
		}

		$languages[$i] = $languages[$i] . '.UTF-8';
	}
}
$current_lang = setlocale(LC_ALL, $languages);

if(isset($_GET['mobile']) && $_GET['mobile']) {
	$theme = 'mobile';
} else {
	$theme = $default_theme;
}

bindtextdomain('nixtape', $install_path . '/themes/' . $theme . '/locale/');
textdomain('nixtape');

$smarty = new Smarty();

$smarty->setTemplateDir(array(
	$install_path . '/themes/'. $theme . '/templates/',
	$install_path . '/themes/thelist/templates/'
));
$smarty->setPluginsDir(array(
	SMARTY_DIR . '/plugins/',
	$install_path. '/themes/' . $theme . '/plugins/',
	$install_path . '/themes/thelist/plugins/'
));
$smarty->setCompileDir($install_path . '/themes/' . $theme . '/templates_c/');
$smarty->setCacheDir($install_path . '/cache/');
$smarty->setConfigDir(array($install_path . '/themes/' . $theme . '/config/', $install_path . '/themes/thelist/config/'));

$current_lang = preg_replace('/.UTF-8/', '', $current_lang);

$smarty->assign('casauth', $auth);

if ($auth) {
    $casuid = phpCAS::getUser();
    $smarty->assign('userid',phpCAS::getUser());

    $attr = phpCAS::getAttributes();

    $nickname = $attr['nickname'];

    $smarty->assign('handle', $nickname);
    
    $user = new User($casuid);

    $userid = $user->id;
    $makerid = $user->makerid;

    $smarty->assign('makerid', $makerid);


}


$smarty->assign('lang_selector_array', array(($current_lang) => 1));
$smarty->assign('base_url', $base_url);
$smarty->assign('default_theme', $default_theme);
$smarty->assign('site_name', $site_name);
$smarty->assign('img_url', $base_url . '/themes/' . $theme . '/img/');
$smarty->assign('this_page', $_SERVER['REQUEST_URI']);
$smarty->assign('this_page_absolute',
	  (empty($_SERVER['HTTPS']) ? 'http://' : 'http://')
	. (empty($_SERVER['HOST']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HOST'])
	. (($_SERVER['SERVER_PORT'] == 80) ? '' : (':' . $_SERVER['SERVER_PORT']))
	. $_SERVER['REQUEST_URI']);

if (isset($logged_in) && $logged_in) {
	$smarty->assign('logged_in', true);
	// Pre-fix this user's details with 'this_' to avoid confusion with other users
	$smarty->assign('this_user', $this_user);
}

header('Content-Type: text/html; charset=utf-8');

