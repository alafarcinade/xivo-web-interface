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

dwho_file::required(array(XIVO_PATH_OBJECT,'application','service','ipbx','asterisk.inc'),true);

class xivo_application_service_asterisk_callfilter extends xivo_application_asterisk_abstract
{
	var $_dso		        = null;
	var $_callfilter	    = false;
	var $_userfeatures	    = false;
	var $_callfiltermember	= false;
	var $_dialaction	    = false;
	var $_callerid		    = false;
	var $_func_key_manager  = false;
	var $_param		        = array('type'	=> '');

	public function __construct(&$service,&$dso,$param=null,$components=true)
	{
		parent::__construct($service);

		if(is_object($dso) === false)
			trigger_error('Invalid Datastorage object',E_USER_ERROR);

		$this->_dso = &$dso;

		if(($this->_callfilter = &$this->_service->get_module('callfilter')) === false
		|| ($this->_userfeatures = &$this->_service->get_module('userfeatures')) === false
		|| $this->load_init($param) === false)
			return(false);
		else if((bool) $components === true)
			$this->load_components();

		return(true);
	}

	function load_init($param=array())
	{
		if($this->set_params($param) === false
		|| isset($param['type']) === false
		|| $this->_callfilter->chk_element_value('type',$param['type']) === false)
		{
			$this->_param['type'] = '';
			return(false);
		}

		$this->_param['type'] = $param['type'];

		return(true);
	}

	function load_components()
	{
		$this->_components = true;

		if(($this->_callfiltermember = &$this->_service->get_module('callfiltermember')) === false
		|| ($this->_dialaction = $this->get_abstract('dialaction',array('category' => 'callfilter'))) === false
		|| ($this->_callerid = $this->get_abstract('callerid',array('type' => 'callfilter'))) === false
		|| ($_MGR = &dwho_gct::get('xivo_manager')) === false
		|| ($this->_func_key_manager = &$_MGR->get_ressource('func_key')) === false)
			return(false);

		return(true);
	}

	function get($id,$disable=null)
	{
		$this->_info = $this->_origin = array();

		if(($this->_info['callfilter'] = $this->_callfilter->get($id,$disable)) === false)
			return(false);

		$this->_origin['callfilter'] = $this->_callfilter->get_origin();

		if($this->_components === false)
			return($this->_info);

		$this->_get_callfiltermember();
		$this->_get_dialaction($this->_info['callfilter']['id']);
		$this->_get_callerid($this->_info['callfilter']['id']);

		return($this->_info);
	}

	function _get_all_callfiltermember_user()
	{
		$this->_info['callfilteruser'] = false;
		$this->_origin['callfilteruser'] = false;

		if(($rs = $this->_dso->get_all_callfiltermember_user($this->_param['type'],
								     $this->_info['callfilter']['id'])) === false
		|| ($nb = $this->_dso->get_cnt()) === 0)
			return(false);

		$this->_info['callfilteruser'] = array();
		$this->_origin['callfilteruser'] = array();

		$this->_info['userfeatures'] = array();

		for($i = 0;$i < $nb;$i++)
		{
			$ref = &$rs[$i];

			$user = array();
			$user['firstname'] = $ref['member_firstname'];
			$user['lastname'] = $ref['member_lastname'];

			unset($ref['member_firstname'],
			      $ref['member_lastname']);

			$user['identity'] = $this->_userfeatures->mk_identity($user);

			$this->_info['userfeatures'][] = $user;
			$this->_origin['callfiltermember'][] = $ref;
			$this->_info['callfiltermember'][] = $this->_callfiltermember->_prepare($ref);
		}

		return(true);
	}

	function _get_callfiltermember()
	{
		switch($this->_param['type'])
		{
			case 'bosssecretary':
				return($this->_get_callfilterbs());
		}

		return(false);
	}

