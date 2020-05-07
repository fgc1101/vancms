<?php
namespace app\admin\controller;
use think\Db;
use think\facade\Request;
use tool\Form;
use app\admin\model\Tags as Tags;
class EmptyController extends Common{
    protected  $dao,$fields;
    public function initialize()
    {
        parent::initialize();
        $this->moduleid = $this->mod[MODULE_NAME];
        $this->dao = db(MODULE_NAME);
        $fields = cache($this->moduleid.'_Field');
        foreach($fields as $key => $res){
            $res['setup']=string2array($res['setup']);
            $this->fields[$key]=$res;
        }
        unset($fields);
        unset($res);
        $this->assign ('fields',$this->fields);
    }
    public function index(){
        if(Request::isAjax()){
            $request = Request::instance();
            $modelname = strtolower($request->controller());
            $model = db($modelname);
            $keyword=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $order = "sort asc,id desc";
            if (input('post.catid')) {
                $catids = db('category')->where(array('pid'=>input('post.catid')))->column('id');
                if($catids){
                    $catid = input('post.catid').','.implode(',',$catids);
                }else{
                    $catid = input('post.catid');
                }
            }
            $cinfo= db('category')->where(array('id'=>input('post.catid')))->field('catdir,is_show')->find();
            if(!empty($keyword) ){
                $map[]=array('title','like','%'.$keyword.'%');
            }
            $prefix=config('database.prefix');
            $Fields=Db::getConnection()->getFields($prefix.$modelname);
            foreach ($Fields as $k=>$v){
                $field[$k] = $k;
            }
            if(in_array('catid',$field)){
               $map[]=array('catid','in',$catid);
            }
            $list = $model
                ->where($map)
                ->order($order)
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            $rsult['code'] = 0;
            $rsult['msg'] = "获取成功";
            $lists = $list['data'];
            foreach ($lists as $k=>$v ){
                $lists[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
                $lists[$k]['catdir'] =  $cinfo['catdir'];
                $lists[$k]['is_show'] =  $cinfo['is_show'];
                $lists[$k]['url'] = url('home/'.$cinfo['catdir'].'/info',['id'=>$v['id'],'catId'=>$v['catid']]);
            }
            $rsult['data'] = $lists;
            $rsult['count'] = $list['total'];
            $rsult['rel'] = 1;
            return $rsult;
        }else{
            return $this->fetch ('content/index');
        }
    }

    public function delImg(){
        if(!input('post.url')){
            return ['code'=>0,'请指定要删除的图片资源'];
        }
        $file = ROOT_PATH.__PUBLIC__.input('post.url');
        if(file_exists($file) && trim(input('post.url'))!=''){
            is_dir($file) ? dir_delete($file) : unlink($file);
        }
        if(input('post.id')){
            $picurl = input('post.picurl');
            $picurlArr = explode(':',$picurl);
            $pics = substr(implode(":::",$picurlArr),0,-3);
            $model = $this->dao;
            $map['id'] =input('post.id');
            $model->where($map)->update(array('pics'=>$pics));
        }
        $result['msg'] = '删除成功!';
        $result['code'] = 1;
        return $result;
    }
    public function getRegion(){
        $Region=db("region");
        $map['pid'] = input("pid");
        $list=$Region->where($map)->select();
        return $list;
    }
}