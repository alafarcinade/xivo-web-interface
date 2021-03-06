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
$url = &$this->get_module('url');
$dhtml = &$this->get_module('dhtml');

$info = $this->get_var('info');

$pginfo = $info['info'];
$pkgs = $info['pkgs'];
$params = $info['params'];

?>
<div class="b-infos b-form">
	<h3 class="sb-top xspan">
		<span class="span-left">&nbsp;</span>
		<span class="span-center"><?=$this->bbf('title_content_name',array($this->get_var('id'),$pginfo['version']));?></span>
		<span class="span-right">&nbsp;</span>
	</h3>
	<div class="sb-content">
	<fieldset>
		<legend><?=$this->bbf('plugin-description_legend',array($this->get_var('id'),$pginfo['version']));?></legend>
		<?$this->bbf('plugin-description');?>
		<?=$pginfo['description'];?>
	</fieldset>
<?php
if ($params !== false):
	$this->file_include('bloc/xivo/configuration/provisioning/plugin/params');
endif;

if (empty($pkgs) === true):
	echo $this->bbf('no_package');
else:
?>
	<div id="box_installer" class="bi_bg">
		<div class="bi_message"></div>
	</div>

	<div class="sb-list">
	<table id="tb-list-pkgs">
		<thead>
		<tr class="sb-top">
			<th class="th-left"><?=$this->bbf('col_name');?></th>
			<th class="th-center"><?=$this->bbf('col_description');?></th>
			<th class="th-center"><?=$this->bbf('col_size');?></th>
			<th class="th-center"><?=$this->bbf('col_version');?></th>
			<th class="th-right"><?=$this->bbf('col_action');?></th>
		</tr>
		</thead>
		<tbody>
	<?php
		if(($nb = count($pkgs)) === 0):
	?>
		<tr>
			<td colspan="5" class="td-single"><?=$this->bbf('no_plugin');?></td>
		</tr>
	<?php
		else:
			for($i = 0;$i < $nb;$i++):
				$ref = &$pkgs[$i];

				$component_size = '-';
				if (isset($ref['dsize']) === true):
					$iec_size = dwho_size_iec($ref['dsize']);
					$component_size = $this->bbf('size_iec_'.$iec_size[1],$iec_size[0]);
				endif;
	?>
		<tr class="fm-paragraph" id="">
			<td class="td-left txt-left" title="<?=dwho_alttitle($ref['name']);?>">
				<?=dwho_htmlen(dwho_trunc($ref['name'],25,'...',false));?>
			</td>
			<td class="txt-left" title="<?=dwho_alttitle($ref['description']);?>">
				<?=dwho_htmlen(dwho_trunc($ref['description'],50,'...',false));?>
			</td>
			<td><?=$component_size?></td>
			<td><?=(isset($ref['version']) === true ? $ref['version'] : '-')?></td>
			<td class="td-right">
	<?php
			if ($ref['type'] === 'installable'):
				echo	$url->href_html($url->img_html('img/site/utils/app-install.png',
								       $this->bbf('opt_install'),
								       'border="0"'),
							'#',
							null,
							'onclick="init_install_pkgs(\''.$this->get_var('id').'\',\''.$ref['name'].'\');return(false);"',
							$this->bbf('opt_install'));
			elseif ($ref['type'] === 'installed'):
				echo	$url->href_html($url->img_html('img/site/utils/app-delete.png',
								       $this->bbf('opt_uninstall'),
								       'border="0"'),
							'xivo/configuration/provisioning/plugin',
							array('act'	=> 'uninstall-pkgs',
							      'plugin'	=> $this->get_var('id'),
							      'id'	=> $ref['name']),
							'onclick="return(confirm(\''.$dhtml->escape($this->bbf('opt_uninstall_confirm')).'\'));"',
							$this->bbf('opt_uninstall'));
			endif;
	?>
			</td>
		</tr>
	<?php
			endfor;
		endif;
	?>
		</tbody>
	</table>
	</div>
<?php
endif;
?>
	</div>
	<div class="sb-foot xspan">
		<span class="span-left">&nbsp;</span>
		<span class="span-center">&nbsp;</span>
		<span class="span-right">&nbsp;</span>
	</div>
</div>
