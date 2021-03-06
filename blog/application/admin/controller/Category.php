<?php

namespace app\admin\controller;

use think\Controller;


/*
 * 栏目管理
 *class Gategory
 * @package app\admin\controller
 * */
class Category extends Controller
{
    protected $db;
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->db = new \app\common\model\Category();
    }

    //首页
    public function index(){
        //获取栏目数据
        //$field = db('cate')->select();
        $field = $this->db->getAll();
        //halt($field);
        $this->assign('field',$field);
        return $this->fetch();
    }
    //添加
    public function store(){
        if (request()->isPost()){
            $res = $this->db->store(input('post.'));
            if ($res['valid']){
                //说明操作成功
                $this->success($res['msg'],'index');exit;
            }else{
                $this->error($res['msg']);exit;
            }
        }
        return $this->fetch();
    }

    /*
     * 添加子集
     * */
    public function addSon(){
        if (request()->isPost()){
            $res = $this->db->store(input('post.'));
            if ($res['valid']){
                //说明操作成功
                $this->success($res['msg'],'index');exit;
            }else{
                $this->error($res['msg']);exit;
            }
        }
        $cate_id = input('param.cate_id');
        $data = $this->db->where('cate_id',$cate_id)->find();
        $this->assign('data',$data);
        return $this->fetch();
    }

    /*
     * 编辑栏目
     * */
    public function edit(){
        if (request()->isPost()){
            //halt($_POST);
            $res = $this->db->edit(input('post.'));
            if ($res['valid']){
                $this->success($res['msg'],'index');exit;
            }else{
                $this->error($res['msg']);exit;
            }
        }
        //接收cate_id
        $cate_id = input('param.cate_id');
        //获取旧数据
        $oldData = $this->db->find($cate_id);
        $this->assign('oldData',$oldData);
        //处理所属分类不能包含自己和自己子集的数据
        $cateData = $this->db->getCateData($cate_id);
        //halt($cateData);
        $this->assign('cateData',$cateData);
        return $this->fetch();
    }
    /*
     * 删除栏目
     * */
    public function del(){

        $res = $this->db->del(input('param.cate_id'));
        if ($res['valid']){
            $this->success($res['msg'],'index');exit;
        }else{
            $this->error($res['msg']);exit;
        }
    }
}
