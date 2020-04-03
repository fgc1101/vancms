<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use tp5er\Backup;

/**
 * Class Data 数据备份
 * @package app\admin\controller
 */
class Data extends Controller
{

    // 数据库操作（备份、还原）操作对象
    private $db = null;

    // 配置
    private $config = [
        //数据库备份路径
        'path' => './backup/',
        //数据库备份卷大小,这里设置为20M（part单位B）
        'part' => 20971520,
        //数据库备份文件是否启用压缩 0不压缩 1 压缩
        'compress' => 0,
        // 数据库备份文件压缩级别 1普通 4 一般  9最高
        'level' => 9
    ];

    public function initialize()
    {
        // 如果备份数据比较大的情况下，需要修改如下参数
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $this->db = new Backup($this->config);
    }

    /**
     * 备份首页
     *
     * @return \think\Response
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $tmp = Db::query('SHOW TABLE STATUS');
            $tables = array_map('array_change_key_case', $tmp);
            //var_dump($tables);die;
            $json = [
                'code' => 0,
                'data' => $tables
            ];
            return json($json);
        }
        return $this->fetch();
    }


    /***
     * 执行备份数据
     */
    public function backup(){
        $ids = input('ids', '');
        try {
            if (strpos($ids, ',') !== false) {
                $idsArray = explode(',', $ids);
                foreach ($idsArray as $table) {
                    $result = $this->db->setFile()->backup($table, 0);
                    if ($result === false) {
                        return json(['code' => 0, 'msg' => '数据表备份异常，请联系系统管理员']);
                    }
                }
                return json(['code' => 1, 'url' => url('data/index')]);
            } else {
                return json(['code' => 0, 'msg' => '数据表备份出错，没有备份数据表']);
            }
        } catch (\Exception $e) {
            return json(['code' => 0, 'msg' => $e->getMessage()]);
        }
    }


    /***
     * 数据库还原列表
     */
    public function filelist(){
        $fileLists = $this->db->fileList();
        $temp = [];
        //提取列数组；
        foreach ($fileLists as $key => $val) {
            $temp[$key] = $val['time'];
        }
        // 此处对数组进行降序排列；SORT_DESC按降序排列
        array_multisort($temp, SORT_DESC, $fileLists);
        return $this->assign('lists', $fileLists)->fetch();
    }

    /**
     * 优化表
     * @return \think\response\Json
     */
    public function optimize()
    {
        $ids = input('ids', '');
        try {
            if (strpos($ids, ',') !== false) {
                $idsArray = explode(',', $ids);
                foreach ($idsArray as $id) {
                    Db::query("OPTIMIZE TABLE `{$id}`");
                }
            } else {
                Db::query("OPTIMIZE TABLE `{$ids}`");
            }
            return json(['code' => 1]);
        } catch (\Exception $e) {
            return json(['code' => 0, 'msg' => '数据表优化出错，请稍后再试']);
        }
    }

    /**
     * 修复表
     * @param null $ids
     * @return \think\response\Json
     */
    public function repair($ids = null)
    {
        $ids = input('ids', '');
        try {
            if (strpos($ids, ',') !== false) {
                $idsArray = explode(',', $ids);
                foreach ($idsArray as $id) {
                    Db::query("REPAIR TABLE `{$id}`");
                }
            } else {
                Db::query("REPAIR TABLE `{$ids}`");
            }
            return json(['code' => 1]);
        } catch (\Exception $e) {
            return json(['code' => 0, 'msg' => '数据表修复出错，请稍后再试']);
        }
    }

    /**
     * 删除指定的备份文件，根据时间删除，也就是文件名
     * @return \think\response\Json
     */
    public function del()
    {
        $file = input('file', '', 'urlsafe_b64decode');
        try {
            $file = strtotime($file);
            $this->db->delFile($file);
            return json(['code' => 1]);
        } catch (\Exception $e) {
            return json(['code' => 0, 'msg' => '备份删除失败：' . $e->getMessage()]);
        }
    }

    /**
     * 还原数据库
     */
    public function rollback()
    {
        $start = 0;
        try {
            $file = input('file', '', 'urlsafe_b64decode');
            // 总共的卷数
            $totalPart = input('part', 1, 'intval');
            for ($i = 1; $i <= $totalPart; $i++) {
                $name_prefix = $this->config['path'] . date('Ymd-His-', strtotime($file));
                $fileArray = [$i, $name_prefix . $i . '.sql', 'name' => $name_prefix];
                $result = $this->db->setFile($fileArray)->import($start);
                if ($result === false) {
                    return json(['code' => 0, 'msg' => '数据表还原异常，请联系系统管理员']);
                }
            }
            // 这里$name_prefix 只是文件名不包含卷和后缀在前缀文件名称
            return json(['code' => 1]);
        } catch (\Exception $e) {
            return json(['code' => 0, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * 下载备份文件
     * @return \think\response\Json
     */
    public function download()
    {
        $file = input('file', '', 'urlsafe_b64decode');
        try {
            $file = strtotime($file);
            $this->db->downloadFile($file);
        } catch (\Exception $e) {
            return json(['code' => 0, 'msg' => $e->getMessage()]);
        }
    }
}