	function _get_callfilterbs()
	{
		$r = true;

		$this->_info['callfiltermember'] = array();
		$this->_origin['callfiltermember'] = array();

		$where = array();
		$where['type'] = 'user';
		$where['bstype'] = 'boss';
		$where['callfilterid'] = $this->_info['callfilter']['id'];

		if(($this->_info['callfiltermember']['boss'] = $this->_callfiltermember->get_where($where)) === false)
		{
			$r = false;
			$this->_info['callfiltermember']['boss'] = null;
			$this->_origin['callfiltermember'] = false;
		}
		else
			$this->_origin['callfiltermember'][] = $this->_callfiltermember->get_origin();

		$where['bstype'] = 'secretary';
		$order = array('priority' => SORT_ASC);

		if(($this->_info['callfiltermember']['secretary'] = $this->_callfiltermember->get_all_where($where,null,true,$order)) === false)
		{
			$this->_info['callfiltermember']['secretary'] = null;
			$this->_origin['callfiltermember'] = false;
			return(false);
		}
		else if($r === false)
			return($this->_info['callfiltermember']);

		$this->_origin['callfiltermember'] = array_merge($this->_origin['callfiltermember'],
								 $this->_callfiltermember->get_origin_list());

		return($this->_info['callfiltermember']);
	}

	function get_boss_users($disable=null)
	{
		$order = array();
		$order['firstname'] = SORT_ASC;
		$order['lastname'] = SORT_ASC;

		if(($rs = $this->_dso->get_boss_users($disable,$order)) == false
		|| ($nb = count($rs)) === false)
			return(false);

		for($i = 0;$i < $nb;$i++)
			$rs[$i] = $this->_userfeatures->_prepare($rs[$i]);

		return($rs);
	}

	function get_free_boss_users()
	{
		$order = array();
		$order['firstname'] = SORT_ASC;
		$order['lastname'] = SORT_ASC;

		if(($rs = $this->_dso->get_free_boss_users($order)) == false
		|| ($nb = count($rs)) === false)
			return(false);

		for($i = 0;$i < $nb;$i++)
			$rs[$i] = $this->_userfeatures->_prepare($rs[$i]);

		return($rs);
	}

	function get_secretary_users($disable=null,$assoc=false,$callfilterid=null)
	{
		$order = array();
		$order['firstname'] = SORT_ASC;
		$order['lastname'] = SORT_ASC;

		if(($rs = $this->_dso->get_secretary_users($disable,$order,null,$callfilterid)) == false
		|| ($nb = count($rs)) === false)
			return(false);

		$r = array();
		for($i = 0;$i < $nb;$i++)
		{
			$r[$rs[$i]['id']] = $this->_userfeatures->_prepare($rs[$i]);
		}

		if((bool) $assoc === false)
			$r = array_values($r);

		if(empty($r) === true)
			$r = false;

		return($r);
	}

	function get_elements()
	{
		$r = array();
		$r['callfilter'] = $this->_callfilter->get_element();
		$r['callfiltermember'] = $this->_callfiltermember->get_element();
		$r['userfeatures'] = $this->_userfeatures->get_element();
		$r['dialaction'] = $this->_dialaction->get_element();
		$r['callerid'] = $this->_callerid->get_element();

		return($r);
	}

	function get_callfilters_list($disable=null,$order=null,$limit=null,$assoc=false)
	{
		$rs = $this->_dso->get_all($this->_param['type'],$disable,$order,$limit);

		$this->_cnt = $this->_dso->get_cnt();

		if($rs === false || ($nb = count($rs)) === 0)
			return(false);

		$r = array();

		for($i = 0;$i < $nb;$i++)
		{
			$r[$rs[$i]['id']] = $this->_callfilter->_prepare($rs[$i]);
			$ref = &$r[$rs[$i]['id']];

			if(dwho_empty($ref['member_id']) === true)
			{
				$ref['member_identity'] = '';
				$ref['linked'] = false;
			}
			else
			{
				$ref['linked'] = (bool) $ref['linked'];

				$member = array();
				$member['firstname'] = $ref['member_firstname'];
				$member['lastname'] = $ref['member_lastname'];
				$member['identity'] = $this->_userfeatures->mk_identity($member);
				$ref['member_identity'] = $member['identity'];
			}
		}

		if(empty($r) === true)
			return(false);
		else if((bool) $assoc === false)
			return(array_values($r));

		return($r);
	}

