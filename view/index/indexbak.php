<?php require $pach . 'public/top.php';?>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li  class="active"><a href="#">列表</a></li>
       
    </ul>
	<div class="cf well form-search" style="height: 68px;">
	    <div class="fl ">
	        <div class="btn-group">
	            <button type="button" post-url="/public/index.php/index/auth/cache.html" class="btn ajax-post btn-success">上传资料</button>
	        </div>
	    </div>
	</div>
	<div class="cf well form-search" style="height: 68px;">
	    <div class="fl ">
	        总行数：
	    </div>
	</div>
	
	{$list}
  
    
    <div class="text-center">
       {$page}
    </div>
</div>
<?php require $pach . 'public/foot.php';?>