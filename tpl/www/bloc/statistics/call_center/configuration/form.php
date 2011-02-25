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
#

$form = &$this->get_module('form');
$url = &$this->get_module('url');
$dhtml = &$this->get_module('dhtml');

$listqos = $this->get_var('listqos');
$incall = $this->get_var('incall');
$queue = $this->get_var('queue');
$group = $this->get_var('group');
$agent = $this->get_var('agent');
$user = $this->get_var('user');
$info = $this->get_var('info');
$element = $this->get_var('element');

if($this->get_var('fm_save') === false)
	$dhtml->write_js('xivo_form_result(false,\''.$dhtml->escape($this->bbf('fm_error-save')).'\');');

?>
	<div id="sb-part-first" class="b-nodisplay">
		<p>
			<label id="lb-description" for="it-description"><?=$this->bbf('fm_description_general');?></label>
		</p>
<?php
	echo	$form->text(array('desc'	=> $this->bbf('fm_name'),
				  'name'	=> 'stats_conf[name]',
				  'labelid'	=> 'name',
				  'size'	=> 20,
				  'default'	=> $element['stats_conf']['name']['default'],
				  'value'	=> $info['stats_conf']['name'],
				  'error'	=> $this->bbf_args('error',$this->get_var('error','stats_conf','name')) ));
	
	echo	$form->text(array('desc'	=> $this->bbf('fm_default_delta'),
				  'name'	=> 'stats_conf[default_delta]',
				  'labelid'	=> 'name',
				  'size'	=> 12,
				  'help'	=> $this->bbf('hlp_fm_default_delta'),
				  'default'	=> $element['stats_conf']['default_delta']['default'],
				  'value'	=> $info['stats_conf']['default_delta'],
				  'error'	=> $this->bbf_args('error',$this->get_var('error','stats_conf','default_delta')) ));
?>
			<fieldset id="stats_conf_cache_period">
				<legend><?=$this->bbf('cache_during_period');?></legend>
						<p>
							<label id="lb-description" for="it-description"><?=$this->bbf('fm_description_cache');?></label>
						</p>				
	<?php
		echo	$form->text(array('desc'	=> $this->bbf('fm_dbegcache'),
					  'name'	=> 'stats_conf[dbegcache]',
					  'labelid'	=> 'name',
					  'size'	=> 6,
					  'default'	=> $element['stats_conf']['dbegcache']['default'],
					  'value'	=> $info['stats_conf']['dbegcache'],
					  'error'	=> $this->bbf_args('error',$this->get_var('error','stats_conf','dbegcache')) ));
				      	
		echo	$form->text(array('desc'	=> $this->bbf('fm_dendcache'),
					  'name'	=> 'stats_conf[dendcache]',
					  'labelid'	=> 'name',
					  'size'	=> 6,
					  'help'	=> $this->bbf('hlp_fm_dendcache'),
					  'default'	=> $element['stats_conf']['dendcache']['default'],
					  'value'	=> $info['stats_conf']['dendcache'],
					  'error'	=> $this->bbf_args('error',$this->get_var('error','stats_conf','dendcache')) ));
	?>
			</fieldset>
			<fieldset id="stats_conf_workhour">
				<legend><?=$this->bbf('stats_conf_workhour');?></legend>
				<div class="b-form">
					<table>
					<tr>
						<td align="right">
							<?=$this->bbf('fm_hour_start')?>
						<td>
						<td>
<?php
	echo	$form->select(array('name'		=> 'workhour_start[h]',
				    'labelid'	=> 'hour_start',
				    'key'		=> false,
				    'default'	=> $element['stats_conf']['workhour']['h']['default'],
				    'selected'	=> $this->get_var('workhour_start','h'),
				 	'error'		=> $this->bbf_args('error',$this->get_var('error','workhour_start','h'))),
			      $element['stats_conf']['workhour']['h']);
?>
						</td>
						<td>
<?php
	echo	$form->select(array('name'		=> 'workhour_start[m]',
				    'labelid'	=> 'hour_start',
				    'key'		=> false,
				    'default'	=> $element['stats_conf']['workhour']['m']['default'],
				    'selected'	=> $this->get_var('workhour_start','m')),
			      $element['stats_conf']['workhour']['m']);
?>
						</td>
					</tr>
					<tr>
						<td align="right">
							<?=$this->bbf('fm_hour_end')?>
						<td>
						<td>
