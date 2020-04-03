<?php /*a:3:{s:71:"/Applications/MAMP/htdocs/vancms/application/admin/view/data/index.html";i:1585919391;s:72:"/Applications/MAMP/htdocs/vancms/application/admin/view/common/head.html";i:1585843137;s:72:"/Applications/MAMP/htdocs/vancms/application/admin/view/common/foot.html";i:1585917775;}*/ ?>
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
        <legend>数据库备份</legend>
    </fieldset>
    <fieldset class="layui-elem-field" style="border:0">
        <div class="layui-form-item" style="float: left;">
            <button class="layui-btn layui-btn-warm backup"><i class="fa fa-copy"></i> 立即备份</button>
            <button class="layui-btn optimizeBatch"><i class="fa fa-gavel"></i> 优化表</button>
            <button class="layui-btn layui-btn-danger repaireBatch"><i class="fa fa-gear"></i> 修复表</button>
        </div>
    </fieldset>
    <table class="layui-table" id="datatable" lay-filter="datatable"></table>
</div>

<script type="text/javascript" src="/static/plugins/layui/layui.js"></script>
<script type="text/javascript" src="/static/common/js/jquery.2.1.1.min.js"></script>


<script type="text/html" id="barDemo">
    <
</script>
<script type="text/html" id="toobar">
    <a class="layui-btn layui-btn-xs" lay-event="optimize"><i class="fa fa-gavel"></i> 优化表</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="repair"><i class="fa fa-gear"></i> 修复表</a>
</script>
<script>

    // 优化表函数
    function ajax_optimize(ids) {
        $.post("<?php echo url('data/optimize'); ?>" + '?t=' + new Date().getTime(), {ids: ids}, function (data) {
            if (data.code == 1) {
                layer.msg('数据表优化成功', {icon: 1, time: 1000, offset: 't'});
            } else {
                layer.msg(data.msg, {offset: 't', icon: 2});
            }
        }, 'json');
    }

    // 修复表函数
    function ajax_repair(ids) {
        $.post("<?php echo url('data/repair'); ?>" + '?t=' + new Date().getTime(), {ids: ids}, function (data) {
            if (data.code == 1) {
                layer.msg('数据表修复成功', {icon: 1, time: 1000, offset: 't'});
            } else {
                layer.msg(data.msg, {offset: 't', icon: 2});
            }
        }, 'json');
    }

    layui.config({
        base: '/static/admin/js/'
    }).use(['table','form','utils'], function() {
        var table = layui.table,form = layui.form,$ = layui.jquery,utils = layui.utils;
        var datatable = table.render({
            elem: '#datatable',
            url: '<?php echo url("index"); ?>',
            page: false,
            method:'post',
            cols: [[
                {checkbox: true},
                {field: 'name', title: '表名', align: 'center', width: 200},
                {
                    field: 'rows', title: '数据量', align: 'center', width: 180,
                    templet: '<div>【{{ d.rows}}】条记录</div>'
                },
                {
                    field: 'data_length', title: '数据大小', align: 'center', width: 150,
                    templet: '<div>{{ layui.utils.formatFileSize(d.data_length)}}</div>'
                },
                {
                    field: 'add_time', title: '创建时间', align: 'center', width: 180,
                    templet: '<div>{{ layui.utils.toDateString(d.add_time*1000) }}</div>'
                },
                {
                    title: '备份状态', align: 'center', width: 120,
                    templet: '<div>等待备份...</div>'
                },
                {title: '操作', align: 'center', width: 200, toolbar: '#toobar'}
            ]]
        });

        table.on('tool(datatable)', function (obj) {
            console.log(obj);
            var row = obj.data; //获得当前行数据
            var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
            if (layEvent === 'optimize') {
                ajax_optimize(row.name);
            } else if (layEvent === 'repair') {
                ajax_repair(row.name);
            }
        });

        // 获取批量选中的行，包含表名name
        function getSelectedRow() {
            var checkStatus = table.checkStatus('datatable');
            var checkgroup = [];
            if (checkStatus.data.length < 1) {
                layer.msg("至少选择一行", {icon: 5, anim: 6, offset: 't'});
                return false;
            }
            checkStatus.data.forEach(function (v) {
                checkgroup.push(v.name);
            });
            return checkgroup.join(',');
        }

        $('.backup').on('click', function () {
            var ids = getSelectedRow();
            console.log(ids);
            if (ids !== false) {
                $.ajax({
                    url: "<?php echo url('data/backup'); ?>?t=" + new Date().getTime(),
                    type: 'post',
                    data: 'ids=' + ids,
                    dataType: 'json',
                    beforeSend: function () {
                        loading = layer.msg('数据库备份中，请稍后……', {
                            icon: 16, shade: 0.3, shadeClose: false, time: 0
                        });
                    },
                    success: function (data) {
                        layer.close(loading);
                        if (data.code == 1) {
                            layer.msg('数据表备份成功，马上跳转到备份列表页……', {icon: 1, time: 2000, offset: 't'}, function () {
                                window.location.href = data.url;
                            })
                        } else {
                            layer.msg(data.msg, {offset: 't', icon: 2});
                        }
                    }
                })
            }
        });

    });

</script>
</body>
</html>