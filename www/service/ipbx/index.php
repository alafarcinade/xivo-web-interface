<?php

#
# XiVO Web-Interface
# Copyright (C) 2006-2011  Avencall
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

if(xivo_user::chk_acl(true) === false)
	$_QRY->go($_TPL->url('xivo'), array('go' => urlencode($_SERVER['REQUEST_URI'])));

$ipbx = &$_SRE->get('ipbx');

$dhtml = &$_TPL->get_module('dhtml');
$dhtml->set_css('css/service/ipbx/'.$ipbx->get_name().'.css');
$dhtml->set_js('js/service/ipbx/'.$ipbx->get_name().'.js');

$_TPL->load_i18n_file('struct/service/ipbx/'.$ipbx->get_name());

$action_path = $_LOC->get_action_path('service/ipbx/'.$ipbx->get_name(),2);

require_once(DWHO_PATH_ROOT.DIRECTORY_SEPARATOR.'logaccess.inc');

if($action_path === false)
	$_QRY->go($_TPL->url('xivo'));

die(include($action_path));

?>