<?php /*a:3:{s:74:"/Applications/MAMP/htdocs/vancms/application/admin/view/data/filelist.html";i:1585919989;s:72:"/Applications/MAMP/htdocs/vancms/application/admin/view/common/head.html";i:1585843137;s:72:"/Applications/MAMP/htdocs/vancms/application/admin/view/common/foot.html";i:1585917775;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo config('sys_name'); ?>后台管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="/static/plugins/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="/static/admin/css/global.css" media="all">
    <link rel="stylesheet" href="/static/common/css/font.css" media="all">
</head>
<body class="skin-<?php if(!empty($_COOKIE['skin'])){echo $_COOKIE['skin'];}else{echo '0';setcookie('skin','0');}?>">
<div class="admin-main layui-anim layui-anim-upbit">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>数据库备份列表</legend>
    </fieldset>
    <table class="layui-table">
        <colgroup>
            <col width="200">
            <col width="100">
            <col width="100">
            <col width="200">
            <col width="180">
        </colgroup>
        <thead>
        <tr>
            <th style="text-align:center">备份名称</th>
            <th style="text-align:center">卷数</th>
            <th style="text-align:center">数据大小</th>
            <th style="text-align:center">备份时间</th>
            <th style="text-align:center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(is_array($lists) || $lists instanceof \think\Collection || $lists instanceof \think\Paginator): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "暂时没有备份数据表数据" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
        <tr style="text-align:center">
            <td><?php echo htmlentities($key); ?></td>
            <td><?php echo htmlentities($v['part']); ?></td>
            <td><?php echo htmlentities(format_bytes($v['size'])); ?></td>
            <td><?php echo htmlentities(date('Y-m-d H:i:s',!is_numeric($v['time'])? strtotime($v['time']) : $v['time'])); ?></td>
            <td>
                <a href="<?php echo url('data/rollback',['file'=>urlsafe_b64encode($key),'part'=>$v['part']]); ?>" class="layui-btn layui-btn-xs rollback"><i class="fa fa-reply-all"></i> 还原</a>&nbsp;&nbsp;
                <a href="<?php echo url('data/download',['file'=>urlsafe_b64encode($key)]); ?>" class="layui-btn layui-btn-xs layui-btn-warm download"><i class="fa fa-reply-all"></i> 下载</a>&nbsp;&nbsp;
                <a href="<?php echo url('data/del',['file'=>urlsafe_b64encode($key)]); ?>" class="layui-btn layui-btn-danger layui-btn-xs del"><i class="fa fa-trash-o"></i> 删除</a>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "暂时没有备份数据表数据" ;endif; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript" src="/static/plugins/layui/layui.js"></script>
<script type="text/javascript" src="/static/common/js/jquery.2.1.1.min.js"></script>


<script>
    layui.use(['layer','jquery'], function() {
        var layer = layui.layer, $ = layui.jquery;

        $(function () {
            // 数据库还原
            $('.rollback').on('click', function () {
                var _this = $(this), url = _this.attr('href');
                layer.confirm('您确定要恢复到此数据版本吗？请慎重操作', {title: '警告', icon: 0, btn: ['确定', '取消']}, function () {
                    $.post(url + '?t=' + new Date().getTime(), function (data) {
                        if (data.code == 1) {
                            layer.msg('数据表恢复成功', {icon: 1, offset: 't'});
                        } else {
                            layer.msg(data.msg, {offset: 't', icon: 2});
                        }
                    }, 'json');
                });
                return false;
            });
            // 数据库备份删除
            $('.del').on('click', function () {
                var _this = $(this), url = _this.attr('href');
                layer.confirm('您确定要删除此数据库备份吗？', {title: '删除警告', icon: 0, btn: ['确定', '取消']}, function () {
                    $.post(url + '?t=' + new Date().getTime(), function (data) {
                        if (data.code == 1) {
                            layer.msg('数据库备份删除成功', {icon: 1, time: 1000, offset: 't'}, function (index) {
                                window.location.reload(true);
                            });
                        } else {
                            layer.msg(data.msg, {offset: 't', icon: 2});
                        }
                    }, 'json');
                });
                return false;
            });
            // 数据库备份下载
            $('.download').on('click', function () {
                var _this = $(this), url = _this.attr('href');
                $.ajax({
                    type: 'POST',
                    async: false,
                    url: url + '?t=' + new Date().getTime()
                });
                return false;
            });
        });
    });
</script>
</body>
</html>