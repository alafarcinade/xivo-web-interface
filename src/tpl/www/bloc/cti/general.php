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

$info = $this->get_var('info');
$element = $this->get_var('element');

$error = $this->get_var('error');
$listaccount = $this->get_var('listaccount');

$error_js = array();
$error_nb = count($error['ctimain']);

for($i = 0;$i < $error_nb;$i++):
	$error_js[] = 'dwho.form.error[\'it-ctimain-'.$error['ctimain'][$i].'\'] = true;';
endfor;

if(isset($error_js[0]) === true)
	$dhtml->write_js($error_js);

?>
<div class="b-infos b-form">
<h3 class="sb-top xspan">
	<span class="span-left">&nbsp;</span>
	<span class="span-center"><?=$this->bbf('title_content_name');?></span>
	<span class="span-right">&nbsp;</span>
</h3>
<div class="sb-content">
<form action="#" method="post" accept-charset="utf-8">
<?php
	echo
		$form->hidden(array('name'	=> DWHO_SESS_NAME,
				    'value'	=> DWHO_SESS_ID)),
		$form->hidden(array('name'	=> 'fm_send',
				    'value'	=> 1));
?>

<div id="sb-part-first">
	<?=$form->select(array('desc'	=> $this->bbf('fm_cti_commandset'),
				    'name'	=> 'cti[commandset]',
				    'labelid'	=> 'cti_commandset',
				    'key'	=> false,
				    'default'	=> $element['ctimain']['commandset']['default'],
#				    'help'	=> $this->bbf('hlp_fm_cti_commandset'),
				    'selected'	=> $this->get_var('ctimain','commandset','var_val')),
			      $element['ctimain']['commandset']['value']);
	?>
<fieldset id="cti-ami">
	<legend><?=$this->bbf('cti-ami');?></legend>
			<?=$form->text(array('desc'	=> $this->bbf('fm_cti_ami_login'),
						  'name'	=> 'cti[ami_login]',
						  'labelid'	=> 'cti-ami_login',
						  'required'	=> 1,
						  'size'	=> 15,
						  'default'	=> $element['ctimain']['ami_login']['default'],
						  'value'	=> $info['ctimain']['ami_login'],
				          'error'	=> $this->bbf_args('error',
							   $this->get_var('error', 'ctimain','ami_login'))))?>
			<?=$form->text(array('desc'	=> $this->bbf('fm_cti_ami_password'),
						  'name'	=> 'cti[ami_password]',
						  'labelid'	=> 'cti-ami_password',
						  'required'	=> 1,
						  'size'	=> 15,
						  'default'	=> $element['ctimain']['ami_password']['default'],
						  'value'	=> $info['ctimain']['ami_password'],
				          'error'	=> $this->bbf_args('error',
							   $this->get_var('error', 'ctimain','ami_password'))))?>
			<?=$form->text(array('desc'	=> $this->bbf('fm_cti_ami_ip'),
						  'name'	=> 'cti[ami_ip]',
						  'labelid'	=> 'cti-ami_ip',
						  'required'	=> 1,
						  'regexp'	=> '[[:ipv4:]]',
						  'size'	=> 15,
						  'default'	=> $element['ctimain']['ami_ip']['default'],
						  'value'	=> $info['ctimain']['ami_ip'],
				          'error'	=> $this->bbf_args('error',
							   $this->get_var('error', 'ctimain','ami_ip'))))?>
			<?=$form->text(array('desc'	=> $this->bbf('fm_cti_ami_port'),
						  'name'	=> 'cti[ami_port]',
						  'labelid'	=> 'cti-ami_port',
						  'required'	=> 1,
						  'regexp'	=> '[[:port:]]',
						  'size'	=> 4,
						  'default'	=> $element['ctimain']['ami_port']['default'],
						  'value'	=> $info['ctimain']['ami_port'],
				          'error'	=> $this->bbf_args('error',
							   $this->get_var('error', 'ctimain','ami_port'))))?>
</fieldset>

