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
$form = &$this->get_module('form');
$dhtml = &$this->get_module('dhtml');

$pager = $this->get_var('pager');
$act = $this->get_var('act');
$sort = $this->get_var('sort');

$param = array();

if(($search = (string) $this->get_var('search')) !== ''):
	$param['search'] = $search;
endif;

if(($context = $this->get_var('context')) !== ''):
	$param['context'] = $context;
endif;

$page = $url->pager($pager['pages'],
		    $pager['page'],
		    $pager['prev'],
		    $pager['next'],
		    'service/ipbx/pbx_settings/lines',
		    array('act' => $act,$param));

?>
<div class="b-list">
<?php
	if($page !== ''):
		echo '<div class="b-page">',$page,'</div>';
	endif;
?>
<form action="#" name="fm-lines-list" method="post" accept-charset="utf-8">
<?=$form->hidden(array('name' => DWHO_SESS_NAME,'value' => DWHO_SESS_ID))?>
<?=$form->hidden(array('name' => 'act','value' => $act))?>
<?=$form->hidden(array('name' => 'reboot','value' => ''))?>
<?=$form->hidden(array('name' => 'page','value' => $pager['page']))?>
<?=$form->hidden(array('name' => 'search','value' => ''))?>
<?=$form->hidden(array('name' => 'context','value' => ''))?>

<table id="table-main-listing">
	<tr class="sb-top">
		<th class="th-left xspan"><span class="span-left">&nbsp;</span></th>
		<th class="th-center">
			<span class="title <?= $sort[1]=='name'?'underline':''?>">
				<?=$this->bbf('col_identity');?>
			</span>
<?php
	echo	$url->href_html(
					$url->img_html('img/updown.png', $this->bbf('col_sort_identity'), 'border="0"'),
					'service/ipbx/pbx_settings/lines',
					array('act'	=> 'list', 'sort' => 'name'),
					null,
					$this->bbf('col_sort_identity'));
?>
		</th>
		<th class="th-center">
			<span class="title <?= $sort[1]=='protocol'?'underline':''?>">
				<?=$this->bbf('col_protocol');?>
			</span>
<?php
	echo	$url->href_html(
					$url->img_html('img/updown.png', $this->bbf('col_sort_protocol'), 'border="0"'),
					'service/ipbx/pbx_settings/lines',
					array('act'	=> 'list', 'sort' => 'protocol'),
					null,
					$this->bbf('col_sort_protocol'));
?>
		</th>
		<th class="th-center"><?=$this->bbf('col_entity');?></th>
		<th class="th-center"><?=$this->bbf('col_provisioning');?></th>
		<th class="th-center"><?=$this->bbf('col_user');?></th>
		<th class="th-center"><?=$this->bbf('col_phone');?></th>
		<th class="th-center col-action"><?=$this->bbf('col_action');?></th>
		<th class="th-right xspan"><span class="span-right">&nbsp;</span></th>
	</tr>
<?php
	if(($list = $this->get_var('list')) === false || ($nb = count($list)) === 0):
?>
	<tr class="sb-content">
		<td colspan="10" class="td-single"><?=$this->bbf('no_line');?></td>
	</tr>
<?php
	else:
		for($i = 0;$i < $nb;$i++):
			$ref = &$list[$i];

			$phone = 'green';
			if(strlen($ref['device']) === 0)
				$phone = 'red';

			if($ref['commented'] === true):
				$icon = 'disable';
			elseif($ref['initialized'] === false):
				$icon = 'unavailable';
			else:
				$icon = 'enable';
			endif;
?>
	<tr onmouseover="this.tmp = this.className; this.className = 'sb-content l-infos-over';"
	    onmouseout="this.className = this.tmp;"
	    class="sb-content l-infos-<?=(($i % 2) + 1)?>on2">
		<td class="td-left">
			<?=$form->checkbox(array('name'		=> 'lines[]',
						 'value'	=> $ref['id'],
						 'label'	=> false,
						 'id'		=> 'it-lines-'.$i,
						 'checked'	=> false,
						 'paragraph'	=> false));?>
		</td>
		<td class="txt-left col_identity" title="<?=dwho_alttitle($ref['identity']);?>">
			<label for="it-lines-<?=$i?>" id="lb-lines-<?=$i?>">
<?php
				echo $url->img_html('img/site/phone/'.$icon.'.gif',null,'class="icons-list"');
				echo $url->img_html('img/site/utils/phone-'.$phone.'.png',null,'class="icons-list"');
				echo dwho_htmlen(dwho_trunc($ref['identity'],30,'...',false));
?>
			</label>
		</td>
		<td class="col_protocol"><?=$this->bbf('line_protocol-'.$ref['protocol']);?></td>
		<td class="col_entity"><?=$ref['entity']['displayname']?></td>
		<td class="txt-center col_provisioningid">
			<?=(empty($ref['provisioningid']) || ($ref['useridentity'] === '-')) ? '-' : $ref['provisioningid']?>
		</td>
		<td class="txt-center col_user">
			<?=dwho_htmlen(dwho_trunc($ref['useridentity'],25,'...',false))?>
		</td>
		<td class="txt-center col_number">
			<?=empty($ref['number']) ? '-' : $ref['number']?>
		</td>
		<td class="td-right" colspan="2">
<?php
		echo	$url->href_html($url->img_html('img/site/button/edit.gif',
						       $this->bbf('opt_modify'),
						       'border="0"'),
					'service/ipbx/pbx_settings/lines',
					array('act'	=> 'edit',
					      'id'	=> $ref['id']),
					null,
					$this->bbf('opt_modify')),"\n";

		echo	$url->href_html($url->img_html('img/site/button/delete.gif',
					       $this->bbf('opt_delete'),
					       'border="0"'),
				'service/ipbx/pbx_settings/lines',
				array('act'	=> 'delete',
				      'id'	=> $ref['id'],
				      'page'	=> $pager['page'],
				      $param),
				'onclick="return(confirm(\''.$dhtml->escape($this->bbf('opt_delete_confirm')).'\'));"',
				$this->bbf('opt_delete'));
?>
		</td>
	</tr>
<?php
		endfor;
	endif;
?>
	<tr class="sb-foot">
		<td class="td-left xspan b-nosize"><span class="span-left b-nosize">&nbsp;</span></td>
		<td class="td-center" colspan="7"><span class="b-nosize">&nbsp;</span></td>
		<td class="td-right xspan b-nosize"><span class="span-right b-nosize">&nbsp;</span></td>
	</tr>
</table>
</form>
<?php
	if($page !== ''):
		echo '<div class="b-page">',$page,'</div>';
	endif;
?>
</div>

<fieldset>
	<legend><?=$this->bbf('lines-list_legend');?></legend>
	<p><?=$url->img_html('img/site/utils/phone-green.png');?> <?=$this->bbf('line-list_legend-opt',array('line_assoc_device_exist'));?></p>
	<p><?=$url->img_html('img/site/utils/phone-red.png');?> <?=$this->bbf('line-list_legend-opt',array('line_assoc_device_not_exist'));?></p>
</fieldset>
