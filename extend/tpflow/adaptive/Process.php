<?php
/**
*+------------------
* Tpflow 工作流步骤
*+------------------
* Copyright (c) 2006~2018 http://cojz8.cn All rights reserved.
*+------------------
* Author: guoguo(1838188896@qq.com)
*+------------------ 
*/

namespace tpflow\adaptive;

use tpflow\lib\unit;

class Process{
	
	protected $mode ; 
    public function  __construct(){
		if(unit::gconfig('wf_db_mode')==1){
			$className = '\\tpflow\\custom\\think\\AdapteeProcess';
		}else{
			$className = unit::gconfig('wf_db_namespace').'AdapteeProcess';
		}
		$this->mode = new $className();
    }
	/**
	 * 根据ID获取流程信息
	 *
	 * @param $pid 步骤编号
	 */
	static function find($pid)
	{
		return (new Process())->mode->find($pid);
	}
	static function FindRun($id,$field='*')
	{
		return (new Process())->mode->FindRun($id,$field='*');
	}
	/**
	 * 根据ID获取流程信息
	 *
	 * @param $pid 步骤编号
	 */
	static function GetProcessInfo($pid,$run_id='')
	{
		$info = (new Process())->mode->find($pid);
		if($info['auto_person']==3){ //办理人员
			$ids = explode(",",$info['range_user_text']);
			$info['todo'] = ['ids'=>explode(",",$info['range_user_ids']),'text'=>explode(",",$info['range_user_text'])];
		}
		if($info['auto_person']==4){ //办理人员
			$info['todo'] = $info['auto_sponsor_text'];
		}
		if($info['auto_person']==5){ //办理角色
			$info['todo'] = $info['auto_role_text'];
		}
		if($info['auto_person']==6){ //办理角色
				$wf  =  Flow::FindRun($run_id);
				$user_id = Bill::getbillvalue($wf['from_table'],$wf['from_id'],$wf_process['work_text']);
				$info['todo']= User::GetUserName($user_id);
			}
		return $info;
	}
	/**
	 * 同步步骤信息
	 *
	 * @param $pid 步骤编号
	 */
	static function GetProcessInfos($ids,$run_id)
	{
		$info = (new Process())->mode->finds($ids);
		foreach($info as $k=>$v){
			if($v['auto_person']==3){ //办理人员
				$ids = explode(",",$info['range_user_text']);
				$info[$k]['todo'] = ['ids'=>explode(",",$v['range_user_ids']),'text'=>explode(",",$v['range_user_text'])];
			}
			if($v['auto_person']==4){ //办理人员
				$info[$k]['todo'] = $v['auto_sponsor_text'];
			}
			if($v['auto_person']==5){ //办理角色
				$info[$k]['todo'] = $v['auto_role_text'];
			}
			if($v['auto_person']==6){ //办理角色
				$wf  =   Flow::FindRun($run_id);
				$user_id = Bill::getbillvalue($wf['from_table'],$wf['from_id'],$wf_process['work_text']);
				$user_info = User::GetUserInfo($user_id);
				$info['user_info']= $user_info;
				$info[$k]['todo']= $user_info['username'];
			}
		}
		return $info;
	}
	/**
	 * 获取下个审批流信息
	 *
	 * @param $wf_type 单据表
	 * @param $wf_fid  单据id
	 * @param $pid   流程id
	 * @param $premode   上一个步骤的模式
	 **/
	static function GetNexProcessInfo($wf_type,$wf_fid,$pid,$run_id,$premode='')
	{
		if($pid==''){
			return [];
		}
		$nex = (new Process())->mode->find($pid);
		//先判断下上一个流程是什么模式
		if($nex['process_to'] !=''){
		$nex_pid = explode(",",$nex['process_to']);
		$out_condition = json_decode($nex['out_condition'],true);
			/* 加入同步模式 2为同步模式
			 * 2019年1月28日14:30:52
			 *1、加入同步模式    2、先获取本步骤信息 3、获取本步骤的模式   4、根据模式进行读取  5、获取下一步骤需要的信息
			 **/
			switch ($nex['wf_mode']){
			case 0:
			  $process = self::GetProcessInfo($nex_pid,$run_id);
			  break;
			case 1:
				//多个审批流
				foreach($out_condition as $key=>$val){
					$where =implode(",",$val['condition']);
					//根据条件寻找匹配符合的工作流id
					$info = Bill::checkbill($wf_type,$wf_fid,$where);
					if($info){
						$nexprocessid = $key; //获得下一个流程的id
						break;	
					}
				}
				$process = self::GetProcessInfo($nexprocessid,$run_id);
			   break;
			case 2:
				$process = self::GetProcessInfos($nex_pid,$run_id);
			  break;
			}
		}else{
			$process = ['auto_person'=>'','id'=>'','process_name'=>'END','todo'=>'结束'];
		}
		return $process;
	}
	/**
	 * 获取前步骤的流程信息
	 *
	 * @param $runid
	 */
	static function GetPreProcessInfo($runid)
	{
		$mode = (new Process())->mode;
		$pre = [];
		$pre_n = $mode->FindRunProcess($runid);
		//获取本流程中小于本次ID的步骤信息
		$pre_p = $mode->SearchRunProcess([['run_flow','=',$pre_n['run_flow']],['run_id','=',$pre_n['run_id']],['id','<',$pre_n['id']]],'run_flow_process');
		//遍历获取小于本次ID中的相关步骤
		foreach($pre_p as $k=>$v){
			$pre[] = $mode->find($v['run_flow_process']);
		}
		$prearray = [];
		if(count($pre)>=1){
			$prearray[0] = '退回制单人修改';
			foreach($pre as $k => $v){
				if($v['auto_person']==4){ //办理人员
					$todo = $v['auto_sponsor_text'];
				}
				if($v['auto_person']==5){ //办理角色
					$todo = $v['auto_role_text'];
				}
				$prearray[$v['id']] = $v['process_name'].'('.$todo.')';
			}
			}else{
			$prearray[0] = '退回制单人修改';	
		}
		return $prearray;
	}
	/**
	 * 获取前步骤的流程信息
	 *
	 * @param $runid
	 */
	static function Getrunprocess($pid,$run_id)
	{
		return (new Process())->mode->Getrunprocess($pid,$run_id);
	}
	/**
	 * 同步模式下获取未办结的流程信息
	 *
	 * @param $run_id 运行中的ID
	 * @param $run_process 运行中的流程ID
	 */
	static function Getnorunprocess($run_id,$run_process)
	{
		return (new Process())->mode->SearchRunProcess([['run_id','=',$run_id],['status','=',0],['id','<>',$run_process]]);
	}
	/**
	 * 获取第一个流程
	 *
	 * @param $wf_id
	 */
	static function getWorkflowProcess($wf_id) 
	{
		$flow_process = (new Process())->mode->SearchFlowProcess([['flow_id','=',$wf_id],['is_del','=',0]]);
		//找到 流程第一步
        $flow_process_first = array();
        foreach($flow_process as $value)
        {
            if($value['process_type'] == 'is_one')
            {
                $flow_process_first = $value;
                break;
            }
        }
		if(!$flow_process_first)
        {
            return  false;
        }
		return $flow_process_first;
	}
	/**
	 * 阻止重复提交
	 *
	 * @param $id
	 */
	static function run_check($id) 
	{
		$data = (new Process())->mode->FindRunProcess($id);
		return $data['status'];
	}
	/**
	 *新增会签
	 *
	 *@param $config 参数信息
	 **/
	static function AddSing($config)
	{
		$data = [
			'run_id'=>$config['run_id'],
			'run_flow'=>$config['flow_id'],
			'run_flow_process'=>$config['run_process'],
			'uid'=>$config['wf_singflow'],
			'dateline'=>time()
		];
		$run_sign = (new Process())->mode->AddSing($data);
		if(!$run_sign){
            return  false;
        }
        return $run_sign;
	}
	/**
	 *会签执行
	 *
	 * @param $sing_sign 会签ID
	 * @param $check_con  审核内容
	 **/
	static function EndSing($sing_sign,$check_con)
	{
		return (new Process())->mode->EndSing($sing_sign,$check_con);
	}
	/**
	 *更新会签信息
	 *
	 *@param $run_id 工作流run id
	 **/
	static function up_run_sing($run_id)
	{
		return (new Process())->mode->EditRun($run_id,['is_sing'=>0]);
	}
	/**
	 *更新流程步骤信息
	 *
	 *@param $run_id 工作流ID
	 *@param $run_process 运行步骤
	 **/
	static function up_flow_press($run_id,$run_process)
	{
		return (new Process())->mode->EditRun($run_id,['run_flow_process'=>$run_process]);
	}
	/**
	 *更新流程会签信息
	 *
	 *@param $run_id 工作流ID
	 *@param $sid 会签ID
	 **/
	static function up_flow_sing($run_id,$sid)
	{
		return (new Process())->mode->EditRun($run_id,['is_sing'=>1,'sing_id'=>$sid,'endtime'=>time()]);
	}
	/**
	 *获取sing_id
	 *
	 *@param $run_id 工作流ID
	 **/
	static function get_sing_id($run_id)
	{
		$data = (new Process())->mode->FindRun($run_id);
		return $data['sing_id'];
	}
	/**
	 *获取所有相关的流程步骤
	 *
	 *@param $uid 用户id
	 *@param $role 用户角色id
	 **/
	static function get_userprocess($uid,$role)
	{
		return (new Process())->mode->get_userprocess($uid,$role);
	}
}