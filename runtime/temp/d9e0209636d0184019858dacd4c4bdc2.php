<?php /*a:3:{s:72:"/Applications/MAMP/htdocs/van/application/admin/view/auth/adminForm.html";i:1585843137;s:69:"/Applications/MAMP/htdocs/van/application/admin/view/common/head.html";i:1585843137;s:69:"/Applications/MAMP/htdocs/van/application/admin/view/common/foot.html";i:1585843137;}*/ ?>
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
        <legend><?php echo htmlentities($title); ?></legend>
    </fieldset>
    <form class="layui-form layui-form-pane" lay-filter="form">
        <div class="layui-form-item">
            <label class="layui-form-label">所属用户组</label>
            <div class="layui-input-4">
                <select name="group_id" lay-verify="required">
                    <option value="">请选择用户组</option>
                    <?php if(is_array($authGroup) || $authGroup instanceof \think\Collection || $authGroup instanceof \think\Paginator): $i = 0; $__LIST__ = $authGroup;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo htmlentities($vo['group_id']); ?>"><?php echo htmlentities($vo['title']); ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php echo lang('username'); ?></label>
            <div class="layui-input-4">
                <input type="text" name="username" lay-verify="required" placeholder="<?php echo lang('pleaseEnter'); ?>登录用户名" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                用户名在4到25个字符之间。
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php echo lang('pwd'); ?></label>
            <div class="layui-input-4">
                <input type="password" name="pwd" placeholder="<?php echo lang('pleaseEnter'); ?>登录密码" <?php if(ACTION_NAME == 'adminadd'): ?>lay-verify="required"<?php endif; ?> class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                密码必须大于6位，小于15位。
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">头像</label>
            <input type="hidden" name="avatar" id="avatar">
            <div class="layui-input-block">
                <div class="layui-upload">
                    <button type="button" class="layui-btn layui-btn-primary" id="adBtn"><i class="icon icon-upload3"></i>点击上传</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img" id="adPic">
                        <p id="demoText"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php echo lang('email'); ?></label>
            <div class="layui-input-4">
                <input type="text" name="email" lay-verify="email" placeholder="<?php echo lang('pleaseEnter'); ?>用户邮箱" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                用于密码找回，请认真填写。
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php echo lang('tel'); ?></label>
            <div class="layui-input-4">
                <input type="text" name="tel" lay-verify="phone" value="" placeholder="<?php echo lang('pleaseEnter'); ?>手机号" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input type="hidden" name="admin_id">
                <button type="button" class="layui-btn" lay-submit="" lay-filter="submit"><?php echo lang('submit'); ?></button>
                <a href="<?php echo url('adminList'); ?>" class="layui-btn layui-btn-primary"><?php echo lang('back'); ?></a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="/static/plugins/layui/layui.js"></script>


<script>
    layui.use(['form', 'layer','upload'], function () {
        var form = layui.form, layer = layui.layer,$= layui.jquery,upload = layui.upload;
        var info = <?php echo $info; ?>;
        form.val("form", info);
        if(info){
            $('#adPic').attr('src',info.avatar);
        }
        form.render();
        form.on('submit(submit)', function (data) {
            loading =layer.load(1, {shade: [0.1,'#fff']});
            $.post("", data.field, function (res) {
                layer.close(loading);
                if (res.code > 0) {
                    layer.msg(res.msg, {time: 1800, icon: 1}, function () {
                        location.href = res.url;
                    });
                } else {
                    layer.msg(res.msg, {time: 1800, icon: 2});
                }
            });
        });
        //普通图片上传
        var uploadInst = upload.render({
            elem: '#adBtn'
            ,url: '<?php echo url("UpFiles/upload"); ?>'
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#adPic').attr('src', result); //图片链接（base64）
                });
            },
            done: function(res){
                if(res.code>0){
                    $('#avatar').val(res.url);
                }else{
                    //如果上传失败
                    return layer.msg('上传失败');
                }
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-mini demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
        });
    });
</script>