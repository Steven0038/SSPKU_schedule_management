<?php
/**
 * Created by PhpStorm.
 * User: yym
 * Date: 2019-06-23
 * Time: 16:07
 */

namespace app\wxcampus\model;
use think\Db;
use think\Model;
class Index extends Model
{
  //获取用户部门和角色信息
    public function getDepartAndPosition($userId){
        $userInformationById = Db::name('user_info')
            ->alias('t1')
            ->join('user_depart t2', 't1.depart_id=t2.id')
            ->join('user_position t3', 't1.position_id=t3.id')
            ->where('t1.id', $userId)
            ->field('t2.name as dname,t3.name as pname')
            ->select();
        return $userInformationById;
    }
}