<?php	
	echo	$form->select(array('name'		=> 'workhour_end[h]',
				    'labelid'	=> 'workhour_end',
				    'key'		=> false,
				    'default'	=> $element['stats_conf']['workhour']['h']['default'],
				    'selected'	=> $this->get_var('workhour_end','h'),
				 	'error'		=> $this->bbf_args('error',$this->get_var('error','workhour_end','h'))),
			      $element['stats_conf']['workhour']['h']);
?>
						</td>
						<td>
<?php
	echo	$form->select(array('name'		=> 'workhour_end[m]',
				    'labelid'	=> 'workhour_end',
				    'key'		=> false,
				    'default'	=> $element['stats_conf']['workhour']['m']['default'],
				    'selected'	=> $this->get_var('workhour_end','m')),
			      $element['stats_conf']['workhour']['m']);
?>
						</td>
					</tr>
					</table>
				</div>
			</fieldset>
			<fieldset id="stats_conf_period">
				<legend><?=$this->bbf('stats_conf_period');?></legend>
<?php	
	for($i=1;$i<6;$i++):
	
	echo	$form->text(array('desc'	=> $this->bbf('fm_period'.$i),
					  'name'	=> 'stats_conf[period'.$i.']',
					  'labelid'	=> 'period'.$i,
					  'size'	=> 5,
					  'help'		=> $this->bbf('hlp_fm_period'),
					  'required'	=> false,
					  'default'	=> $element['stats_conf']['period'.$i]['default'],
					  'value'	=> $info['stats_conf']['period'.$i],
				 	  'error'	=> $this->bbf_args('error',$this->get_var('error','stats_conf','period'.$i)) ));
	
	endfor;
?>
			</fieldset>
			<div class="fm-paragraph fm-description">
				<p>
					<label id="lb-description" for="it-description"><?=$this->bbf('fm_description');?></label>
				</p>
				<?=$form->textarea(array('paragraph'	=> false,
					 'label'	=> false,
					 'name'		=> 'stats_conf[description]',
					 'id'		=> 'it-description',
					 'cols'		=> 60,
					 'rows'		=> 5,
					 'default'	=> $element['stats_conf']['description']['default']),
						   $info['stats_conf']['description']);?>
			</div>
			</div>
			
			<div id="sb-part-workweek" class="b-nodisplay">
<?php	
	echo	$form->checkbox(array('desc' => $this->bbf('fm_workweek_monday'),
				  'name'	=> 'stats_conf[monday]',
				  'labelid'	=> 'monday',
				  'checked'	=> $info['stats_conf']['monday'])),
	
	$form->checkbox(array('desc' => $this->bbf('fm_workweek_tuesday'),
				  'name'	=> 'stats_conf[tuesday]',
				  'labelid'	=> 'tuesday',
				  'checked'	=> $info['stats_conf']['tuesday'])),
	
	$form->checkbox(array('desc' => $this->bbf('fm_workweek_wednesday'),
				  'name'	=> 'stats_conf[wednesday]',
				  'labelid'	=> 'wednesday',
				  'checked'	=> $info['stats_conf']['wednesday'])),
	
	$form->checkbox(array('desc' => $this->bbf('fm_workweek_thursday'),
				  'name'	=> 'stats_conf[thursday]',
				  'labelid'	=> 'thursday',
				  'checked'	=> $info['stats_conf']['thursday'])),
	
	$form->checkbox(array('desc' => $this->bbf('fm_workweek_friday'),
				  'name'	=> 'stats_conf[friday]',
				  'labelid'	=> 'friday',
				  'checked'	=> $info['stats_conf']['friday'])),
	
	$form->checkbox(array('desc' => $this->bbf('fm_workweek_saturday'),
				  'name'	=> 'stats_conf[saturday]',
				  'labelid'	=> 'saturday',
				  'checked'	=> $info['stats_conf']['saturday'])),
	
	$form->checkbox(array('desc' => $this->bbf('fm_workweek_sunday'),
				  'name'	=> 'stats_conf[sunday]',
				  'labelid'	=> 'sunday',
				  'checked'	=> $info['stats_conf']['sunday']));				   
?>
			</div>
			
			<div id="sb-part-incall" class="b-nodisplay">
<?php
	if(isset($incall['list']) === true
	&& $incall['list'] !== false):
