
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li  class="active"><a href="{:url('admin/index')}">用户管理</a></li>
        <li><a href="{:url('admin/add')}">增加用户</a></li>
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
	        总行数：{$num_row} 
	    </div>
	</div>

    {$list}
    
    <div class="text-center">
       
    </div>
</div>
