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

dwho_file::required(array(XIVO_PATH_OBJECT,'application','xivo.inc'),true);

class xivo_application_xivo_object_entity extends xivo_application_xivo_object_abstract
{
	var $_entity	= false;
	var $_context	= false;

	function __construct(&$xobj,&$dso,$param=null,$components=true)
	{
		parent::__construct($xobj);

		if(is_object($dso) === false)
			trigger_error('Invalid Datastorage object',E_USER_ERROR);

		$this->_dso = &$dso;

		if(($this->_entity = &$this->_xobj->get_module('entity')) === false)
			return(false);
		else if((bool) $components === true)
			$this->load_components();

		return(true);
	}

	function load_components()
	{
		$this->_components = true;

		if(($_SRE = &dwho_gct::get('xivo_service')) === false
		|| ($this->_context = &$_SRE->get_module('ipbx','context')) === false)
			return(false);

		return(true);
	}

	function get($id,$disable=null,$type=null)
	{
		$this->_info = $this->_origin = array();

		if(($this->_info['entity'] = $this->_entity->get($id)) === false)
			return(false);

		$this->_origin['entity'] = $this->_entity->get_origin();

		if($this->_components === false)
			return($this->_info);

		$this->_info['deletable'] = $this->_get_context($type) === false;

		return($this->_info);
	}

	function get_nb($arr=null,$disable=null)
	{
		return($this->_entity->get_nb($arr,$disable));
	}

	function _get_context($type=null)
	{
		$this->_info['context'] = false;
		$this->_origin['context'] = false;

		$where = array();
		$where['entity'] = $this->_info['entity']['name'];
		if (is_null($type) === false)
			$where['contexttype'] = $type;

		if(($this->_info['context'] = $this->_context->get_all_where($where)) === false)
			return(false);

		$this->_origin['context'] = $this->_context->get_origin_list();

		return($this->_info['context']);
	}

	function get_elements()
	{
		$r = array();
		$r['entity'] = $this->_entity->get_element();

		return($r);
	}

	function get_entities_search($search,$disable=null,$order=null,$limit=null,$assoc=false,$type=null)
	{
		return($this->_get_entities('search',$search,$disable,$order,$limit,$assoc,$type));
	}

	function get_entities_list($disable=null,$order=null,$limit=null,$assoc=false,$type=null)
	{
		return($this->_get_entities('list',null,$disable,$order,$limit,$assoc,$type));
	}

	function _get_entities($action,$search='',$disable=null,$order=null,$limit=null,$assoc=false,$type=null)
	{
		$search = strval($search);

		if(is_object($this->_context) === false)
		{
			$this->_cnt = 0;
			return(false);
		}

		switch($action)
		{
			case 'search':
				$rs = $this->_dso->search($search,$disable,$order,$limit);
				$this->_cnt = $this->_dso->get_cnt();
				break;
			case 'list':
			default:
				$rs = $this->_entity->get_all($disable,true,$order,$limit);
				$this->_cnt = $this->_entity->get_cnt();
		}

		if($rs === false || ($nb = count($rs)) === 0)
			return(false);

		$r = array();

		for($i = 0;$i < $nb;$i++)
		{
			$r[$rs[$i]['id']]['entity'] = $rs[$i];

			$ref = &$r[$rs[$i]['id']];
			$ref['entity']['disable'] = (bool) $ref['entity']['disable'];
			$ref['entity']['identity'] = $this->_xobj->identity('entity',$ref['entity']);
			$ref['nb_context'] = dwho_ulongint($this->_context->get_nb(array('entity' => $rs[$i]['name'])));

			if (is_null($type) === false)
			{
				$where = array();
				$where['entity'] = $rs[$i]['name'];
				if (is_null($type) === false)
					$where['contexttype'] = $type;

				$ref['context'] = $this->_context->get_all_where($where);
			}
		}

		if(empty($r) === true)
			return(false);
		else if((bool) $assoc === false)
			return(array_values($r));

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

		if(($action !== 'add' && $action !== 'edit') === true)
			return(false);

		$this->set_entity($arr);

		return(($this->get_errnb() < 1));
	}

	function set_entity($arr)
	{
		if(is_array($arr) === false)
		{
			$this->_set_result('entity',null);
			$this->_set_error('entity','invalid data');
			return(false);
		}

		$arr['disable'] = false;

		if(($rs = $this->_entity->chk_values($arr)) === false)
		{
			$this->_set_result('entity',$this->_entity->get_filter_result());
			$this->_set_error('entity',$this->_entity->get_filter_error());
			return(false);
		}

		$this->_set_result('entity',$rs);

		return(true);
	}