?>
				<div id="incalllist" class="fm-paragraph fm-multilist">
				<?=$form->input_for_ms('incalllist',$this->bbf('ms_seek'))?>
					<div class="slt-outlist">
						<?=$form->select(array('name'		=> 'incalllist',
								       'label'		=> false,
								       'id'		=> 'it-incalllist',
								       'multiple'	=> true,
								       'size'		=> 5,
								       'paragraph'	=> false,
								       'key'		=> 'identity',
								       'altkey'		=> 'id'),
										$incall['list']);?>
					</div>
			
					<div class="inout-list">
						<a href="#"
						   onclick="dwho.form.move_selected('it-incalllist','it-incall');
							   populateqos('it-incall','incall');
							    return(dwho.dom.free_focus());"
						   title="<?=$this->bbf('bt_inincall');?>">
							<?=$url->img_html('img/site/button/arrow-left.gif',
									  $this->bbf('bt_inincall'),
									  'class="bt-inlist" id="bt-inincall" border="0"');?></a><br />
						<a href="#"
						   onclick="dwho.form.move_selected('it-incall','it-incalllist');
							   populateqos('it-incall','incall');
							    return(dwho.dom.free_focus());"
						   title="<?=$this->bbf('bt_outincall');?>">
							<?=$url->img_html('img/site/button/arrow-right.gif',
									  $this->bbf('bt_outincall'),
									  'class="bt-outlist" id="bt-outincall" border="0"');?></a>
					</div>
			
					<div class="slt-inlist">
						<?=$form->select(array('name'		=> 'incall[]',
								       'label'		=> false,
								       'id'		=> 'it-incall',
								       'multiple'	=> true,
								       'size'		=> 5,
								       'paragraph'	=> false,
								       'key'		=> 'identity',
								       'altkey'		=> 'id'),
									   $incall['slt']);?>
					</div>
				</div>
				<div class="clearboth"></div>
<?php
	else:
		echo	'<div class="txt-center">',
			$url->href_htmln($this->bbf('create_incall'),
					'service/ipbx/call_center/incalls',
					'act=add'),
			'</div>';
	endif;
?>
			</div>
			
			<div id="sb-part-queue" class="b-nodisplay">
			
			<fieldset>
			<legend><?=$this->bbf('queue')?></legend>
<?php
	if(isset($queue['list']) === true
	&& $queue['list'] !== false):
?>
				<div id="queuelist" class="fm-paragraph fm-multilist">
				<?=$form->input_for_ms('queuelist',$this->bbf('ms_seek'))?>
					<div class="slt-outlist">
						<?=$form->select(array('name'		=> 'queuelist',
								       'label'		=> false,
								       'id'		=> 'it-queuelist',
								       'multiple'	=> true,
								       'size'		=> 5,
								       'paragraph'	=> false,
								       'key'		=> 'name',
								       'altkey'		=> 'id'),
										$queue['list']);?>
					</div>
			
					<div class="inout-list">
						<a href="#"
						   onclick="dwho.form.move_selected('it-queuelist','it-queue');
							   populateqos('it-queue','queue');
							    return(dwho.dom.free_focus());"
						   title="<?=$this->bbf('bt_inqueue');?>">
							<?=$url->img_html('img/site/button/arrow-left.gif',
									  $this->bbf('bt_inqueue'),
									  'class="bt-inlist" id="bt-inqueue" border="0"');?></a><br />
						<a href="#"
						   onclick="dwho.form.move_selected('it-queue','it-queuelist');
							   populateqos('it-queue','queue');
							    return(dwho.dom.free_focus());"
						   title="<?=$this->bbf('bt_outqueue');?>">
							<?=$url->img_html('img/site/button/arrow-right.gif',
									  $this->bbf('bt_outqueue'),
									  'class="bt-outlist" id="bt-outqueue" border="0"');?></a>
					</div>
			
					<div class="slt-inlist">
						<?=$form->select(array('name'		=> 'queue[]',
								       'label'		=> false,
								       'id'		=> 'it-queue',
								       'multiple'	=> true,
								       'size'		=> 5,
								       'paragraph'	=> false,
								       'key'		=> 'name',
								       'altkey'		=> 'id'),
									   $queue['slt']);?>
					</div>
				</div>
				<div class="clearboth"></div>
