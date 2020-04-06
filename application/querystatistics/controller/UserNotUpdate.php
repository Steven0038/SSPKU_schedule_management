<?php
namespace app\querystatistics\controller;

use app\common\controller\Common;
use think\Controller;
use think\Request;
use think\Db; 

/**
*	@	Purpose:
*	 统计用户日程信息的类
*	@Author:	邓凌鹏
*	@Date:	2019/4/24
*	@Time:	19:00
*/

class UserNotUpdate extends Common
{
		
  		/**
		*	@Purpose:
		*	 查询未更新日程的用户
		*	@Method	Name:	index()
		*
		*	@Author:	邓凌鹏
		*
		*	@Return:	查询返回值（结果集对象）
		*/
  
  /*
  	public function index()
    {
				$today = date("Y-m-d");
      			$next_week = date("Y-m-d",time()+3600*24*7);
      			$sql = "SELECT * 
				FROM user_info
				WHERE user_info.id NOT IN(
					SELECT schedule_info.user_id
    				FROM schedule_info
    				WHERE(schedule_info.date>\"$today\" AND schedule_info.date< \"$next_week\")   
				)
                ORDER BY id";
        
        		$list = Db::query($sql);
      
      			$today = date("Ymd");
      			$next_week = date("Ymd",time()+3600*24*7);
      			$sql="SELECT * FROM workday WHERE workday.is_work_day=0 AND workday.ymd>\"$today\" AND workday.ymd<\"$next_week\" ";
      			$not_work_day_num=count(Db::query($sql));
      			if($not_work_day_num==7) $list=array();
        		$this->assign('arealist',$list);
       			$user_type=array("0"=>"普通用户","1"=>"院领导","2"=>"部门领导","3"=>"系领导");
       			$this->assign('user_type',$user_type);
        		return $this->fetch();
    }
 */
   public function index()
    {
				$today = date("Y-m-d");
      			$next_week = date("Y-m-d",time()+3600*24*7);
    			$week=date('w');
    			$week++;
      /*
       			$sql = "SELECT *,MAX(date)AS last_date 
				FROM user_info LEFT JOIN schedule_info 
				ON user_info.id=schedule_info.user_id 
				GROUP BY(user_info.id) 
				HAVING (last_date IS NULL OR last_date<\"$today\")
                ORDER BY last_date DESC";
       */
    			$list=array();
    			$time_id=array(1,150);
      			for($i=1;$i<=7;$i++){
                 	$day= date("Y-m-d",time()+3600*24*$i);
                    $week=date('w',time()+3600*24*$i);
                  	$week=$week==0?7:$week;
                  	$_day=date("Ymd",time()+3600*24*$i);
                  	$sql="SELECT * FROM workday WHERE workday.is_work_day=0 AND workday.ymd=\"$_day\"";
                  	if(count(Db::query($sql))!=0) continue;
                  	for($j=0;$j<2;$j++){
                    	$sql = "SELECT * 
								FROM user_info
								WHERE user_info.id NOT IN(
								SELECT schedule_info.user_id
    							FROM schedule_info
    							WHERE(schedule_info.date=\"$day\" AND schedule_info.time_id=$time_id[$j] AND schedule_info.is_delete=0)   
								UNION
                                SELECT schedule_default.user_id FROM schedule_default
								WHERE schedule_default.day=$week
								AND schedule_default.is_delete=0
                                AND schedule_default.time_id=$time_id[$j]
                                ) AND user_info.is_delete=0
                				ORDER BY id";
                      	$list=$list+Db::query($sql);
                    }
                }
    			$list=array_unique($list,SORT_REGULAR);
        		$this->assign('arealist',$list);
       			$user_type=array("0"=>"普通用户","1"=>"院领导","2"=>"部门领导","3"=>"系领导");
       			$this->assign('user_type',$user_type);
        		return $this->fetch();
    }
}