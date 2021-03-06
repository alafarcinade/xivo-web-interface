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

$form = &$this->get_module('form');

$fkdata = $this->get_var('fkdata');

$inputhidden = array();
$inputhidden['paragraph'] = false;
$inputhidden['name'] = 'phonefunckey[typeval][]';
$inputhidden['label'] = false;
$inputhidden['id'] = 'it-phonefunckey-meetme-typeval';

$inputtxt = $inputhidden;
$inputtxt['size'] = 20;
$inputtxt['name'] = 'phonefunckey-meetme-suggest';
$inputtxt['id'] = 'it-phonefunckey-meetme-suggest';

if($fkdata['ex'] === false):
	$incr = dwho_uint($fkdata['incr']);
	$inputhidden['id'] .= '-'.$incr;
	$inputtxt['id'] .= '-'.$incr;

	if($fkdata['type'] === 'meetme'):
		$inputhidden['value'] = $fkdata['result']['id'];
		$inputtxt['value'] = $fkdata['result']['identity'];
	endif;
else:
	$inputhidden['disabled'] = true;
endif;

echo	$form->hidden($inputhidden),
	$form->text($inputtxt);

?>