<?php
	else:
		echo	'<div class="txt-center">',
			$url->href_htmln($this->bbf('create_queue'),
					'service/ipbx/call_center/queues',
					'act=add'),
			'</div>';
	endif;
?>
			</fieldset>
			<fieldset>
			<legend><?=$this->bbf('group')?></legend>
<?php
	if(isset($group['list']) === true
	&& $group['list'] !== false):
?>
				<div id="grouplist" class="fm-paragraph fm-multilist">
				<?=$form->input_for_ms('grouplist',$this->bbf('ms_seek'))?>
					<div class="slt-outlist">
						<?=$form->select(array('name'		=> 'grouplist',
								       'label'		=> false,
								       'id'		=> 'it-grouplist',
								       'multiple'	=> true,
								       'size'		=> 5,
								       'paragraph'	=> false,
								       'key'		=> 'name',
								       'altkey'		=> 'id'),
										$group['list']);?>
					</div>
			
					<div class="inout-list">
						<a href="#"
						   onclick="dwho.form.move_selected('it-grouplist','it-group');
							   populateqos('it-group','group');
							    return(dwho.dom.free_focus());"
						   title="<?=$this->bbf('bt_ingroup');?>">
							<?=$url->img_html('img/site/button/arrow-left.gif',
									  $this->bbf('bt_ingroup'),
									  'class="bt-inlist" id="bt-ingroup" border="0"');?></a><br />
						<a href="#"
						   onclick="dwho.form.move_selected('it-group','it-grouplist');
							   populateqos('it-group','group');
							    return(dwho.dom.free_focus());"
						   title="<?=$this->bbf('bt_outgroup');?>">
							<?=$url->img_html('img/site/button/arrow-right.gif',
									  $this->bbf('bt_outgroup'),
									  'class="bt-outlist" id="bt-outgroup" border="0"');?></a>
					</div>
			
					<div class="slt-inlist">
						<?=$form->select(array('name'		=> 'group[]',
								       'label'		=> false,
								       'id'		=> 'it-group',
								       'multiple'	=> true,
								       'size'		=> 5,
								       'paragraph'	=> false,
								       'key'		=> 'name',
								       'altkey'		=> 'id'),
									   $group['slt']);?>
					</div>
				</div>
				<div class="clearboth"></div>
<?php
	else:
		echo	'<div class="txt-center">',
			$url->href_htmln($this->bbf('create_group'),
					'service/ipbx/pbx_settings/groups',
					'act=add'),
			'</div>';
	endif;
?>
			</fieldset>
			</div>
			
			<div id="sb-part-qos" class="b-nodisplay">
				<p>
					<label id="lb-description" for="it-description"><?=$this->bbf('fm_description_stats_queue_qos');?></label>
				</p>
				<div id="it-listqueueqos"></div>
				<div id="it-listgroupqos"></div>
				<script type="text/javascript">				
					var listqos = new Object();
<?php
					if (is_null($listqos) === false
					&& empty($listqos) === false)
						foreach($listqos as $k => $v)
							echo "listqos[$k] = $v;\n";
							
							
?>	
					var translation = new Object();
					translation['queue'] = '<?=addslashes($this->bbf('queue'))?>';
					translation['group'] = '<?=addslashes($this->bbf('group'))?>';
					function populateqos(eid,type)
					{
						var to = dwho_eid('it-list'+type+'qos');
						var box = dwho_eid(eid);
						var nb = box.length;
						var input = '';
						if (nb > 0)
						{
							input += '<fieldset>'
							input += '<legend>'+translation[type]+'</legend>';
						}
						for (var i = 0; i < nb; i++)
						{
							var qos = 0;
							var option = box[i];
							if( option.value in listqos )
								qos = listqos[option.value];
							input += '<p id="fd-qos" class="fm-paragraph">';
							input += '<span class="fm-desc clearboth"><label id="lb-qos" for="it-qos">'+option.text+':</label></span>';
							input += '<input type="text" name="'+type+'_qos['+option.value+']" value="'+qos+'" size="5" />';
							input += '</p>';
						}
						if (nb > 0)
							input += '</fieldset>';
						to.innerHTML = input;
					}
					dwho.dom.set_onload(populateqos('it-queue','queue'));
					dwho.dom.set_onload(populateqos('it-group','group'));
				</script>
			</div>
			
			<div id="sb-part-last" class="b-nodisplay">
			
			<fieldset>
			<legend><?=$this->bbf('agent')?></legend>