<fieldset id="cti-servers">
	<legend><?=$this->bbf('cti-servers');?></legend>
	<div class="sb-list">
		<table>
		<tr class="sb-top">
			<th class="th-left" width="40"><?=$this->bbf('fm_cti_list_active')?></th>
			<th class="th-center"><?=$this->bbf('fm_cti_list_ip')?></th>
			<th class="th-right" width="25%"><?=$this->bbf('fm_cti_list_port')?></th>
		</tr>
		<tr onmouseover="this.tmp = this.className; this.className = 'sb-content l-infos-over';"
		    onmouseout="this.className = this.tmp;">
			<td class="td-left">
			<?=$form->checkbox(array('name'	=> 'cti[cti_active]',
				'checked'=> $info['ctimain']['cti_active'],
				'label'		=> false,
				'id'		=> 'it-cti_active',
				'paragraph'	=> false));?>
			</td>
			<td>
			<?=$form->text(array('desc'	=> $this->bbf('fm_cti_cti_ip'),
				'name'		=> 'cti[cti_ip]',
				'labelid'	=> 'cti_cti_ip',
				'value'		=> $info['ctimain']['cti_ip'],
				'required'	=> 1,
				'regexp'	=> '[[:ipv4:]]',
				'default'	=> $element['ctimain']['cti_ip']['default'] //,
				/* 'help'		=> $this->bbf('hlp_fm_cti_cti_ip') */ ))?>
			</td>
			<td class="td-right">
			<?=$form->text(array(#'desc'	=> $this->bbf('fm_cti_cti_port'),
					'name'		=> 'cti[cti_port]',
					'labelid'	=> 'cti_cti_port',
					'value'		=> $info['ctimain']['cti_port'],
					'required'	=> 1,
					'regexp'	=> '[[:port:]]',
					'default'	=> $element['ctimain']['cti_port']['default'],
#					'help'		=> $this->bbf('hlp_fm_cti_cti_port')
					))
				?>
			</td>
		</tr>
		<tr onmouseover="this.tmp = this.className; this.className = 'sb-content l-infos-over';"
		    onmouseout="this.className = this.tmp;">
			<td class="td-left">
			<?=$form->checkbox(array('name'	=> 'cti[ctis_active]',
				'checked'=> $info['ctimain']['ctis_active'],
				'label'		=> false,
				'id'		=> 'it-ctis_active',
				'paragraph'	=> false));?>
			</td>
			<td>
			<?
				echo $form->text(array('desc'	=> $this->bbf('fm_cti_ctis_ip'),
								'name'		=> 'cti[ctis_ip]',
								'labelid'	=> 'cti_ctis_ip',
								'value'		=> $info['ctimain']['ctis_ip'],
								'required'	=> 1,
								'regexp'	=> '[[:ipv4:]]',
								'default'	=> $element['ctimain']['ctis_ip']['default'])),

							$form->select(array('desc'  => $this->bbf('fm_tlscertfile'),
		            'name'    => 'cti[tlscertfile]',
		            'labelid' => 'tlscertfile',
								'key'     => 'name',
								'altkey'  => 'path',
		            'empty'   => true,
								'label'		=> false,
		            'selected'=> $this->get_var('info','ctimain','tlscertfile'),
		            'default' => $element['ctimain']['tlscertfile']['default']),
					         $this->get_var('tlscertfiles')),


					    $form->select(array('desc'  => $this->bbf('fm_tlsprivkeyfile'),
							  'name'    => 'cti[tlsprivkeyfile]',
		            'labelid' => 'tlsprivkeyfile',
								'key'     => 'name',
								'altkey'  => 'path',
		            'empty'   => true,
				        'help'    => $this->bbf('hlp_fm_tlsprivkeyfile'),
						    'selected'=> $this->get_var('info','ctimain','tlsprivkeyfile'),
								'default' => $element['ctimain']['tlsprivkeyfile']['default']),
					         $this->get_var('tlsprivkeyfiles'));
			?>
			</td>
			<td class="td-right">
			<?=$form->text(array(#'desc'	=> $this->bbf('fm_cti_ctis_port'),
					'name'		=> 'cti[ctis_port]',
					'labelid'	=> 'cti_ctis_port',
					'value'		=> $info['ctimain']['ctis_port'],
					'required'	=> 1,
					'regexp'	=> '[[:port:]]',
					'default'	=> $element['ctimain']['ctis_port']['default'],
#					'help'		=> $this->bbf('hlp_fm_cti_ctis_port')
					))
				?>
			</td>
		</tr>
		<tr onmouseover="this.tmp = this.className; this.className = 'sb-content l-infos-over';"
		    onmouseout="this.className = this.tmp;">
			<td class="td-left">
			<?=$form->checkbox(array('name'	=> 'cti[webi_active]',
				'checked'		=> $info['ctimain']['webi_active'],
				'label'		=> false,
				'id'		=> 'it-webi_active',
				'paragraph'	=> false));?>
			</td>
			<td>
			<?=$form->text(array('desc'	=> $this->bbf('fm_cti_webi_ip'),
				'name'		=> 'cti[webi_ip]',
				'labelid'	=> 'cti_webi_ip',
				'value'		=> $info['ctimain']['webi_ip'],
				'required'	=> 1,
				'regexp'	=> '[[:ipv4:]]',
				'default'	=> $element['ctimain']['webi_ip']['default'] //,
				/* 'help'		=> $this->bbf('hlp_fm_cti_webi_ip') */ ))
			?>
			</td>
			<td class="td-right">
			<?=$form->text(array(#'desc'	=> $this->bbf('fm_cti_webi_port'),
					'name'		=> 'cti[webi_port]',
					'labelid'	=> 'cti_webi_port',
					'value'		=> $info['ctimain']['webi_port'],
					'required'	=> 1,
					'regexp'	=> '[[:port:]]',
					'default'	=> $element['ctimain']['webi_port']['default'],
#					'help'		=> $this->bbf('hlp_fm_cti_webi_port')
					))
				?>
			</td>
		</tr>
		<tr onmouseover="this.tmp = this.className; this.className = 'sb-content l-infos-over';"
		    onmouseout="this.className = this.tmp;">
			<td class="td-left">
			<?=$form->checkbox(array('name'	=> 'cti[info_active]',
				'checked'	=> $info['ctimain']['info_active'],
				'label'		=> false,
				'id'		=> 'it-info_active',
				'paragraph'	=> false));?>
			</td>
			<td>
			<?=$form->text(array('desc'	=> $this->bbf('fm_cti_info_ip'),
				'name'		=> 'cti[info_ip]',
				'labelid'	=> 'cti_info_ip',
				'value'		=> $info['ctimain']['info_ip'],
				'required'	=> 1,
				'regexp'	=> '[[:ipv4:]]',
				'default'	=> $element['ctimain']['info_ip']['default'] //,
				/* 'help'		=> $this->bbf('hlp_fm_cti_info_ip') */ ))
			?>
			</td>
			<td class="td-right">
			<?=$form->text(array(#'desc'	=> $this->bbf('fm_cti_info_port'),
					'name'		=> 'cti[info_port]',
					'labelid'	=> 'cti_info_port',
					'value'		=> $info['ctimain']['info_port'],
					'required'	=> 1,
					'regexp'	=> '[[:port:]]',
					'default'	=> $element['ctimain']['info_port']['default'],
#					'help'		=> $this->bbf('hlp_fm_cti_info_port')
					))
				?>
			</td>
		</tr>
		</table>
	</div>
