<?php

namespace app\common\model;

use think\Model;

/**
* 
*/
class AdminUser extends Model
{
	protected $autoWriteTimestamp = 'datetime';
	
	public function add($data){
		if (!is_array($data)) {
			exception("传递数据不合法，不是数组");
		}

		$this->allowField(true)->save($data);        //allowField(true) 当所传的字段在数据表中没有时报错
		return $this->id;
	}
}