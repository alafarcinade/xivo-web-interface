<?php

#
# XiVO Web-Interface
# Copyright (C) 2006-2011  Proformatique <technique@proformatique.com>
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

$page = $url->pager($pager['pages'],
		    $pager['page'],
		    $pager['prev'],
		    $pager['next'],
		    'service/ipbx/pbx_settings/devices',
		    array('act' => $act,$param));

?>
<div class="b-list">
<?php
	if($page !== ''):
		echo '<div class="b-page">',$page,'</div>';
	endif;
?>
<form action="#" name="fm-devices-list" method="post" accept-charset="utf-8">
<?=$form->hidden(array('name' => DWHO_SESS_NAME,'value' => DWHO_SESS_ID))?>
<?=$form->hidden(array('name' => 'act','value' => $act))?>
<?=$form->hidden(array('name' => 'reboot','value' => ''))?>
<?=$form->hidden(array('name' => 'page','value' => $pager['page']))?>
<?=$form->hidden(array('name' => 'search','value' => ''))?>

<table id="table-main-listing" cellspacing="0" cellpadding="0" border="0">
	<tr class="sb-top">
		<th class="th-left xspan"><span class="span-left">&nbsp;</span></th>
		<th class="th-center">
			<span class="title <?= $sort[1]=='mac'?'underline':''?>">
				<?=$this->bbf('col_mac');?>
			</span>
<?php
	echo	$url->href_html(
					$url->img_html('img/updown.png', $this->bbf('col_sort_mac'), 'border="0"'),
					'service/ipbx/pbx_settings/devices',
					array('act'	=> 'list', 'sort' => 'mac'),
					null,
					$this->bbf('col_sort_mac'));
?>
		</th>
		<th class="th-center">
			<span class="title <?= $sort[1]=='ip'?'underline':''?>">
				<?=$this->bbf('col_ip');?>
			</span>
<?php
	echo	$url->href_html(
					$url->img_html('img/updown.png', $this->bbf('col_sort_ip'), 'border="0"'),
					'service/ipbx/pbx_settings/devices',
					array('act'	=> 'list', 'sort' => 'ip'),
					null,
					$this->bbf('col_sort_ip'));
?>
		</th>
		<th class="th-center">
			<span class="title, <?= $sort[1]=='vendor'?'underline':''?>">
				<?=$this->bbf('col_vendor');?>
			</span>
<?php
	echo	$url->href_html(
					$url->img_html('img/updown.png', $this->bbf('col_sort_vendor'), 'border="0"'),
					'service/ipbx/pbx_settings/devices',
					array('act'	=> 'list', 'sort' => 'vendor'),
					null,
					$this->bbf('col_sort_vendor'));
?>
		</th>
		<th class="th-center">
			<span class="title, <?= $sort[1]=='model'?'underline':''?>">
				<?=$this->bbf('col_model');?>
			</span>
<?php
	echo	$url->href_html(
					$url->img_html('img/updown.png', $this->bbf('col_sort_model'), 'border="0"'),
					'service/ipbx/pbx_settings/devices',
					array('act'	=> 'list', 'sort' => 'model'),
					null,
					$this->bbf('col_sort_model'));
?>
		</th>
		<th class="th-center"><?=$this->bbf('col_plugin');?></th>
		<th class="th-center"><?=$this->bbf('col_action');?></th>
		<th class="th-right xspan"><span class="span-right">&nbsp;</span></th>
	</tr>
<?php
	if(($list = $this->get_var('list')) === false || ($nb = count($list)) === 0):
?>
	<tr class="sb-content">
		<td colspan="8" class="td-single"><?=$this->bbf('no_device');?></td>
	</tr>
<?php
	else:
		for($i = 0;$i < $nb;$i++):

			$ref = &$list[$i];

			if($ref['commented'] === true):
				$icon = 'disable';
			elseif($ref['configured'] === false):
				$icon = 'red';
			else:
				if(strncmp($ref['config'],'autoprov',strlen('autoprov')) === 0)
					$icon = 'yellow';
				else
					$icon = 'green';
			endif;

			if($ref['provdexist'] === true):
				$provdexist = 'stock_link';
			else:
				$provdexist = 'stock_unlink';
			endif;
