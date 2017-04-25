<?php require $pach . 'public/top.php';?>
<style>
    .btn-group{margin-right: 10px;}
</style>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li  class="active"><a href="#">读取文档</a></li>

    </ul>

	<div class="cf well form-search" style="height: 68px;">
        <div class="btn-group fl">
            <button type="button" onclick="history.back(-1);" class="btn ajax-post btn-success" style="background: blue;">返回</button>
        </div>
	    <div class="fl ">
            ╰☆╮ 温馨说明：，此功能仅模拟读取文档，格式可能会有出入。^_^
	    </div>
	</div>


    
    <div class="text-center">
    {$str_xls}
    </div>
</div>
<?php require $pach . 'public/foot.php';?>