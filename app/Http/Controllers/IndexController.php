<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeaderApply;
use App\LeaderApplyday;
class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //查看详细的申请
    public function detailApply(Request $request)
    {
        $id = $request->input('id',0);
        $leader_apply_mod = new LeaderApply();
        $leader_applyday_mod = new LeaderApplyday();

        $apply = $leader_apply_mod
            ->where('id',$id)
            ->first();

        //解码一下聊天信息
        $apply['chat01'] = json_decode($apply['chat01'],1);
        preg_match('/周边,(.*出发)/',$apply['mdd'],$zb);
        preg_match('/国内,(.*)(?:,出境)?/',$apply['mdd'],$gn);
        preg_match('/出境,(.*)/',$apply['mdd'],$cj);
        $apply['zb']   = $zb[1];
        $apply['gn']   = isset($gn[1])?$gn[1]:'';
        $apply['cj']   = isset($cj[1])?$cj[1]:'';

        //查询包含哪些天
        if($apply['status'] != -2){
            $apply['days'] = $leader_applyday_mod
                ->where(['qid' => $id])
                ->field('qid', true)
                ->select();
        }else{
            //保持数据结构一致
            $dayarr = explode(',',$apply['day_history']);
            foreach ($dayarr as $tem) {
                $temp['day'] = $tem;
                $apply['days'][] = $temp;
            }
        }
        unset($apply['day_history']);
        $apply['op_last_cnt'] = json_decode($apply['op_last_cnt'],true);
        $apply['mdd'] = $mdd = C('MDD');    //目的地选择列表
        $data = array(
            'data' => $apply,
            'status' => 1,
            'msg' => 'success!'
        );
        //更新阅读状态
        if ($apply['is_read'] == 0) {
            $leader_apply_mod->where(['id' => $id])->setField('is_read',1);
        }



        return response()->json($apply);
        dd($apply);
        /*$leader_apply_mod = D('LeaderApply');
        $leader_applyday_mod = D('LeaderApplyday');
        $apply = $leader_apply_mod
            ->where(['id' => $id])
            ->field('chat02,beizhu,op_id,op',true)
            ->find();
        //解码一下聊天信息
        $apply['chat01'] = json_decode($apply['chat01'],1);
        preg_match('/周边,(.*出发)/',$apply['mdd'],$zb);
        preg_match('/国内,(.*)(?:,出境)?/',$apply['mdd'],$gn);
        preg_match('/出境,(.*)/',$apply['mdd'],$cj);
        $apply['zb']   = $zb[1];
        $apply['gn']   = $gn[1];
        $apply['cj']   = $cj[1];
        //查询包含哪些天
        if($apply['status'] != -2){
            $apply['days'] = $leader_applyday_mod
                ->where(['qid' => $id])
                ->field('qid', true)
                ->select();
        }else{
            //保持数据结构一致
            $dayarr = explode(',',$apply['day_history']);
            foreach ($dayarr as $tem) {
                $temp['day'] = $tem;
                $apply['days'][] = $temp;
            }
        }
        unset($apply['day_history']);
        $apply['op_last_cnt'] = json_decode($apply['op_last_cnt'],true);
        $apply['mdd'] = $mdd = C('MDD');    //目的地选择列表
        $data = array(
            'data' => $apply,
            'status' => 1,
            'msg' => 'success!'
        );
        //更新阅读状态
        if ($apply['is_read'] == 0) {
            $leader_apply_mod->where(['id' => $id])->setField('is_read',1);
        }
        $this->ajaxReturn($data);*/



    }




}
