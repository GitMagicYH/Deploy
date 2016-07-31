<?= View::get('layout.header') ?>

<div class="col-md-12">
	<!-- BEGIN SAMPLE TABLE PORTLET-->
	<div class="portlet box green">
	    <div class="portlet-title">
	        <div class="caption">
	            <i class="fa fa-comments"></i> Projects 
	        </div>
	        <div class="actions">
                <div class="btn-group btn-group-devided" data-toggle="buttons">
                    <a href="javascript:;" id="new" class="btn btn-outline btn-circle btn-sm purple"> New Project </a>&nbsp;&nbsp;
                </div>
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
	                        	<a href="javascript:;" class="edit btn btn-outline btn-circle green btn-sm purple" data-id="<?= $record->id; ?>" data-json='<?= $record->json; ?>'>
	                        		<i class="fa fa-edit"></i> Edit </a>
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


<div id="dialog" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Modal title</h4>
			</div>
			<div class="modal-body">
				<div class="col-md-12"> 
					<form class="form-horizontal">
						<input id="id" style="display: none" type="text" value="">
						<div class="form-group"> 
							<label class="col-md-3 control-label" for="name" aria-required="true">Name</label> 
							<div class="col-md-7"> 
								<input id="name" name="name" type="text" placeholder="Project Name" class="form-control input-md" value=""> 
							</div> 
						</div> 
						<div class="form-group"> 
							<label class="col-md-3 control-label" for="type">Source Type</label> 
							<div class="col-md-7"> 
								<select id="type" name="type" class="form-control"> 
									<option value="0">SVN</option>
									<option value="1">Git</option>
								</select>
							</div>
						</div>
						<div class="form-group"> 
							<label class="col-md-3 control-label" for="source_url">Source Url</label> 
							<div class="col-md-7"> 
								<input id="source_url" name="source_url" type="text" placeholder="Srouce Url" class="form-control input-md" value=""> 
							</div>
						</div>
						<div class="form-group"> 
							<label class="col-md-3 control-label" for="target_path">Target Path</label> 
							<div class="col-md-7"> 
								<input id="target_path" name="target_path" type="text" placeholder="Target Path" class="form-control input-md" value=""> 
							</div>
						</div>
						<div class="form-group"> 
							<label class="col-md-3 control-label" for="host_list">Host List</label> 
							<div class="col-md-7"> 
								<input id="host_list" name="host_list" type="text" placeholder="Host List" class="form-control input-md" value=""> 
							</div>
						</div>
						<div class="form-group"> 
							<label class="col-md-3 control-label" for="user_name">User Name</label> 
							<div class="col-md-7"> 
								<input id="user_name" name="user_name" type="text" placeholder="User Name" class="form-control input-md" value=""> 
							</div>
						</div>
						<div class="form-group"> 
							<label class="col-md-3 control-label" for="pswd">Password</label> 
							<div class="col-md-7"> 
								<input id="pswd" name="pswd" type="password" placeholder="Password" class="form-control input-md" value=""> 
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<div class="form-group">
					<div class="col-md-4 col-md-offset-3">
						<button id="submit" type="button" class="btn btn-primary">Submit</button>
					</div>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
<!-- <script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script> -->
<!-- <script src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script> -->
<!-- <link href="/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"> -->
<link href="/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css">
<script src="/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script type="text/javascript">
	// bind event
	$(document).ready(function() {
		// edit click
		$(".edit").on("click", function() {
			var _this = $(this);
			var id = _this.data("id");
			var param = _this.data("json");
			buildBoxContent(param);
			$('#dialog').modal({
				keyboard: true,
			});
		});

		// new project
		$("#new").on("click", function() {
			var param = {
				"id": "",
				"name": "",
				"type": 0,
				"source_url": "",
				"target_path": "",
				"host_list": "",
				"user_name": "",
				"pswd": "",
			};
			buildBoxContent(param);
			$('#dialog').modal({
				keyboard: true,
			});
		});

		// submit change
		$("#submit").on("click", function() {
			var url = "<?= URL::route('Project\ProjectController@editAction') ?>";
			var param = getParam();
			_ajax_submit(url, param, function() {
				$('#dialog').modal("hide");
				bootbox.alert("Save project success!");
				window.location.reload();
			});
		});
	});

	var getParam = function() {
		var id = $("#id").val();
		var name = $("#name").val();
		var param = {
			"id": $.trim($("#id").val()),
			"name": $.trim($("#name").val()),
			"type": $.trim($("#type").val()),
			"source_url": $.trim($("#source_url").val()),
			"target_path": $.trim($("#target_path").val()),
			"host_list": $.trim($("#host_list").val()),
			"user_name": $.trim($("#user_name").val()),
			"pswd": $.trim($("#pswd").val()),
		}
		return param;
	}

	var buildBoxContent = function(param) {
		$("#id").val(param["id"]);
		$("#name").val(param["name"]);
		$("#type").val(param["type"]);
		$("#source_url").val(param["source_url"]);
		$("#target_path").val(param["target_path"]);
		$("#host_list").val(param["host_list"]);
		$("#user_name").val(param["user_name"]);
		$("#pswd").val(param["pswd"]);
		var content = $("#dialog").html();
		return content;
	}
	
	<?= View::get('layout.js.commonuse') ?>
</script>
<?= View::get('layout.footer') ?>