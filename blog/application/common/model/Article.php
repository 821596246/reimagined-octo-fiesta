<?php

namespace app\common\model;

use think\Model;

class Article extends Model
{
    protected $pk = 'arc_id';
    protected $table = 'blog_article';
    protected $auto = ['admin_id'];
    protected $insert = ['sendtime'];
    protected $update = ['updatetime'];
    protected function setAdminIdAttr($value){
        return session('admin.admin_id');
    }
    protected function setSendTimeAttr($value){
        return time();
    }
    protected function setUpdateTimeAttr($value){
        return time();
    }
    /*
     * 获取文章首页数据
     * */
    public function getAll($is_recycle){
        return db('article')->alias('a')
            ->join('__CATE__ c','a.cate_id=c.cate_id')
            ->where('a.is_recycle',$is_recycle)
            ->field('a.arc_id,a.arc_title,a.arc_author,a.sendtime,c.cate_name,a.arc_sort')
            ->order('a.arc_sort desc,a.sendtime desc,a.arc_id desc')->paginate(5);
    }
    /*
     * 添加文章
     * */
    public function store($data){
        //验证
        //添加数据库
        $result = $this->validate(true)->allowField(true)->save($data);
        if ($result){
            //文章标签中间表的添加
            foreach ($data['tag'] as $v){
                $arcTagData = [
                    'arc_id'=>$this->arc_id,
                    'tag_id'=>$v,
                ];
                (new ArcTag())->save($arcTagData);
            }
            //执行成功
            return['valid'=>1,'msg'=>'添加成功'];

        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }
    /*
     * 文章编辑
     * */
    public function edit($data){
        $res = $this->validate(true)->allowField(true)->save($data,[$this->pk=>$data['arc_id']]);
        if ($res){
            //执行标签处理,删除原标签后重新添加
            (new ArcTag())->where('arc_id',$data['arc_id'])->delete();
            //执行标签添加
            foreach ($data['tag'] as $v){
                $arcTagData = [
                    'arc_id'=>$this->arc_id,
                    'tag_id'=>$v,
                ];
                (new ArcTag())->save($arcTagData);
            }
            return ['valid'=>1,'msg'=>'编辑成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }
    /*
     * 修改排序
     * */
    public function changeSort($data){
        $result = $this->validate([
            'arc_sort'=>'require|between:1,9999',
        ],[
            'arc_sort.require'=>'请输入排序号',
            'arc_sort.between'=>'请输入1-9999间的排序号',
        ])->save($data,[$this->pk=>$data['arc_id']]);
        if ($result){
            return ['valid'=>1,'msg'=>'操作成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }
    /*
     * 彻底删除
     * */
    public function del($arc_id){
        if (Article::destroy($arc_id)){
            //删除成功
            //删除文章中间表数据
            (new ArcTag())->where('arc_id',$arc_id)->delete();
            return ['valid'=>1,'msg'=>'删除成功'];
        }else{
            return ['valid'=>0,'msg'=>'删除失败'];
        }
    }

}
