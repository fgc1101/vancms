<?php
/**
 * Created by Fanguochao
 * User: fgc
 * Date: 2020-11-06
 * Time: 22:50
 * Desc: 广告模型
 */

namespace app\common\model;

use think\Collection;
use think\facade\Request;
use think\Model;

class Ad extends Model
{
    // 表名称
    protected $name = 'ad';
    // 开启时间戳
    protected $autoWriteTimestamp = true;

    /***
     * desc 根据广告类型获取广告列表
     * @param $type
     * @return array|\PDOStatement|string|Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAdByType($type){
        $where = [];
        if(!empty($type)){
            $where[] = array('location','=',$type);
        }
        $where[] = array('status','=',1);

        $ads = $this->where($where)->order('sort desc')->field('name,img')->select();
        Collection::make($ads)->each(function ($ad) {
            $ad['img'] = Request::domain() . '/uploads/' . $ad['img'];
            return $ad;
        });

        return $ads;
    }

    /**
     * 根据条件获取广告图片
     * @param $map
     * @param $cur_page
     * @param $limits
     * @return array
     */
    public function getListByWhere($map, $cur_page, $limits)
    {
        try {
            $count = $this->where($map)->count();
            $list = $this->where($map)->page($cur_page, $limits)->order('location,sort')->select();
            $json = [
                'code' => 0,
                'msg' => 'success',
                'count' => $count,
                'data' => $list
            ];
            return $json;
        } catch (\Exception $e) {
            return ['code' => 404, 'msg' => $e->getMessage()];
        }
    }
}