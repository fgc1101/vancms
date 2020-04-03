<?php /*a:3:{s:75:"/Applications/MAMP/htdocs/vancms/application/admin/view/auth/rule_edit.html";i:1585843137;s:72:"/Applications/MAMP/htdocs/vancms/application/admin/view/common/head.html";i:1585843137;s:72:"/Applications/MAMP/htdocs/vancms/application/admin/view/common/foot.html";i:1585843137;}*/ ?>
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
<div class="admin-main">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑权限</legend>
    </fieldset>
    <blockquote class="layui-elem-quote">
        1、《控/方》：意思是 控制器/方法; 例如 Sys/sysList<br/>
        2、图标名称为左侧导航栏目的图标样式，具体可查看<a href="https://icomoon.io/app/#/select" target="_blank">premium</a>图标
    </blockquote>
    <form class="layui-form layui-form-pane">
        <input type="hidden" name="id" value="<?php echo htmlentities($rule['id']); ?>">
        <div class="layui-form-item">
            <label class="layui-form-label">权限名称</label>
            <div class="layui-input-4">
                <input type="text" name="title" value="<?php echo htmlentities($rule['title']); ?>" lay-verify="required" placeholder="<?php echo lang('pleaseEnter'); ?>权限名称" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">控制器/方法</label>
            <div class="layui-input-4">
                <input type="text" name="href" value="<?php echo htmlentities($rule['href']); ?>" lay-verify="required" placeholder="<?php echo lang('pleaseEnter'); ?>控制器/方法" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">图标名称</label>
            <div class="layui-input-4">
                <input type="text" name="icon" value="<?php echo htmlentities($rule['icon']); ?>" placeholder="<?php echo lang('pleaseEnter'); ?>图标名称" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">菜单状态</label>
            <div class="layui-input-block">
                <input type="radio" name="menustatus" <?php if($rule['menustatus'] == 1): ?>checked<?php endif; ?> value="1" title="开启">
                <input type="radio" name="menustatus" <?php if($rule['menustatus'] == 0): ?>checked<?php endif; ?> value="0" title="关闭">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-2">
                <input type="text" name="sort" value="<?php echo htmlentities($rule['sort']); ?>" placeholder="<?php echo lang('pleaseEnter'); ?>排序编号" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="button" class="layui-btn" lay-submit="" lay-filter="auth">立即提交</button>
                <a href="<?php echo url('adminRule'); ?>" class="layui-btn layui-btn-primary">返回</a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="/static/plugins/layui/layui.js"></script>


<script>
    layui.use(['form', 'layer'], function () {
        var form = layui.form,layer = layui.layer,$ = layui.jquery;
        form.on('submit(auth)', function (data) {
            // 提交到方法 默认为本身
            $.post("<?php echo url('ruleEdit'); ?>",data.field,function(res){
                if(res.code > 0){
                    layer.msg(res.msg,{time:1800,icon:1},function(){
                        location.href = res.url;
                    });
                }else{
                    layer.msg(res.msg,{time:1800,icon:2});
                }
            });
        })
    })
</script>
</body>
</html>