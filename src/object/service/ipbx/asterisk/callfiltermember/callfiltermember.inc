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

xivo_service_asterisk::required(array('abstract','abstract.inc'),true);

class xivo_service_asterisk_callfiltermember extends xivo_service_asterisk_abstract
{
	var $_dso		= null;
	var $_name		= 'callfiltermember';
	var $_filter		= false;
	var $_origin		= false;
	var $_origin_list	= false;
	var $_func_key_manager = false;

	public function __construct(&$sre,&$dso)
	{
		if(is_object($sre) === false)
			trigger_error('Invalid service in '.__CLASS__,E_USER_ERROR);

		if(is_object($dso) === false)
			trigger_error('Invalid datastorage in '.__CLASS__,E_USER_ERROR);

		$this->_sre = &$sre;
		$this->_dso = &$dso;

		$this->_load_config();
	}

	function _prepare($data)
	{
		$data['active'] = (bool) $data['active'];

		return($data);
	}

	function add($arr,$id=0)
	{
		if(is_array($arr) === false)
			return(false);
		else if(isset($arr['typeval']) === true)
			$arr['typeval'] = strval($arr['typeval']);

		return(parent::add($arr,$id));
	}

	function edit($id,$arr,$disable=null)
	{
		if(is_array($arr) === false)
			return(false);
		else if(isset($arr['typeval']) === true)
			$arr['typeval'] = strval($arr['typeval']);

		return(parent::edit($id,$arr,$disable));
	}

	function is_valid($type,$typeval,$disable=null)
	{
		$type = strval($type);

		switch($type)
		{
			case 'user':
				$typeval = dwho_ulongint($typeval);
				$module = $this->_sre->get_module('userfeatures');
				break;
			default:
				return(false);
		}

		if(($info = $module->get($typeval,$disable)) === false)
			return(false);

		return($info);
	}

	public function get_by_boss_secretary($boss_id, $secreatary_id)
	{
		return($this->_dso->get_by_boss_secretary($boss_id, $secreatary_id));
	}

	public function get_secretary_member_ids_for_bsfilter($filter_id)
	{
		return($this->_dso->get_secretary_member_ids_for_bsfilter($filter_id));
	}

	public function delete_member($user_id)
	{
		if(($_MGR = &dwho_gct::get('xivo_manager')) === false
		|| ($func_key_manager = &$_MGR->get_ressource('func_key')) === false)
			return(false);

		if($func_key_manager->delete_bsfilter_for_user($user_id) === false)
			return(false);

		$where = array('type' => 'user',
					   'typeval' => (string) $user_id);

		return($this->delete_where($where));
	}
}

?>
