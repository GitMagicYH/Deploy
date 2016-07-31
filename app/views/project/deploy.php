<?= View::get('layout.header') ?>

<div class="col-md-12">
	<!-- BEGIN SAMPLE TABLE PORTLET-->
	<div class="portlet box green">
	    <div class="portlet-title">
	        <div class="caption">
	            <i class="fa fa-comments"></i>Deploy</div>
	        <div class="tools">
	            <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
	            <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
	            <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
	            <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
	        </div>
	    </div>
	    <div class="portlet-body">
	        <div class="table-scrollable">
	            <table class="table table-striped table-hover">
	                <thead>
	                    <tr>
	                        <th> Project Name </th>
	                        <th> Source </th>
	                        <th> Target Path </th>
	                        <th> Create time </th>
	                        <th> Update time </th>
	                        <th> Operate </th>
	                    </tr>
	                </thead>
	                <tbody>
	                	<?php foreach($list as $record): ?>
	                    <tr>
	                        <td> <?= $record->name; ?> </td>
	                        <td> <?= $record->source_url; ?> </td>
	                        <td> <?= $record->target_path; ?> </td>
	                        <td> <?= $record->create_time; ?> </td>
	                        <td> <?= $record->update_time; ?> </td>
	                        <td>
	                        	<a href="javascript:;" data-id="<?= $record->id; ?>" class="deploy btn btn-outline btn-circle green btn-sm purple">
	                        		<i class="fa fa-edit"></i> Deploy </a>
	                        	<a href="javascript:;" data-id="<?= $record->id; ?>" class="rollback btn btn-outline btn-circle green btn-sm purple">
	                        		<i class="fa fa-edit"></i> Rollback </a>
	                        </td>
	                    </tr>
	                    <?php endforeach; ?>
	                </tbody>
	            </table>
	        </div>
	    </div>
	</div>
	<!-- END SAMPLE TABLE PORTLET-->
</div>

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
<!-- <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script> -->
<!-- <script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script> -->
<!-- <link href="/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"> -->
<!-- <link href="/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<script src="/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script> -->
<!-- END PAGE LEVEL PLUGINS -->

<script type="text/javascript">
	$(document).ready(function() {
		$(".deploy").on("click", function() {
			var _this = $(this);
			var url = "<?= URL::route('Project\ProjectController@deployAction') ?>";
			var param = {
				"id": _this.data("id"),
			};
			_ajax_submit(url, param, function() {
				bootbox.alert("Deploy project success!");
			});
		});

		$(".rollback").on("click", function() {
			var _this = $(this);
			var url = "<?= URL::route('Project\ProjectController@rollbackAction') ?>";
			var param = {
				"id": _this.data("id"),
			};
			_ajax_submit(url, param, function() {
				bootbox.alert("Rollback project success!");
			});
		});
	});
	<?= View::get('layout.js.commonuse') ?>
</script>
<?= View::get('layout.footer') ?>