</fieldset>
<fieldset id="cti-intervals">
	<legend><?=$this->bbf('cti-intervals');?></legend>
<?php
	echo	$form->text(array('desc'	=> $this->bbf('fm_cti_socket_timeout'),
					'name'		=> 'cti[socket_timeout]',
					'labelid'	=> 'cti_socket_timeout',
					'value'		=> $info['ctimain']['socket_timeout'],
					'regexp'	=> '[[:int:]]',
					'default'	=> $element['ctimain']['socket_timeout']['default'],
#					'help'		=> $this->bbf('hlp_fm_cti_socket_timeout')
					)),

			$form->text(array('desc'	=> $this->bbf('fm_cti_login_timeout'),
					'name'		=> 'cti[login_timeout]',
					'labelid'	=> 'cti_login_timeout',
					'value'		=> $info['ctimain']['login_timeout'],
					'regexp'	=> '[[:int:]]',
					'default'	=> $element['ctimain']['login_timeout']['default'],
#					'help'		=> $this->bbf('hlp_fm_cti_login_timeout')
					));
?>
</fieldset>
<?php
	echo	$form->checkbox(array('desc' => $this->bbf('fm_cti_context_separation'),
							'name' => 'cti[context_separation]',
							'labelid' => 'context_separation',
							'checked' => $info['ctimain']['context_separation']));
?>
</div>

<?php
	echo	$form->submit(array('name'	=> 'submit',
				    'id'	=> 'it-submit',
				    'value'	=> $this->bbf('fm_bt-save')));
?>
</form>

	</div>
	<div class="sb-foot xspan">
		<span class="span-left">&nbsp;</span>
		<span class="span-center">&nbsp;</span>
		<span class="span-right">&nbsp;</span>
	</div>
</div>
