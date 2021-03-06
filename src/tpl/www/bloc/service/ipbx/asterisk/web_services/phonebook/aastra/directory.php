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

$url = &$this->get_module('url');
$xmlphone = &$this->get_module('xmlphone');
$xmlvendor = $xmlphone->factory($this->get_var('vendor'));

$list = $this->get_var('list');
$pos = (int) $this->get_var('pos');
$prevpos = $this->get_var('prevpos');

$tagmenu = $xmlvendor->tag_menu();

$argseparator = $xmlvendor->arg_separator();

if($prevpos > 0):
	$prevparam = array();
	$prevparam['node'] = 1;
	$prevparam['pos'] = floor($pos / $prevpos) * $prevpos;
	$prevparam['name'] = $this->get_var('name');

	$previous = $url->href('service/ipbx/web_services/phonebook/search',
			       $prevparam,
			       true,
			       $argseparator,
			       false);
else:
	$previous = null;
endif;

echo	'<',$tagmenu,' style="none" destroyOnExit="yes">',"\n";

if(is_array($list) === false || ($nb = count($list)) === 0):
	echo	'<MenuItem>',"\n",
		'<Prompt>',$xmlvendor->escape($this->bbf('phone_noentries')),'</Prompt>',"\n",
		'<URI></URI>',"\n",
		'</MenuItem>',"\n";
	if(is_null($previous) === false):
		echo	'<MenuItem>',"\n",
			'<Prompt>[',$xmlvendor->escape($this->bbf('phone_back')),']</Prompt>',"\n",
			'<URI>',$previous,'</URI>',"\n",
			'</MenuItem>',"\n";
	endif;
else:
	if(is_null($previous) === false):
		echo	'<MenuItem>',"\n",
			'<Prompt>[',$xmlvendor->escape($this->bbf('phone_back')),']</Prompt>',"\n",
			'<URI>',$previous,'</URI>',"\n",
			'</MenuItem>',"\n";
	endif;

	for($i = 0;$i < $nb;$i++):
		$ref = &$list[$i];

		if(isset($ref['additionaltype']) === true && $ref['additionaltype'] === 'custom'):
			if($ref['additionaltext'] === ''):
				$name = $this->bbf('phone_name-empty',$ref['name']);
			else:
				$name = $this->bbf('phone_name-custom',array($ref['name'],$ref['type']));
			endif;
		else:
			$name = $this->bbf('phone_name-'.$ref['type'],$ref['name']);
		endif;

		echo	'<MenuItem>',"\n",
			'<Prompt>',$xmlvendor->escape($name),'</Prompt>',"\n",
			'<URI>Dial:',$xmlvendor->escape($ref['phone']),'</URI>',"\n",
			'<Dial>',$xmlvendor->escape($ref['phone']),'</Dial>',"\n",
			'</MenuItem>',"\n";
	endfor;
endif;

echo '</',$tagmenu,'>';

?>
