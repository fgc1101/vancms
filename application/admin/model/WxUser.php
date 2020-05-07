<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class WxUser extends Model
{
    protected $pk = 'id';

    /**
     * 根据条件获取微信用户的列表和总数
     * @param $map
     * @param $cur_page
     * @param $limits
     * @return array
     */
    public function getDataByWhere($map, $cur_page, $limits)
    {
        try {
            $count = $this->where($map)->count();
            $list = $this->where($map)->page($cur_page, $limits)->order('id desc')->field(true)->select();
            $json = [
                'code' => 0,
                'msg' => '',
                'count' => $count,
                'data' => $list
            ];
            return $json;
        } catch (\Exception $e) {
            return ['code' => 404, 'msg' => '获取微信用户列表失败：' . $e->getMessage()];
        }
    }
}

