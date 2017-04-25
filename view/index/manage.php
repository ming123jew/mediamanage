<?php require $pach . 'public/top.php';?>
<style>
    .btn-group{margin-right: 10px;}
</style>
<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li  class="active"><a href="#">管理文档</a></li>
    </ul>
	<div class="cf well form-search" style="height: 68px;">
	    <div class="fl ">
	        <div class="btn-group">
	            <button type="button" onclick="location.href='<?php echo url('upload/index');?>'" class="btn ajax-post btn-success" style="background: blue;">上传资料</button>
	        </div>
            <?php foreach ($list_category as $key=>$value){?>
                <div class="btn-group">
                    <button <?php if($value['id']==$category_id){?>style="background: #de8748;" <?php }?> type="button" onclick="location.href='<?php echo url('upload/manage',['category_id'=>$value['id']]);?>'" class="btn ajax-post btn-success">{$value.name}</button>
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
    <?php }?>
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <td>ID</td>
            <td>文件名称</td>
            <td>修改日期</td>
            <td>类型</td>
            <td>大小(KB)</td>
            <td>UID</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list_data as $key=>$value){?>
        <tr>
            <td>{$value.id}</td><td>{$value.name}</td><td>{$value.update_time}</td><td>{$value.filetype}</td><td>{:round($value.filesize/1024,2)}</td><td>{$value.uid}</td><td><a href="javascript:openapp('<?php echo url("index/showfile",['path'=>base64_encode($value['path'])]);?>','index_clearcache','查看文档');" >查看</a> | <a class="a-post" href="#" post-url="<?php echo url("delete",['upload_id'=>$value['id'],'path'=>base64_encode($value['path'])]);?>" post-msg="确认要删除？">删除</a></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>


    
    <div class="text-center">
    {$page}
    </div>
</div>

<script>
    function openapp(url) {
        window.location.href = url;
    }
</script>

<?php require $pach . 'public/foot.php';?>