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

class xivo_service_asterisk_userfeatures extends xivo_service_asterisk_abstract
{
	var $_dso		= null;
	var $_name		= 'userfeatures';
	var $_filter	= false;
	var $_origin	= false;
	var $_identity	= false;

	public function __construct(&$sre,&$dso)
	{
		if(is_object($sre) === false)
			trigger_error('Invalid service in '.__CLASS__,E_USER_ERROR);

		if(is_object($dso) === false)
			trigger_error('Invalid datastorage in '.__CLASS__,E_USER_ERROR);

		$this->_sre = &$sre;
		$this->_dso = &$dso;

		$this->_load_config();
		$this->_identity = &$this->_sre->get_identity($this->_name);
	}

	function _prepare($data)
	{
		$this->_identity->_prepare_identity($data);

		$data['id'] = (int) $data['id'];
		$data['entityid'] = (int) $data['entityid'];
		$data['cti_profile_id'] = (int) $data['cti_profile_id'];
		$data['enableclient'] = (bool) $data['enableclient'];
		$data['enablehint'] = (bool) $data['enablehint'];
		$data['enablevoicemail'] = (bool) $data['enablevoicemail'];
		$data['enablexfer'] = (bool) $data['enablexfer'];
		$data['enableautomon'] = (bool) $data['enableautomon'];
		$data['callrecord'] = (bool) $data['callrecord'];
		$data['incallfilter'] = (bool) $data['incallfilter'];
		$data['enablednd'] = (bool) $data['enablednd'];
		$data['enablerna'] = (bool) $data['enablerna'];
		$data['enablebusy'] = (bool) $data['enablebusy'];
		$data['enableunc'] = (bool) $data['enableunc'];
		$data['commented'] = (bool) $data['commented'];
		$data['func_key_template_id'] = (int) $data['func_key_template_id'];
		$data['func_key_private_template_id'] = (int) $data['func_key_private_template_id'];

		return($data);
	}

	public function users_has_profile_id($cti_profile_id)
	{
		$arr = array();
		$arr['cti_profile_id'] = (int) $cti_profile_id;

		if($this->get_nb($arr) > 0)
			return(true);

		return(false);
	}

	function loginclient_exists($loginclient,$id=null)
	{
		if(dwho_has_len($loginclient) === false)
			return(null);

		$arr = array();
		$arr['loginclient'] = strtolower($loginclient);

		if(($primary = $this->get_primary($arr)) === false
		|| (empty($id) === false
		   && dwho_ulongint($primary) === dwho_ulongint($id)) === true)
			return(false);

		return(true);
	}

	function add($arr,$id=0)
	{
		if(is_array($arr) === false)
			return(false);
		else if(isset($arr['name']) === true)
		{
			$arr['name'] = strtolower($arr['name']);

			if((isset($arr['destrna']) === true
			   && $arr['name'] === $arr['destrna']) === true
			|| (isset($arr['destbusy']) === true
			   && $arr['name'] === $arr['destbusy']) === true
			|| (isset($arr['destunc']) === true
			   && $arr['name'] === $arr['destunc']) === true)
				return(false);
		}

		if(isset($arr['loginclient']) === true)
		{
			if($this->loginclient_exists($arr['loginclient'],
						     $id) === true)
				return(false);

			$arr['loginclient'] = strtolower($arr['loginclient']);
		}

		return(parent::add($arr,$id));
	}

	function edit($id,$arr,$provisioning=false,$disable=null)
	{
		if(is_array($arr) === false)
			return(false);
		else if(isset($arr['name']) === true)
		{
			$arr['name'] = strtolower($arr['name']);

			if((isset($arr['destrna']) === true
			   && $arr['name'] === $arr['destrna']) === true
			|| (isset($arr['destbusy']) === true
			   && $arr['name'] === $arr['destbusy']) === true
			|| (isset($arr['destunc']) === true
			   && $arr['name'] === $arr['destunc']) === true)
				return(false);
		}

		if(isset($arr['loginclient']) === true)
		{
			if($this->loginclient_exists($arr['loginclient'],
						     $id) === true)
				return(false);

			$arr['loginclient'] = strtolower($arr['loginclient']);
		}

		return(parent::edit($id,$arr,$disable));
	}

	function get_all_except($id=0,$number=false,$order=null,$limit=null)
	{
		if(($id = dwho_ulongint($id)) === 0 && (bool) $number === false)
			return($this->get_all(null,true,$order,$limit));

		if(isset($this->_origin_list) === true)
			$this->_origin_list = false;

		if(($list = $this->_dso->get_all_except($id,$number,$order,$limit)) === false
		|| isset($list[0]) === false)
			return(false);
		else if(isset($this->_origin_list) === true)
			$this->_origin_list = $list;

		return($this->_mk_list_assoc_prepare($list,false,true));
	}
}

?>
