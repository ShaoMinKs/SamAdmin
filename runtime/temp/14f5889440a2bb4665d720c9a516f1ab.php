<?php /*a:1:{s:77:"/www/wwwroot/sam_zhuzhouyike_com/application/admin/view/uploadify/upload.html";i:1552892133;}*/ ?>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>文件管理</title>
<link rel="stylesheet" type="text/css" href="/public/plugins/webuploader/webuploader.css">
<link rel="stylesheet" type="text/css" href="/public/plugins/webuploader/css/style.css">
</head>
<body>
<div class="upload-box">
	<ul class="tabs">
		<li class="checked" id="upload_tab">本地上传</li>
		<li id="manage_tab">在线管理</li>
		<li id="search_tab">文件搜索</li>
	</ul>
	<div class="container">
		<div class="area upload-area area-checked" id="upload_area">
			<div id="uploader">
				<div class="statusBar" style="display:none;">
					<div class="progress">
						<span class="text">0%</span>
						<span class="percentage"></span>
					</div><div class="info"></div>
					<div class="btns">
						<div id="filePicker2"></div><div class="uploadBtn">开始上传</div>
						<div class="saveBtn">确定使用</div>
					</div>
				</div>
				<div class="queueList">
					<div id="dndArea" class="placeholder">
						<div id="filePicker"></div>
						<p>或将文件拖到这里，本次最多可选<?php echo htmlentities((isset($info['num']) && ($info['num'] !== '')?$info['num']:1)); ?>个</p>
					</div>
				</div>
			</div>
		</div>
		<div class="area manage-area" id="manage_area">
			<ul class="choose-btns">
				<li class="btn sure checked">确定</li>
				<li class="btn cancel">取消</li>
			</ul>
			<div class="file-list">
				<ul id="file_all_list">
					<!--<li class="checked">
						<div class="img">
							<img src="" />
							<span class="icon"></span>
						</div>
						<div class="desc"></div>
					</li>-->
				</ul>
			</div>
		</div>
		<div class="area search-area" id="search_area">
			<ul class="choose-btns">
				<li class="search">
					<div class="search-condition">
						<input class="key" type="text" />
						<input class="submit" type="button" hidefocus="true" value="搜索" />
					</div>
				</li>
				<li class="btn sure checked">确定</li>
				<li class="btn cancel">取消</li>
			</ul>
			<div class="file-list">
				<ul id="file_search_list">
					<!--<li>
						<div class="img">
							<img src="" />
							<span class="icon"></span>
						</div>
						<div class="desc"></div>
					</li>-->
				</ul>
			</div>
		</div>
		<div class="fileWarp" style="display:none;">
			<fieldset>
				<legend>列表</legend>
				<ul>
				</ul>
			</fieldset>
		</div>
	</div>
</div>
<script type="text/javascript" src="/public/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/public/plugins/webuploader/webuploader.min.js"></script>
<script type="text/javascript" src="/public/plugins/webuploader/upload.js?v=5.1"></script>
<script>
$(function(){
	
	moudle = 'Admin';
	var config = {
			"swf":"/public/plugins/webuploader/Uploader.swf",
			"server":"<?php echo htmlentities($info['upload']); ?>",
			"filelistPah":"<?php echo htmlentities($info['fileList']); ?>",
			"delPath":"<?php echo url('Uploadify/delupload'); ?>",
			"chunked":false,
			"chunkSize":1024000,
			"fileNumLimit":<?php echo htmlentities((isset($info['num']) && ($info['num'] !== '')?$info['num']:1)); ?>,
			"fileSizeLimit":2097152000,
			"fileSingleSizeLimit":20971520,
			"fileVal":"file",
			"auto":true,
			"formData":{},
			"uptype" : "<?php echo htmlentities($info['uptype']); ?>",
			"pick":{"id":"#filePicker","label":"点击选择文件","name":"file"},
			"thumb":{"width":110,"height":110,"quality":70,"allowMagnify":true,"crop":true,"preserveHeaders":false,"type":"image\/jpeg"},
			"compress":false
	};
    var fileType = "<?php echo htmlentities((isset($info['fileType']) && ($info['fileType'] !== '')?$info['fileType']:'Images')); ?>";
	Manager.upload($.extend(config, {type : fileType}));



	/*点击保存按钮时
	 *判断允许上传数，检测是单一文件上传还是组文件上传
	 *如果是单一文件，上传结束后将地址存入$input元素
	 *如果是组文件上传，则创建input样式，添加到$input后面
	 *隐藏父框架，清空列队，移除已上传文件样式*/
	$(".statusBar .saveBtn").click(function(){
		var callback = "<?php echo htmlentities($info['func']); ?>";
		var elementid = "<?php echo htmlentities($info['input']); ?>";
		var num = <?php echo htmlentities((isset($info['num']) && ($info['num'] !== '')?$info['num']:1)); ?>;
		var fileurl_tmp = [];
		if(callback != "undefined"){	
			if(num > 1){	
				 $("input[name^='fileurl_tmp']").each(function(index,dom){
					fileurl_tmp[index] = dom.value;
				 });	
			}else{
				fileurl_tmp = $("input[name^='fileurl_tmp']").val();	
			}
			eval('window.parent.'+callback+'(fileurl_tmp,elementid)');
			window.parent.layer.closeAll();
			return;
		}					 
		if(num > 1){
				var fileurl_tmp = "";
				$("input[name^='fileurl_tmp']").each(function(){
					fileurl_tmp += '<li rel="'+ this.value +'"><input class="input-text" type="text" name="<?php echo htmlentities($info['input']); ?>[]" value="'+ this.value +'" /><a href="javascript:void(0);" onclick="ClearPicArr(\''+ this.value +'\',\'\')">删除</a></li>';	
				});			
				$(window.parent.document).find("#<?php echo htmlentities($info['input']); ?>").append(fileurl_tmp);
		}else{
				$(window.parent.document).find("#<?php echo htmlentities($info['input']); ?>").val($("input[name^='fileurl_tmp']").val());
		}
		window.parent.layer.closeAll();
	});
	
});
</script>
</body>
</html>