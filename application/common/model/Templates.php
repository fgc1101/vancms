<?php
/* @project : VanCms
 * @auther  : simplevan
 * @date    : 2020年11月06日22:20:29
 * @desc    : 模板模型
 */
namespace app\common\model;


class Templates
{
    // 表的名称
    protected $name = 'ad';
    // 关闭自动时间戳
    protected $autoWriteTimestamp = false;


    /***
     * desc 根据条件分页获取列表
     * @param $map
     * @param $cur_page
     * @param $limits
     * @return array
     */
    public function getListByWhere($map, $cur_page, $limits){
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