?>
	<tr onmouseover="this.tmp = this.className; this.className = 'sb-content l-infos-over';"
	    onmouseout="this.className = this.tmp;"
	    class="sb-content l-infos-<?=(($i % 2) + 1)?>on2">
		<td class="td-left">
			<?=$form->checkbox(array('name'		=> 'devices[]',
						 'value'	=> $ref['id'],
						 'label'	=> false,
						 'id'		=> 'it-devices-'.$i,
						 'checked'	=> false,
						 'paragraph'	=> false));?>
		</td>
		<td class="txt-left" title="<?=dwho_alttitle($ref['mac']);?>">
			<label for="it-devices-<?=$i?>" id="lb-devices-<?=$i?>">
<?php
				echo $url->img_html('img/site/utils/'.$provdexist.'.png',null,'class="icons-list"');
				echo $url->img_html('img/site/utils/cercle-'.$icon.'.png',null,'class="icons-list"');
				echo dwho_has_len($ref['mac']) === true ? dwho_htmlen(dwho_trunc($ref['mac'],25,'...',false)) : '-';
?>
			</label>
		</td>
		<td><?=$ref['ip'];?></td>
		<td><?=(dwho_has_len($ref['vendor']) === true ? $ref['vendor'] : '-')?></td>
		<td><?=(dwho_has_len($ref['model']) === true ? $ref['model'] : '-')?></td>
		<td><?=(dwho_has_len($ref['plugin']) === true ? $ref['plugin'] : '-')?></td>
		<td class="td-right" colspan="2">
<?php
		echo	$url->href_html($url->img_html('img/site/utils/updating.png',
						       $this->bbf('opt_synchronize'),
						       'border="0"'),
					'service/ipbx/pbx_settings/devices',
					array('act'	=> 'synchronize',
					      'id'	=> $ref['id']),
					'onclick="return(confirm(\''.$dhtml->escape($this->bbf('opt_synchronize_confirm')).'\'));"',
					$this->bbf('opt_synchronize')),"\n";
		echo	$url->href_html($url->img_html('img/site/utils/reset.png',
						       $this->bbf('opt_reset-autoprov'),
						       'border="0" width="16" height="16"'),
					'service/ipbx/pbx_settings/devices',
					array('act'	=> 'modeautoprov',
					      'id'	=> $ref['id']),
					'onclick="return(confirm(\''.$dhtml->escape($this->bbf('opt_modeautoprov_confirm')).'\'));"',
					$this->bbf('opt_reset-autoprov')),"\n";
		echo	$url->href_html($url->img_html('img/site/button/edit.gif',
						       $this->bbf('opt_modify'),
						       'border="0"'),
					'service/ipbx/pbx_settings/devices',
					array('act'	=> 'edit',
					      'id'	=> $ref['id']),
					null,
					$this->bbf('opt_modify')),"\n",
			$url->href_html($url->img_html('img/site/button/delete.gif',
						       $this->bbf('opt_delete'),
						       'border="0"'),
					'service/ipbx/pbx_settings/devices',
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
		<td class="td-center" colspan="6"><span class="b-nosize">&nbsp;</span></td>
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
	<legend><?=$this->bbf('device-list_legend');?></legend>
	<p><?=$url->img_html('img/site/utils/stock_link.png');?> <?=$this->bbf('device-list_legend-opt',array('link_provd_exist'));?></p>
	<p><?=$url->img_html('img/site/utils/stock_unlink.png');?> <?=$this->bbf('device-list_legend-opt',array('link_provd_not_exist'));?></p>
	<p><?=$url->img_html('img/site/utils/cercle-green.png');?> <?=$this->bbf('device-list_legend-opt',array('configured'));?></p>
	<p><?=$url->img_html('img/site/utils/cercle-yellow.png');?> <?=$this->bbf('device-list_legend-opt',array('autoprov-mode'));?></p>
	<p><?=$url->img_html('img/site/utils/cercle-red.png');?> <?=$this->bbf('device-list_legend-opt',array('not_configured'));?></p>
</fieldset>
