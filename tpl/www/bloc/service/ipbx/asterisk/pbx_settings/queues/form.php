<?php
	$form = &$this->get_module('form');
	$url = &$this->get_module('url');
	$element = $this->vars('element');
	$info = $this->vars('info');

	$member_slt = $this->vars('member_slt');
	$member_list = $this->vars('member_list');
	$moh_list = $this->vars('moh_list');
	$announce_list = $this->vars('announce_list');
?>

<?=$form->text(array('desc' => $this->bbf('fm_qfeatures_name'),'name' => 'qfeatures[name]','labelid' => 'qfeatures-name','size' => 15,'default' => $element['qfeatures']['name']['default'],'value' => $info['qfeatures']['name']),'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->text(array('desc' => $this->bbf('fm_qfeatures_number'),'name' => 'qfeatures[number]','labelid' => 'qfeatures-number','size' => 15,'default' => $element['qfeatures']['number']['default'],'value' => $info['qfeatures']['number']),'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->text(array('desc' => $this->bbf('fm_qfeatures_context'),'name' => 'qfeatures[context]','labelid' => 'qfeatures-context','size' => 15,'default' => $element['qfeatures']['context']['default'],'value' => $info['qfeatures']['context']),'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_qfeatures_timeout'),'name' => 'qfeatures[timeout]','labelid' => 'qfeatures-timeout','bbf' => array('paramkey','fm_qfeatures_timeout-opt'),'key' => false,'default' => $element['qfeatures']['timeout']['default'],'value' => (int) $info['qfeatures']['timeout'] ),$element['qfeatures']['timeout']['value'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->checkbox(array('desc' => $this->bbf('fm_qfeatures_data-quality'),'name' => 'qfeatures[data_quality]','labelid' => 'qfeatures-data-quality','default' => $element['qfeatures']['data_quality']['default'],'checked' => $element['qfeatures']['data_quality']['default']),$info['qfeatures']['data_quality'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->checkbox(array('desc' => $this->bbf('fm_qfeatures_hitting-callee'),'name' => 'qfeatures[hitting_callee]','labelid' => 'qfeatures-hitting-callee','default' => $element['qfeatures']['hitting_callee']['default'],'checked' => $element['qfeatures']['hitting_callee']['default']),$info['qfeatures']['hitting_callee'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->checkbox(array('desc' => $this->bbf('fm_qfeatures_hitting-caller'),'name' => 'qfeatures[hitting_caller]','labelid' => 'qfeatures-hitting-caller','default' => $element['qfeatures']['hitting_caller']['default'],'checked' => $element['qfeatures']['hitting_caller']['default']),$info['qfeatures']['hitting_caller'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->checkbox(array('desc' => $this->bbf('fm_qfeatures_retries'),'name' => 'qfeatures[retries]','labelid' => 'qfeatures-retries','default' => $element['qfeatures']['retries']['default'],'checked' => $element['qfeatures']['retries']['default']),$info['qfeatures']['retries'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->checkbox(array('desc' => $this->bbf('fm_qfeatures_ring'),'name' => 'qfeatures[ring]','labelid' => 'qfeatures-ring','default' => $element['qfeatures']['ring']['default'],'checked' => $element['qfeatures']['ring']['default']),$info['qfeatures']['ring'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->checkbox(array('desc' => $this->bbf('fm_qfeatures_transfer-user'),'name' => 'qfeatures[transfer_user]','labelid' => 'qfeatures-transfer-user','default' => $element['qfeatures']['transfer_user']['default'],'checked' => $element['qfeatures']['transfer_user']['default']),$info['qfeatures']['transfer_user'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->checkbox(array('desc' => $this->bbf('fm_qfeatures_transfer-call'),'name' => 'qfeatures[transfer_call]','labelid' => 'qfeatures-transfer-call','default' => $element['qfeatures']['transfer_call']['default'],'checked' => $element['qfeatures']['transfer_call']['default']),$info['qfeatures']['transfer_call'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->checkbox(array('desc' => $this->bbf('fm_qfeatures_write-caller'),'name' => 'qfeatures[write_caller]','labelid' => 'qfeatures-write-caller','default' => $element['qfeatures']['write_caller']['default'],'checked' => $element['qfeatures']['write_caller']['default']),$info['qfeatures']['write_caller'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->checkbox(array('desc' => $this->bbf('fm_qfeatures_write-calling'),'name' => 'qfeatures[write_calling]','labelid' => 'qfeatures-write-calling','default' => $element['qfeatures']['write_calling']['default'],'checked' => $element['qfeatures']['write_calling']['default']),$info['qfeatures']['write_calling'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_musiconhold'),'name' => 'queue[musiconhold]','labelid' => 'queue-musiconhold','key' => 'category','empty' => true,'default' => $element['queue']['musiconhold']['default'],'value' => $info['queue']['musiconhold']),$moh_list,'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?php

if($announce_list !== false):
	echo $form->slt(array('desc' => $this->bbf('fm_queue_announce'),'name' => 'queue[announce]','labelid' => 'queue-announce','key' => false,'empty' => true,'default' => $element['queue']['announce']['default'],'value' => $info['queue']['announce']),$announce_list,'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');
else:
	echo '<div class="txt-center">',$url->href_html($this->bbf('add_announce'),'service/ipbx/general_settings/sounds',array('act' => 'list','dir' => 'acd')),'</div>';
endif;

?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_strategy'),'name' => 'queue[strategy]','labelid' => 'queue-strategy','key' => false,'default' => $element['queue']['strategy']['default'],'value' => $info['queue']['strategy']),$element['queue']['strategy']['value'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->text(array('desc' => $this->bbf('fm_queue_servicelevel'),'name' => 'queue[servicelevel]','labelid' => 'queue-servicelevel','size' => 15,'default' => $element['queue']['servicelevel']['default'],'value' => $info['queue']['servicelevel']),'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_timeout'),'name' => 'queue[timeout]','labelid' => 'queue-timeout','bbf' => array('paramkey','fm_queue_timeout-opt'),'key' => false,'default' => $element['queue']['timeout']['default'],'value' => (int) $info['queue']['timeout'] ),$element['queue']['timeout']['value'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_retry'),'name' => 'queue[retry]','labelid' => 'queue-retry','bbf' => array('paramkey','fm_queue_retry-opt'),'key' => false,'default' => $element['queue']['retry']['default'],'value' => (int) $info['queue']['retry']),$element['queue']['retry']['value'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_weight'),'name' => 'queue[weight]','labelid' => 'queue-weight','key' => false,'default' => $element['queue']['weight']['default'],'value' => (int) $info['queue']['weight']),$element['queue']['weight']['value'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_wrapuptime'),'name' => 'queue[wrapuptime]','labelid' => 'queue-wrapuptime','bbf' => array('mixkey','fm_queue_wrapuptime-opt'),'key' => false,'default' => $element['queue']['wrapuptime']['default'],'value' => (isset($info['queue']['wrapuptime']) === true ? (int) $info['queue']['wrapuptime'] : null)),$element['queue']['wrapuptime']['value'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->text(array('desc' => $this->bbf('fm_queue_maxlen'),'name' => 'queue[maxlen]','labelid' => 'queue-maxlen','size' => 15,'default' => $element['queue']['maxlen']['default'],'value' => $info['queue']['maxlen']),'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_announce-frequency'),'name' => 'queue[announce-frequency]','labelid' => 'queue-announce-frequency','bbf' => array('mixkey','fm_queue_announce-frequency-opt','paramarray'),'default' => $element['queue']['announce-frequency']['default'],'value' => (isset($info['queue']['announce-frequency']) === true ? (int) $info['queue']['announce-frequency'] : null)),$element['queue']['announce-frequency']['value'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_periodic-announce-frequency'),'name' => 'queue[periodic-announce-frequency]','labelid' => 'queue-periodic-announce-frequency','bbf' => array('mixkey','fm_queue_periodic-announce-frequency-opt','paramarray'),'default' => $element['queue']['periodic-announce-frequency']['default'],'value' => (isset($info['queue']['periodic-announce-frequency']) === true ? (int) $info['queue']['periodic-announce-frequency'] : null)),$element['queue']['periodic-announce-frequency']['value'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_announce-holdtime'),'name' => 'queue[announce-holdtime]','labelid' => 'queue-announce-holdtime','bbf' => 'fm_queue_announce-holdtime-opt-','key' => false,'default' => $element['queue']['announce-holdtime']['default'],'value' => $info['queue']['announce-holdtime']),$element['queue']['announce-holdtime']['value'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_announce-round-seconds'),'name' => 'queue[announce-round-seconds]','labelid' => 'queue-announce-round-seconds','bbf' => array('mixkey','fm_queue_announce-round-seconds-opt'),'key' => false,'default' => $element['queue']['announce-round-seconds']['default'],'value' => (isset($info['queue']['announce-round-seconds']) === true ? (int) $info['queue']['announce-round-seconds'] : null)),$element['queue']['announce-round-seconds']['value'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_monitor-format'),'name' => 'queue[monitor-format]','labelid' => 'queue-monitor-format','empty' => true,'key' => false,'default' => $element['queue']['monitor-format']['default'],'value' => $info['queue']['monitor-format']),$element['queue']['monitor-format']['value'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->checkbox(array('desc' => $this->bbf('fm_queue_monitor-join'),'name' => 'queue[monitor-join]','labelid' => 'queue-monitor-join','default' => $element['queue']['monitor-join']['default'],'checked' => $element['queue']['monitor-join']['default']),$info['queue']['monitor-join'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_joinempty'),'name' => 'queue[joinempty]','labelid' => 'queue-joinempty','bbf' => 'fm_queue_joinempty-opt-','key' => false,'default' => $element['queue']['joinempty']['default'],'value' => $info['queue']['joinempty']),$element['queue']['joinempty']['value'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_leavewhenempty'),'name' => 'queue[leavewhenempty]','labelid' => 'queue-leavewhenempty','bbf' => 'fm_queue_leavewhenempty-opt-','key' => false,'default' => $element['queue']['leavewhenempty']['default'],'value' => $info['queue']['leavewhenempty']),$element['queue']['leavewhenempty']['value'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->checkbox(array('desc' => $this->bbf('fm_queue_eventwhencalled'),'name' => 'queue[eventwhencalled]','labelid' => 'queue-eventwhencalled','default' => $element['queue']['eventwhencalled']['default'],'checked' => $element['queue']['eventwhencalled']['default']),$info['queue']['eventwhencalled'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->checkbox(array('desc' => $this->bbf('fm_queue_eventmemberstatus'),'name' => 'queue[eventmemberstatus]','labelid' => 'queue-eventmemberstatus','default' => $element['queue']['eventmemberstatus']['default'],'checked' => $element['queue']['eventmemberstatus']['default']),$info['queue']['eventmemberstatus'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->checkbox(array('desc' => $this->bbf('fm_queue_reportholdtime'),'name' => 'queue[reportholdtime]','labelid' => 'queue-reportholdtime','default' => $element['queue']['reportholdtime']['default'],'checked' => $element['queue']['reportholdtime']['default']),$info['queue']['reportholdtime'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_memberdelay'),'name' => 'queue[memberdelay]','labelid' => 'queue-memberdelay','bbf' => array('mixkey','fm_queue_memberdelay-opt'),'key' => false,'default' => $element['queue']['memberdelay']['default'],'value' => (int) $info['queue']['memberdelay'] ),$element['queue']['memberdelay']['value'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->checkbox(array('desc' => $this->bbf('fm_queue_timeoutrestart'),'name' => 'queue[timeoutrestart]','labelid' => 'queue-timeoutrestart','default' => $element['queue']['timeoutrestart']['default'],'checked' => $element['queue']['timeoutrestart']['default']),$info['queue']['timeoutrestart'],'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_queue-youarenext'),'name' => 'queue[queue-youarenext]','labelid' => 'queue-queue-youarenext','empty' => $this->bbf('fm_queue_queue-youarenext-opt-default'),'key' => false,'default' => $element['queue']['queue-youarenext']['default'],'value' => $info['queue']['queue-youarenext']),$announce_list,'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_queue-thereare'),'name' => 'queue[queue-thereare]','labelid' => 'queue-queue-thereare','empty' => $this->bbf('fm_queue_queue-thereare-opt-default'),'key' => false,'default' => $element['queue']['queue-thereare']['default'],'value' => $info['queue']['queue-thereare']),$announce_list,'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_queue-callswaiting'),'name' => 'queue[queue-callswaiting]','labelid' => 'queue-queue-callswaiting','empty' => $this->bbf('fm_queue_queue-callswaiting-opt-default'),'key' => false,'default' => $element['queue']['queue-callswaiting']['default'],'value' => $info['queue']['queue-callswaiting']),$announce_list,'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_queue-holdtime'),'name' => 'queue[queue-holdtime]','labelid' => 'queue-queue-holdtime','empty' => $this->bbf('fm_queue_queue-holdtime-opt-default'),'key' => false,'default' => $element['queue']['queue-holdtime']['default'],'value' => $info['queue']['queue-holdtime']),$announce_list,'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_queue-minutes'),'name' => 'queue[queue-minutes]','labelid' => 'queue-queue-minutes','empty' => $this->bbf('fm_queue_queue-minutes-opt-default'),'key' => false,'default' => $element['queue']['queue-minutes']['default'],'value' => $info['queue']['queue-minutes']),$announce_list,'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_queue-seconds'),'name' => 'queue[queue-seconds]','labelid' => 'queue-queue-seconds','empty' => $this->bbf('fm_queue_queue-seconds-opt-default'),'key' => false,'default' => $element['queue']['queue-seconds']['default'],'value' => $info['queue']['queue-seconds']),$announce_list,'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_queue-thankyou'),'name' => 'queue[queue-thankyou]','labelid' => 'queue-queue-thankyou','empty' => $this->bbf('fm_queue_queue-thankyou-opt-default'),'key' => false,'default' => $element['queue']['queue-thankyou']['default'],'value' => $info['queue']['queue-thankyou']),$announce_list,'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_queue-lessthan'),'name' => 'queue[queue-lessthan]','labelid' => 'queue-queue-lessthan','empty' => $this->bbf('fm_queue_queue-lessthan-opt-default'),'key' => false,'default' => $element['queue']['queue-lessthan']['default'],'value' => $info['queue']['queue-lessthan']),$announce_list,'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_queue-reporthold'),'name' => 'queue[queue-reporthold]','labelid' => 'queue-queue-reporthold','empty' => $this->bbf('fm_queue_queue-reporthold-opt-default'),'key' => false,'default' => $element['queue']['queue-reporthold']['default'],'value' => $info['queue']['queue-reporthold']),$announce_list,'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<?=$form->slt(array('desc' => $this->bbf('fm_queue_periodic-announce'),'name' => 'queue[periodic-announce]','labelid' => 'queue-periodic-announce','empty' => $this->bbf('fm_queue_periodic-announce-opt-default'),'key' => false,'default' => $element['queue']['periodic-announce']['default'],'value' => $info['queue']['periodic-announce']),$announce_list,'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

<div id="sb-part-member">

<?php
	if($member_list !== false):
?>
		<div id="memberlist" class="fm-field">
			<div>

		<?=$form->slt(array('name' => 'memberlist','label' => false,'id' => 'it-memberlist','multiple' => true,'size' => 5,'field' => false),$member_list,'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"')?>

			</div>
			<div id="inout-member">

		<a href="#" onclick="xivo_fm_move_selected('it-memberlist','it-member'); return(false);" title="<?=$this->bbf('bt-inmember')?>"><?=$url->img_html('img/site/button/row-left.gif',$this->bbf('bt-inmember'),'id="bt-inmember" border="0"');?></a><br />

		<a href="#" onclick="xivo_fm_move_selected('it-member','it-memberlist'); return(false);" title="<?=$this->bbf('bt-outmember')?>"><?=$url->img_html('img/site/button/row-right.gif',$this->bbf('bt-outmember'),'id="bt-outmember" border="0"');?></a>

			</div>
			<div class="txt-left">

		<?=$form->slt(array('name' => 'member[]','label' => false,'id' => 'it-member','multiple' => true,'size' => 5,'field' => false,'key' => 'name','key_val' => 'id'),$member_slt,'onfocus="this.className=\'it-mfocus\';" onblur="this.className=\'it-mblur\';"');?>

			</div>
		</div>
<?php
	else:
		echo '<div class="txt-center">',$url->href_html($this->bbf('create_member'),'service/ipbx/pbx_settings/users','act=add'),'</div>';
	endif;
?>

</div>
<div class="clearboth"></div>
