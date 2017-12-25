<?php

namespace app\common\model;

use houdunwang\arr\Arr;
use think\Model;

class Category extends Model
{
    protected $pk = 'cate_id';
    protected $table = 'blog_cate';
    /*
     *获取分类数据  【树状结构】
     * */
    public function getAll(){
        //使用方法参考hdphp手册 搜索数组增强  网页查找tree
        return Arr::tree(db('cate')->order('cate_sort desc,cate_id desc')
            ->select(),'cate_name',$fieldPri = 'cate_id',$fieldPid = 'cate_pid');
    }

    /*
     * 添加
     *@param $data
     *
     * @return array
     * */
    public function store($data){
        //执行验证
        //执行添加
        $result = $this->validate(true)->save($data);
        if (false === $result){
            //验证失败输出错误信息
            //dump($this->getError());
            return ['valid'=>0,'msg'=>$this->getError()];
        }else{
            return ['valid'=>1,'msg'=>'添加成功'];
        }
    }
    /*
     * 处理所属分类
     * */
    public function getCateData($cate_id){
        //halt(db('cate')->select());
        //先找到cate_id子集
        $cate_ids = $this->getSon(db('cate')->select(),$cate_id);
        //把自己追加进去
        $cate_ids[] = $cate_id;
        //halt($cate_ids);
        //dump($cate_ids);
        //找到出他们之外的数据,并变成树状结构
        $field = db('cate')->whereNotIn('cate_id',$cate_ids)->select();
        return Arr::tree($field,'cate_name','cate_id','cate_pid');
        //halt($field);

    }
    /*
     * 找子集
     * */
    public function getSon($data,$cate_id){
        static $temp = [];
        foreach ($data as $k=>$v){
            if ($cate_id == $v['cate_pid']){
                $temp [] = $v['cate_id'];
                $this->getSon($data,$v['cate_id']);
            }
        }
        return $temp;
    }
    /*
     * 编辑栏目
     * */
    public function edit($data){
        $result = $this->validate(true)->save($data,[$this->pk=>$data['cate_id']]);
        if ($result){
            return ['valid'=>1,'msg'=>'编辑成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }
    /*
     * 删除方法
     * */
    public function del($cate_id){
        //获取当前要删除栏目的cate_pid
        $cate_pid = $this->where('cate_id',$cate_id)->value('cate_pid');
        //halt($cate_pid);
        //将当前要删除的$cate_id的子集的pid修改成$cate_pid
        $this->where('cate_pid',$cate_id)->update(['cate_pid'=>$cate_pid]);
        if (Category::destroy($cate_id)){
            return ['valid'=>1,'msg'=>'删除成功'];
        }else{
            return ['valid'=>0,'msg'=>'删除失败'];
        }

    }

}