<?php
	if($agent['list'] !== false):
?>
				<div id="agentlist" class="fm-paragraph fm-multilist">
				<?=$form->input_for_ms('agentlist',$this->bbf('ms_seek'))?>
					<div class="slt-outlist">
						<?=$form->select(array('name'	=> 'agentlist',
								       'label'		=> false,
								       'id'			=> 'it-agentlist',
								       'multiple'	=> true,
								       'size'		=> 5,
								       'paragraph'	=> false,
								       'key'		=> 'fullname',
								       'altkey'		=> 'id'),
										$agent['list']);?>
					</div>
			
					<div class="inout-list">
						<a href="#"
						   onclick="dwho.form.move_selected('it-agentlist','it-agent');
							    return(dwho.dom.free_focus());"
						   title="<?=$this->bbf('bt_inagent');?>">
							<?=$url->img_html('img/site/button/arrow-left.gif',
									  $this->bbf('bt_inagent'),
									  'class="bt-inlist" id="bt-inagent" border="0"');?></a><br />
						<a href="#"
						   onclick="dwho.form.move_selected('it-agent','it-agentlist');
							    return(dwho.dom.free_focus());"
						   title="<?=$this->bbf('bt_outagent');?>">
							<?=$url->img_html('img/site/button/arrow-right.gif',
									  $this->bbf('bt_outagent'),
									  'class="bt-outlist" id="bt-outagent" border="0"');?></a>
					</div>
			
					<div class="slt-inlist">
						<?=$form->select(array('name'	=> 'agent[]',
								       'label'		=> false,
								       'id'			=> 'it-agent',
								       'multiple'	=> true,
								       'size'		=> 5,
								       'paragraph'	=> false,
								       'key'		=> 'fullname',
								       'altkey'		=> 'id'),
									   $agent['slt']);?>
					</div>
				</div>
				<div class="clearboth"></div>
<?php
	else:
		echo	'<div class="txt-center">',
			$url->href_htmln($this->bbf('create_agent'),
					'service/ipbx/call_center/agents',
					'act=add'),
			'</div>';
	endif;
?>
			</fieldset>
			<fieldset>
			<legend><?=$this->bbf('user')?></legend>
<?php
	if($user['list'] !== false):
?>
				<div id="queuelist" class="fm-paragraph fm-multilist">
				<?=$form->input_for_ms('userlist',$this->bbf('ms_seek'))?>
					<div class="slt-outlist">
						<?=$form->select(array('name'		=> 'userlist',
								       'label'		=> false,
								       'id'			=> 'it-userlist',
								       'multiple'	=> true,
								       'size'		=> 5,
								       'paragraph'	=> false,
								       'key'		=> 'identity',
								       'altkey'		=> 'id'),
										$user['list']);?>
					</div>
			
					<div class="inout-list">
						<a href="#"
						   onclick="dwho.form.move_selected('it-userlist','it-user');
							    return(dwho.dom.free_focus());"
						   title="<?=$this->bbf('bt_inuser');?>">
							<?=$url->img_html('img/site/button/arrow-left.gif',
									  $this->bbf('bt_inuser'),
									  'class="bt-inlist" id="bt-inuser" border="0"');?></a><br />
						<a href="#"
						   onclick="dwho.form.move_selected('it-user','it-userlist');
							    return(dwho.dom.free_focus());"
						   title="<?=$this->bbf('bt_outuser');?>">
							<?=$url->img_html('img/site/button/arrow-right.gif',
									  $this->bbf('bt_outuser'),
									  'class="bt-outlist" id="bt-outuser" border="0"');?></a>
					</div>
			
					<div class="slt-inlist">
						<?=$form->select(array('name'	=> 'user[]',
								       'label'		=> false,
								       'id'			=> 'it-user',
								       'multiple'	=> true,
								       'size'		=> 5,
								       'paragraph'	=> false,
								       'key'		=> 'identity',
								       'altkey'		=> 'id'),
									   $user['slt']);?>
					</div>
				</div>
				<div class="clearboth"></div>
<?php
	else:
		echo	'<div class="txt-center">',
			$url->href_htmln($this->bbf('create_user'),
					'service/ipbx/pbx_settings/users',
					'act=add'),
			'</div>';
	endif;
?>
</fieldset>
			</div>