	function add()
	{
		$r = true;

		$this->_status = 'add';
		$this->_return = array();

		if(($this->_dso->get_entity_name_already_exist($this->_result['entity']['name'])) === false)
		{
			$this->_set_error('entity',array('name' => 'already_exist'));
			return(false);
		}

		if($this->get_errnb() > 0
		|| $this->add_entity() === false)
			$r = false;

		$this->_status = '';

		return($r);
	}

	function add_entity()
	{
		if(($rs = $this->get_result('entity')) === null
		|| $rs === false)
			return(false);

		$this->_return['entity'] = $this->_entity->add($rs);

		return($this->_return['entity']);
	}

	function edit()
	{
		$r = true;

		$this->_status = 'edit';
		$this->_return = array();

		if($this->get_errnb() > 0
		|| $this->edit_entity() === false
		|| $this->edit_context() === false)
		{
			$r = false;
			$this->_reverse_edit();
		}

		$this->_status = '';

		return($r);
	}

	function edit_entity()
	{
		if(dwho_issa('entity',$this->_info) === false
		|| ($rs = $this->get_result('entity')) === null
		|| $rs === false)
			return(false);

		$rs['disable'] = $this->_info['entity']['disable'];

		$this->_return['entity'] = $this->_entity->edit($this->_info['entity']['id'],$rs);

		return($this->_return['entity']);
	}

	function edit_context()
	{
		if(is_object($this->_context) === false
		|| dwho_issa('entity',$this->_info) === false
		|| ($entity_name = (string) $this->get_result_var('entity','name')) === '')
			return(false);
		else if(strcmp($this->_info['entity']['name'],$entity_name) === 0)
			return(null);

		$this->_return['context'] = $this->_context->edit_where(array('entity' => $this->_info['entity']['name']),
									array('entity' => $entity_name));

		return($this->_return['context']);
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
				case 'entity':
					if(isset($this->_origin['entity']) === false
					|| $this->_origin['entity'] === false)
						$this->delete_entity();
					else
						$this->_entity->edit_origin($this->_origin['entity']);
					break;
			}
		}

		return(true);
	}

	function delete()
	{
		if(is_object($this->_context) === false
		|| isset($this->_info['deletable']) === false)
			return(false);
		else if($this->_info['deletable'] === false)
			return(null);

		$r = true;

		$this->_status = 'delete';
		$this->_return = array();

		if($this->delete_entity() === false)
			$r = false;

		$this->_status = '';

		return($r);
	}

	function delete_all()
	{
		return($this->_dso->delete_all());
	}

	function delete_entity()
	{
		if(dwho_issa('entity',$this->_info) === false)
			return(false);

		$r = $this->_entity->delete($this->_info['entity']['id']);

		if($this->_status === 'delete')
			$this->_return['entity'] = $r;

		return($r);
	}

	function enable()
	{
		$r = true;

		$this->_status = 'enable';
		$this->_return = array();

		if($this->enable_entity() === false)
			$r = false;

		$this->_status = '';

		return($r);
	}

	function disable()
	{
		$r = true;

		$this->_status = 'disable';
		$this->_return = array();

		if($this->disable_entity() === false)
			$r = false;

		$this->_status = '';

		return($r);
	}

	function enable_entity()
	{
		return($this->_enable_disable_entity(false));
	}

	function disable_entity()
	{
		return($this->_enable_disable_entity(true));
	}

	function _enable_disable_entity($disable=false)
	{
		if(dwho_issa('entity',$this->_info) === false)
			return(false);
		else if((bool) $disable === false)
			$r = $this->_entity->enable($this->_info['entity']['id']);
		else
			$r = $this->_entity->disable($this->_info['entity']['id']);

		if($this->_status === 'enable' || $this->_status === 'disable')
			$this->_return['entity'] = $r;

		return($r);
	}

	function add_from_json()
	{
		if(($data = $this->_get_data_from_json()) === false
		|| $this->set_add($data) === false
		|| $this->add() === false)
			return(false);

		return(true);
	}

	function array_merge_recursive($arr1, $arr2)
	{
		foreach($arr2 as $k => $v)
		{
			if(array_key_exists($k, $arr1) && is_array($arr1[$k]) && is_array($v))
				$arr1[$k] = $this->array_merge_recursive($arr1[$k], $arr2[$k]);
			else
				$arr1[$k] = $v;
		}

		return $arr1;
	}

	function edit_from_json($mergewith=null)
	{
		if(($data = $this->_get_data_from_json()) === false)
			return false;

		if(!is_null($mergewith))
			$arr = $this->array_merge_recursive($mergewith,$data);

		if($this->set_edit($arr) === false
		|| $this->edit() === false)
			return(false);

		return(true);
	}
}

?>
