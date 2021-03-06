<?php

#
# XiVO Web-Interface
# Copyright (C) 2006-2014  Avencall
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#

require_once('xivo.php');

if(dwho_constant('XIVO_WEBI_CONFIGURED',false) === false)
{
	$action_path = $_LOC->get_action_path('xivo/wizard',0);

	if($action_path === false)
		dwho_die('XIVO is not configured');

	die(include($action_path));
}

$go = array_key_exists('go', $_GET)?$_GET['go']:null;

if(xivo_user::is_valid() === true)
	$_QRY->go($_TPL->url(is_null($go)?'xivo':rawurldecode($go)));

$_LANG = &dwho_gat::load_get('language',XIVO_PATH_OBJECTCONF);

if(isset($_QR['language'],$_LANG[$_QR['language']]) === true)
	$language = $_QR['language'];
else
	$language = '';

if(isset($_QR['login'],$_QR['password']) === true
		&& $_USR->load_by_authent($_QR['login'],$_QR['password'],$language) === true)
	$_QRY->go($_TPL->url(is_null($go)?'xivo':rawurldecode($go)));

$_TPL->set_var('language',dwho_array_intersect_key($_LANG,dwho_i18n::get_language_translated_list()));
$_TPL->set_struct('home/login');
$_TPL->display('center');

?>
