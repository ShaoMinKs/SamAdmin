<?php /*a:1:{s:75:"D:\phpstudy\PHPTutorial\WWW\SamAdmin\application\wap\view\public\error.html";i:1575902135;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title><?php if($title): ?><?php echo ($title);else: ?>错误提示<?php endif; ?></title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name='apple-touch-fullscreen' content='yes'>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <script type="text/javascript" src="/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <style>
        .swal2-container.swal2-shown {  background-color: rgba(255, 255, 255, 0.4);  }
    </style>
</head>
<body>
<script>
    document.addEventListener("touchmove",function(e){e.preventDefault();},false);
    var url = "<?php echo htmlentities($url); ?>";
    if(!url || url == 0) url = document.referrer;
    if(!url || url == 0) url = "<?php echo Url('index'); ?>";
    sweetAlert({
        title:"<?php echo htmlentities($title); ?>",
        text:"<?php echo htmlentities($msg); ?>",
        type:"info",
        allowOutsideClick:false
    }).then(function() {
        location.href = url;
    })
</script>
</body>
</html>