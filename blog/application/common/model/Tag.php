<?php

namespace app\common\model;

use think\Model;

class Tag extends Model
{
    protected $pk = 'tag_id';
    protected $table = 'blog_tag';
    /*
     * 添加标签
     * */
    public function store($data){
        //验证
        $result = $this->validate(true)->save($data,$data['tag_id']);
        if ($result){
            //说明验证成功
            return ['valid'=>1,'msg'=>'操作成功'];
        }else{
            return ['valid'=>0,'msg'=>$this->getError()];
        }
    }
}