	function get_dialaction_result()
	{
		return($this->_dialaction->get_result_for_display());
	}

	function get_dialaction_destination_list()
	{
		$r = $this->get_destination_list();
		$r['sounds'] = $this->_dialaction->get_sound();

		return($r);
	}

	function set_add($arr)
	{
		return($this->_set('add',$arr));
	}

	function set_edit($arr)
	{
		if(empty($this->_info) === true)
			return(false);

		return($this->_set('edit',$arr));
	}

	function _set($action,$arr)
	{
		$this->_reset();

		if(($action !== 'add' && $action !== 'edit') === true
		|| dwho_issa('callfilter',$arr) === false)
			return(false);

		$this->set_callfilter($arr['callfilter']);

		switch($this->_param['type'])
		{
			case 'bosssecretary':
				if(dwho_issa('callfiltermember',$arr) === false)
					return(false);

				$this->set_callfiltermember($arr['callfiltermember']);
				break;
			default:
				return(false);
		}

		if(dwho_issa('dialaction',$arr) === true)
			$this->set_dialaction($arr['dialaction']);

		if(dwho_issa('callerid',$arr) === true)
			$this->set_callerid($arr['callerid']);

		return(($this->get_errnb() < 1));
	}

	function set_callfilter($arr)
	{
		if(is_array($arr) === false)
		{
			$this->_set_result('callfilter',null);
			$this->_set_error('callfilter','invalid data');
			return(false);
		}

		$arr['commented'] = false;

		if(($rs = $this->_callfilter->chk_values($arr)) === false)
		{
			$this->_set_result('callfilter',$this->_callfilter->get_filter_result());
			$this->_set_error('callfilter',$this->_callfilter->get_filter_error());
			return(false);
		}
		else if($this->_param['type'] !== $rs['type']
		|| $this->_callfilter->chk_element_value($this->_param['type'],$rs[$this->_param['type']]) === false)
		{
			$rs[$this->_param['type']] = '';
			$this->_set_result('callfilter',$rs);
			$this->_set_error('callfilter','invalid '.$this->_param['type']);
			return(false);
		}

		$this->_set_result('callfilter',$rs);

		return(true);
	}

	function set_callfiltermember($arr)
	{
		switch($this->_param['type'])
		{
			case 'bosssecretary':
				return($this->_set_callfilterbs($arr));
		}

		return(false);
	}

	function _set_callfilterbs($list)
	{
		if(is_array($list) === false
		|| isset($list['boss'],$list['secretary']) === false
		|| is_array($list['boss']) === false
		|| isset($list['boss']['id']) === false
		|| is_array($list['secretary']) === false
		|| ($nb = count($list['secretary'])) === 0)
		{
			$this->_set_result('callfiltermember',null);
			$this->_set_error('callfiltermember','invalid data');
			return(false);
		}

		$secretarylist = array_values($list['secretary']);

		$tmp = $reslist = array();

		$info = array();
		$info['type'] = 'user';
		$info['bstype'] = 'boss';
		$info['callfilterid'] = 0;
		$info['priority'] = 0;
		$info['ringseconds'] = 0;
		$info['active'] = false;

		$where = array();
		$where['bsfilter'] = 'boss';

		if(($boss = $this->_userfeatures->get_where($where,null,(int) $list['boss']['id'])) === false)
		{
			$this->_set_result('callfiltermember',null);
			$this->_set_error('callfiltermember','wrong boss');
			return(false);
		}

		$info['typeval'] = $boss['id'];

		if(isset($list['boss']['ringseconds']) === true)
			$info['ringseconds'] = $list['boss']['ringseconds'];

		if(($rs = $this->_callfiltermember->chk_values($info)) === false)
		{
			$this->_set_result('callfiltermember',$this->_callfiltermember->get_filter_result());
			$this->_set_error('callfiltermember',$this->_callfiltermember->get_filter_error());
			return(false);
		}

		$reslist['boss'] = $rs;
		$tmp[$boss['id']] = 1;

		$reslist['secretary'] = array();
		$info['bstype'] = 'secretary';
		$info['ringseconds'] = 0;

		$where = array();
		$where['bsfilter'] = 'secretary';

		for($i = 0;$i < $nb;$i++)
		{
			if(($secretary = $this->_userfeatures->get_where($where,null,(int) $secretarylist[$i])) === false
			|| isset($tmp[$secretary['id']]) === true)
			{
				$this->_set_result('callfiltermember',$reslist);
				$this->_set_error('callfiltermember','wrong secretary');
				return(false);
			}

			$info['priority'] = $i;
			$info['typeval'] = $secretary['id'];

			if(($rs = $this->_callfiltermember->chk_values($info)) === false)
			{
				$this->_set_result('callfiltermember',$reslist);
				$this->_set_error('callfiltermember','wrong secretary');
				return(false);
			}

			$reslist['secretary'][] = $rs;
			$tmp[$secretary['id']] = 1;
		}

		if(isset($reslist['secretary'][0]) === false)
		{
			$this->_set_result('callfiltermember',false);
			return(false);
		}

		$this->_set_result('callfiltermember',$reslist);

		return(true);
	}

