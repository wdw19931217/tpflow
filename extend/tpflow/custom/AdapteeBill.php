<?php
namespace tpflow\custom;
/**
*+------------------
* Tpflow 单据适配类
*+------------------
* Copyright (c) 2006~2018 http://cojz8.cn All rights reserved.
*+------------------
* Author: guoguo(1838188896@qq.com)
*+------------------
*/
use tpflow\inheritance\InterfaceBill;
use think\facade\Db;

class AdapteeBill implements InterfaceBill
{
	
	public  function getbill($bill_table,$bill_id){
		if ($bill_table == '' || $bill_id == '' ) {
			return false;
		}
		$info = Db::name($bill_table)->find($bill_id);
		if($info){
			return  $info;
		}else{
			return  false;
		}
	}
    public function getbillvalue($bill_table,$bill_id,$bill_field){
		$result = Db::name($bill_table)->where('id',$bill_id)->value($bill_field);
		 if(!$result){
            return  false;
        }
        return $result;
	}
    public function updatebill($bill_table,$bill_id,$updata){
		$result = Db::name($bill_table)->where('id',$bill_id)->update(['status'=>$updata,'uptime'=>time()]);
		 if(!$result){
            return  false;
        }
        return $result;
	}
	 public function checkbill($bill_table,$bill_id,$where){
		return Db::name($bill_table)->where($where)->where('id',$bill_id)->find();
		 
	}
	
	
}