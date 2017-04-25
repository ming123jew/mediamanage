<?php require $pach . 'public/top.php';?>
<style>
    .btn-group{margin-right: 10px;}
</style>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li  class="active"><a href="#">选择分类</a></li>
    </ul>
	<div class="cf well form-search" style="height: 68px;">
	    <div class="fl ">
	        <div class="btn-group">
	            <button type="button" onclick="location.href='<?php echo url('upload/index');?>'" class="btn ajax-post btn-success" style="background: blue;">上传资料</button>
	        </div>
            <?php foreach ($list_category as $key=>$value){?>
                <div class="btn-group">
                    <button type="button" onclick="location.href='<?php echo url('index/index',['category_id'=>$value['id']]);?>'" class="btn ajax-post btn-success">{$value.name}</button>
                </div>
            <?php }?>
	    </div>
	</div>
    <?php if($category_id==0){?>
	<div class="cf well form-search" style="height: 68px;">
	    <div class="fl ">
            ╰☆╮ 温馨说明：，请点上边栏目进入相关栏目列表。^_^
	    </div>
	</div>
    <?php }else{?>
        {$string_data}
    <?php }?>

    
    <div class="text-center">
        {$page}
    </div>
</div>
<?php require $pach . 'public/foot.php';?>