	function add()
	{
		$r = true;

		$this->_status = 'add';
		$this->_return = array();
		$callfilterid = null;

		if($this->get_errnb() > 0
		|| ($callfilterid = $this->add_callfilter()) === false
		|| $this->add_callfiltermember($callfilterid) === false
		|| $this->add_dialaction($callfilterid) === false
		|| $this->add_callerid($callfilterid) === false
		|| $this->add_func_keys($callfilterid) === false)
		{
			$r = false;

			if($callfilterid !== null)
				$this->_reverse_add();
		}

		$this->_status = '';

		return($r);
	}

	function add_callfilter()
	{
		if(($rs = $this->get_result('callfilter')) === null)
			return(false);

		return(($this->_return['callfilter'] = $this->_callfilter->add($rs)));
	}

	function add_callfiltermember($callfilterid)
	{
		switch($this->_param['type'])
		{
			case 'bosssecretary':
				return($this->_add_callfilterbs($callfilterid));
		}

		return(false);
	}

	function _add_callfilterbs($callfilterid)
	{
		if(($callfilterid = dwho_ulongint($callfilterid)) === 0
		|| ($rs = $this->get_result('callfiltermember')) === null
		|| $rs === false
		|| isset($rs['boss'],$rs['secretary']) === false
		|| ($nb = count($rs['secretary'])) === 0)
			return(false);

		$this->_return['callfiltermember'] = array();

		$where = array();
		$where['type'] = $rs['boss']['type'];
		$where['typeval'] = $rs['boss']['typeval'];
		$where['bstype'] = $rs['boss']['bstype'];

		$rs['boss']['callfilterid'] = $callfilterid;

		if($this->_callfiltermember->get_primary($where) !== false
		|| ($this->_return['callfiltermember'][] = $this->_callfiltermember->add($rs['boss'])) === false)
			return(false);

		for($i = 0;$i < $nb;$i++)
		{
			$ref = &$rs['secretary'][$i];
			$ref['callfilterid'] = $callfilterid;

			if(($this->_return['callfiltermember'][] = $this->_callfiltermember->add($ref)) === false)
				return(false);
		}

		if(isset($this->_return['callfiltermember'][1]) === false)
			$this->_return['callfiltermember'] = false;

		return(true);
	}

	function add_func_keys($callfilterid)
	{
		if($this->_param['type'] !== 'bosssecretary')
			return(false);

		return($this->_func_key_manager->add_dst_for_bsfilter($callfilterid));
	}

	function _reverse_add()
	{
		if($this->get_errnb() > 0
		|| $this->_status !== 'add'
		|| is_array($this->_return) === false
		|| empty($this->_return) === true)
			return(false);

		foreach($this->_return as $key => $val)
		{
			if($val === false)
				continue;

			switch($key)
			{
				case 'callfilter':
					$this->_callfilter->delete($val);
					break;
				case 'callfiltermember':
					break;
				case 'dialaction':
					$this->_dialaction->reverse_add();
					break;
				case 'callerid':
					$this->_callerid->reverse_add();
					break;
			}
		}

		return(true);
	}

	function edit()
	{
		$r = true;

		$this->_status = 'edit';
		$this->_return = array();

		if($this->get_errnb() > 0
		|| $this->edit_callfilter() === false
		|| $this->edit_callfiltermember() === false
		|| $this->edit_dialaction() === false
		|| $this->edit_callerid() === false)
		{
			$r = false;
			$this->_reverse_edit();
		}

		$this->_status = '';

		return($r);
	}

	function edit_callfilter()
	{
		if(dwho_issa('callfilter',$this->_info) === false
		|| ($rs = $this->get_result('callfilter')) === null
		|| $rs === false)
			return(false);

		$rs['commented'] = $this->_info['callfilter']['commented'];

		return(($this->_return['callfilter'] = $this->_callfilter->edit($this->_info['callfilter']['id'],$rs)));
	}

	function edit_callfiltermember()
	{
		switch($this->_param['type'])
		{
			case 'bosssecretary':
				return($this->_edit_callfilterbs());
		}

		return(false);
	}

	function _edit_callfilterbs()
	{
		if(dwho_issa('callfilter',$this->_info) === false)
			return(false);

		$callfilter_id = (int) $this->_info['callfilter']['id'];

		if(($rs = $this->get_result('callfiltermember')) === null
		|| $rs === false
		|| isset($rs['boss'],$rs['secretary']) === false
		|| ($nb_secretary = count($rs['secretary'])) === 0)
			return(false);

		$this->_return['callfiltermember'] = array();

		if(dwho_issa('boss',$this->_info['callfiltermember']) === true)
		{
			$callfiltermember_id = (int) $this->_info['callfiltermember']['boss']['id'];
			$rs['boss']['active'] = (bool) $this->_info['callfiltermember']['boss']['active'];
			$rs['boss']['callfilterid'] = (int) $callfilter_id;
			if($this->_callfiltermember->edit($callfiltermember_id,$rs['boss']) === false)
				return(false);
		}

		$secretary_data = dwho_array_set_value_to_key($this->_info['callfiltermember']['secretary'], 'typeval');
		$secretary_data_form = dwho_array_set_value_to_key($rs['secretary'], 'typeval');
		$secretaries_added = array_diff_assoc($secretary_data_form, $secretary_data);
		$secretaries_deleted = array_diff_assoc($secretary_data, $secretary_data_form);

		foreach ($secretaries_added as $secretary_added)
		{
			$secretary_added['callfilterid'] = $callfilter_id;
			$filtermember_id = $this->_callfiltermember->add($secretary_added);
			$this->_func_key_manager->create_bsfilter_func_key_for_filtermember_id($filtermember_id);
		}

		foreach ($secretaries_deleted as $secretary_deleted)
		{
			$this->_func_key_manager->delete_bsfilter_for_user($secretary_deleted['typeval']);
			$this->_callfiltermember->delete($secretary_deleted['id']);
		}
		return(true);
	}

	function edit_dialaction()
	{
		if(dwho_issa('callfilter',$this->_info) === false)
			return(false);

		return(parent::edit_dialaction($this->_info['callfilter']['id']));
	}

	function edit_callerid()
	{
		if(dwho_issa('callfilter',$this->_info) === false)
			return(false);

		return(parent::edit_callerid($this->_info['callfilter']['id']));
	}

	function _reverse_edit()
	{
		if($this->get_errnb() > 0
		|| $this->_status !== 'edit'
		|| is_array($this->_return) === false
		|| empty($this->_return) === true)
			return(false);

		foreach($this->_return as $key => $val)
		{
			if($val === false)
				continue;

			switch($key)
			{
				case 'callfilter':
					if(isset($this->_origin['callfilter']) === false
					|| $this->_origin['callfilter'] === false)
						$this->delete_callfilter();
					else
						$this->_callfilter->edit_origin($this->_origin['callfilter']);
					break;
				case 'callfiltermember':
					break;
				case 'dialaction':
					$this->_dialaction->reverse_edit($this->_status);
					break;
				case 'callerid':
					$this->_callerid->reverse_edit($this->_status);
					break;
			}
		}

		return(true);
	}

	function delete()
	{
		$r = true;

		$this->_status = 'delete';
		$this->_return = array();

		if($this->delete_func_keys() === false
		|| $this->delete_callfilter() === false
		|| $this->delete_callfiltermember() === false
		|| $this->delete_dialaction() === false
		|| $this->delete_callerid() === false)
		{
			$r = false;
			$this->_reverse_delete();
		}
		else
			$this->unlinks();

		$this->_status = '';

		return($r);
	}

	function delete_callfilter()
	{
		if(dwho_issa('callfilter',$this->_info) === false)
			return(false);

		$r = $this->_callfilter->delete($this->_info['callfilter']['id']);

		if($this->_status === 'delete')
			$this->_return['callfilter'] = $r;

		return($r);
	}

	function delete_callfiltermember()
	{
		switch($this->_param['type'])
		{
			case 'bosssecretary':
				return($this->_delete_callfilterbs());
		}

		return(false);
	}

	function _delete_callfilterbs()
	{
		if(dwho_issa('callfiltermember',$this->_info) === false
		|| (isset($this->_info['callfiltermember']['boss']) === false
		   && isset($this->_info['callfiltermember']['secretary']) === false) === true)
			return(null);
		else if(dwho_issa('callfilter',$this->_info) === false)
			return(false);

		$where = array();
		$where['type'] = 'user';
		$where['callfilterid'] = $this->_info['callfilter']['id'];

		$r = $this->_callfiltermember->delete_where($where);

		if($this->_status === 'delete')
			$this->_return['callfiltermember'] = $r;

		return($r);
	}

	function delete_func_keys()
	{
		if($this->_param['type'] !== 'bosssecretary'
			|| dwho_issa('callfilter', $this->_info) === false)
			return(null);

		$this->_func_key_manager->delete_dst_for_bsfilter($this->_info['callfilter']['id']);
	}

	function _reverse_delete()
	{
		if($this->_status !== 'delete'
		|| is_array($this->_return) === false
		|| empty($this->_return) === true)
			return(false);

		foreach($this->_return as $key => $val)
		{
			if($val === false)
				continue;

			switch($key)
			{
				case 'callfilter':
					if(isset($this->_origin['callfilter']) === true
					&& $this->_origin['callfilter'] !== false)
						$this->_callfilter->add_origin($this->_origin['callfilter']);
					break;
				case 'callfiltermember':
					if(isset($this->_origin['callfiltermember']) === true)
						$this->_callfiltermember->add_origin_list($this->_origin['callfiltermember']);
					break;
				case 'dialaction':
					$this->_dialaction->add_origin_list();
					break;
				case 'callerid':
					$this->_callerid->add_origin();
					break;
			}
		}

		return(true);
	}

	function unlinks()
	{
		if(dwho_issa('callfilter',$this->_info) === false)
			return(false);

		$this->_dialaction->unlinked('callfilter',$this->_info['callfilter']['id']);

		return(true);
	}

	function enable()
	{
		$r = true;

		$this->_status = 'enable';
		$this->_return = array();

		if($this->enable_callfilter() === false)
			$r = false;

		$this->_status = '';

		return($r);
	}

	function disable()
	{
		$r = true;

		$this->_status = 'disable';
		$this->_return = array();

		if($this->disable_callfilter() === false)
			$r = false;

		$this->_status = '';

		return($r);
	}

	function enable_callfilter()
	{
		return($this->_enable_disable_callfilter(false));
	}

	function disable_callfilter()
	{
		return($this->_enable_disable_callfilter(true));
	}

	function _enable_disable_callfilter($disable=false)
	{
		if(dwho_issa('callfilter',$this->_info) === false)
			return(false);
		else if((bool) $disable === false)
			$r = $this->_callfilter->enable($this->_info['callfilter']['id']);
		else
			$r = $this->_callfilter->disable($this->_info['callfilter']['id']);

		if($this->_status === 'enable' || $this->_status === 'disable')
			$this->_return['callfilter'] = $r;

		return($r);
	}
}

?>
