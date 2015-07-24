<!DOCTYPE html>
<html lang="en"><head>
	<meta charset="utf-8">
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Neon Admin Panel">
	<meta name="author" content="">
	
	<title>xenixstudio 서비스 플랫폼 - 서비스 디바이스 작성</title>
	
	<link rel="icon" href="/assets/images/logo@2x_favicon2.png" type="image/x-icon" />
	<link rel="shortcut icon" href="/assets/images/logo@2x_favicon2.png" type="image/x-icon" />

	<style>
		html, body { height: 100%; }
		.file-input-wrapper { overflow: hidden; position: relative; cursor: pointer; z-index: 1; }
		.file-input-wrapper input[type=file], .file-input-wrapper input[type=file]:focus, .file-input-wrapper input[type=file]:hover { position: absolute; top: 0; left: 0; cursor: pointer; opacity: 0; filter: alpha(opacity=0); z-index: 99; outline: 0; }
		.file-input-name { margin-left: 8px; }
	</style>
	<link rel="stylesheet" href="/assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="/assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="/assets/css/neon.css">
	<link rel="stylesheet" href="/assets/css/custom.css">

	<script src="/assets/js/jquery-1.10.2.min.js"></script>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	
	<script>
		$(document).ready(function() {
			$('#main-menu > li:nth-child(1)').addClass('opened');
			$('#main-menu > li > ul > li:nth-child(2)').addClass('active');
			
			
			$('#device').change(function(){
				var m_device = $('#device').val();
				console.log(m_device);
				
				if(m_device == 'ANDROID')
				{
					$('.android').css('display', 'block');
					$('.iphone').css('display', 'none');
				}
				else
				{
					$('.android').css('display', 'none');
					$('.iphone').css('display', 'block');
				}
			});
			
			
			
			$('#write_btn').click(function(){
			
				var m_service_id = $('#service_id').val();
				var m_device = $('#device').val();
				var m_version_name = $('#version_name').val();
				var m_version_code = $('#version_code').val();
				
				if(m_device == 'ANDROID')
				{
					var m_gcm_service_key = $('#gcm_service_key').val();
					var m_package_name = $('#gcm_package_name').val();
					var m_queue_name = $('#gcm_queue_name').val();
					var m_worker_count = $('#gcm_worker_count').val();
					var m_feedback_api1 = $('#gcm_feedback_api1').val();
					var m_feedback_api2 = $('#gcm_feedback_api2').val();
					
					if(m_gcm_service_key == '')
					{
						alert('GCM 서비스 키를 입력해주세요.');
						$('#gcm_service_key').focus();
					}
					else if(m_package_name == '')
					{
						alert('GCM Package 이름을 입력해주세요.');
						$('#gcm_package_name').focus();
					}
					else if(m_queue_name == '')
					{
						alert('GCM Queue 이름을 입력해주세요.');
						$('#gcm_queue_name').focus();
					}
					else if(m_worker_count == '')
					{
						alert('GCM Worker 수를 입력해주세요.');
						$('#gcm_worker_count').focus();
					}
					else
					{	
						$.ajax({ 
							type: 'POST',
							url: "/service_device/write",
							dataType: "json",
							data: 'service_id='+m_service_id+'&device='+m_device+'&version_name='+m_version_name+'&version_code='+m_version_code+'&gcm_service_key='+m_gcm_service_key+'&gcm_package_name='+m_package_name+'&gcm_queue_name='+m_queue_name+'&gcm_worker_count='+m_worker_count+'&gcm_feedback_api1='+m_feedback_api1+'&gcm_feedback_api2='+m_feedback_api2,
							error:function(data){
								alert('등록 실패했습니다.\nstatusCode : ' + data.readyState + '\nresponseText : ' + data.responseText);
								console.log(data.responseText);
							},
							success:function(result){
								if(result.result == 1)
								{
									alert('등록되었습니다.');
									window.location.href = '/service_device?service_id='+m_service_id;
								}
								else if(result.result == 103)
								{
									alert('이미 등록된 디바이스입니다.');
								}
								else
								{
									alert('등록 실패했습니다. 개발자에게 문의바랍니다.');
								}							
							}
						});
					}	
				}
				else // IPHONE
				{
					var m_cert = $('#cert').val();
					var m_key = $('#key').val();
					var m_is_production = $('#is_production').val();
					var m_queue_name = $('#apns_queue_name').val();
					var m_worker_count = $('#apns_worker_count').val();
					var m_feedback_api1 = $('#apns_feedback_api1').val();
					var m_feedback_api2 = $('#apns_feedback_api2').val();
					
					if(m_cert == '')
					{
						alert('APNS CERT를 입력해주세요.');
						$('#cert').focus();
					}
					else if(m_key == '')
					{
						alert('APNS KEY를 입력해주세요.');
						$('#key').focus();
					}
					else if(m_queue_name == '')
					{
						alert('APNS Queue 이름을 입력해주세요.');
						$('#apns_queue_name').focus();
					}
					else if(m_worker_count == '')
					{
						alert('APNS Worker 수를 입력해주세요.');
						$('#apns_worker_count').focus();
					}
					else
					{	
						$.ajax({ 
							type: 'POST',
							url: "/service_device/write",
							dataType: "json",
							data: 'service_id='+m_service_id+'&device='+m_device+'&version_name='+m_version_name+'&version_code='+m_version_code+'&cert='+m_cert+'&key='+m_key+'&is_production='+m_is_production+'&apns_queue_name='+m_queue_name+'&apns_worker_count='+m_worker_count+'&apns_feedback_api1='+m_feedback_api1+'&apns_feedback_api2='+m_feedback_api2,
							error:function(data){
								alert('등록 실패했습니다.\nstatusCode : ' + data.readyState + '\nresponseText : ' + data.responseText);
								console.log(data.responseText);
							},
							success:function(result){
								if(result.result == 1)
								{
									alert('등록되었습니다.');
									window.location.href = '/service_device?service_id='+m_service_id;
								}
								else if(result.result == 103)
								{
									alert('이미 등록된 디바이스입니다.');
								}
								else
								{
									alert('등록 실패했습니다. 개발자에게 문의바랍니다.');
								}							
							}
						});
					}
				}			
			});
		});	
		
	</script>
	
</head>
<body class="page-body" data-url="http://neon.dev">

<div class="page-container" style="min-height:100%;"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<?php include('include/side_bar.php'); ?>
	<div class="main-content" style="min-height: 100%;">
		
		<?php include('include/header.php'); ?>

		<hr/>
		<ol class="breadcrumb bc-3">
			<li>
				<a href="/member"><i class="entypo-home"></i>홈</a>
			</li>
			<li class="active">
				<strong>서비스 디바이스</strong>
			</li>
		</ol>
		
		<h2><?php echo $service_name; ?> 디바이스 작성</h2>
		<br/>



		<div class="row">
			<div class="col-md-12">
			
				<div class="panel panel-primary" data-collapsed="0">
		
					<div class="panel-body">
						
						<form role="form" class="form-horizontal form-groups-bordered" enctype="multipart/form-data">
						
							<input type="hidden" id="service_id" value="<?php echo $service_id; ?>" />
						
							<div class="form-group">
								<label class="col-sm-3 control-label">서비스</label>
								
								<div class="col-sm-3">
									<select class="form-control" id="service">
										<option value="<?php echo $service_id; ?>"><?php echo $service_name; ?></option>	
									</select>
								</div>
								
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">디바이스</label>
								
								<div class="col-sm-2">
									<select class="form-control" id="device">
										<option value="ANDROID">ANDROID</option>	
										<option value="IPHONE">IPHONE</option>	
									</select>
								</div>
							</div>


							<div class="form-group">
								<label class="col-sm-3 control-label">버전</label>
								
								<div class="col-sm-3">
									<input type="text" class="form-control" id="version_name">
								</div>								
							</div>														

							<div class="form-group">
								<label class="col-sm-3 control-label">버전 코드</label>
								
								<div class="col-sm-3">
									<input type="text" class="form-control" id="version_code">
								</div>								
							</div>
							
							<div class="form-group android">
								<label class="col-sm-3 control-label">GCM 서비스 KEY</label>
								
								<div class="col-sm-5">
									<input type="text" class="form-control" id="gcm_service_key">
								</div>								
							</div>
							
							<div class="form-group android">
								<label class="col-sm-3 control-label">GCM 패키지</label>
								
								<div class="col-sm-5">
									<input type="text" class="form-control" id="gcm_package_name">
								</div>								
							</div>
							
							<div class="form-group android">
								<label class="col-sm-3 control-label">GCM WORKER 수</label>
								
								<div class="col-sm-1">
									<input type="text" class="form-control" id="gcm_worker_count">
								</div>								
							</div>
							
							<div class="form-group android">
								<label class="col-sm-3 control-label">GCM QUEUE 이름</label>
								
								<div class="col-sm-3">
									<input type="text" class="form-control" id="gcm_queue_name">
								</div>								
							</div>
							
							<div class="form-group android">
								<label class="col-sm-3 control-label">GCM FEEDBACK API1 URL</label>
								
								<div class="col-sm-7">
									<input type="text" class="form-control" id="gcm_feedback_api1">
								</div>								
							</div>

							<div class="form-group android">
								<label class="col-sm-3 control-label">GCM FEEDBACK API2 URL</label>
								
								<div class="col-sm-7">
									<input type="text" class="form-control" id="gcm_feedback_api2">
								</div>								
							</div>
							
							<div class="form-group iphone" style="display:none;">
								<label class="col-sm-3 control-label">APNS CERT</label>
								
								<div class="col-sm-7">
									<textarea class="form-control" rows="5" id="cert"></textarea>
								</div>								
							</div>
							
							<div class="form-group iphone" style="display:none;">
								<label class="col-sm-3 control-label">APNS KEY</label>
								
								<div class="col-sm-7">
									<textarea class="form-control" rows="5" id="key"></textarea>
								</div>								
							</div>
							
							<div class="form-group iphone" style="display:none;">
								<label class="col-sm-3 control-label">운영 여부</label>
								
								<div class="col-sm-3">
									<select class="form-control" id="is_production">
										<option value="1">Production</option>	
										<option value="0">Development</option>	
									</select>
								</div>								
							</div>
							
							<div class="form-group iphone" style="display:none;">
								<label class="col-sm-3 control-label">APNS WORKER 수</label>
								
								<div class="col-sm-1">
									<input type="text" class="form-control" id="apns_worker_count">
								</div>								
							</div>
							
							<div class="form-group iphone" style="display:none;">
								<label class="col-sm-3 control-label">APNS QUEUE 이름</label>
								
								<div class="col-sm-3">
									<input type="text" class="form-control" id="apns_queue_name">
								</div>								
							</div>
							
							<div class="form-group iphone" style="display:none;">
								<label class="col-sm-3 control-label">APNS FEEDBACK API1 URL</label>
								
								<div class="col-sm-7">
									<input type="text" class="form-control" id="apns_feedback_api1">
								</div>								
							</div>

							<div class="form-group iphone" style="display:none;">
								<label class="col-sm-3 control-label">APNS FEEDBACK API2 URL</label>
								
								<div class="col-sm-7">
									<input type="text" class="form-control" id="apns_feedback_api2">
								</div>								
							</div>
						</form>
						
					</div>
					
				</div>
				
				
				<div style="text-align:right">
					<button type="button" class="btn btn-info" style="" id="write_btn">등록</button>
				</div>
			</div>						
		</div>

	<?php include('include/footer.php'); ?>
	
	</div> <!-- main-content end -->
</div>


<link rel="stylesheet" href="/asse	ts/js/zurb-responsive-tables/responsive-tables.css">
<link rel="stylesheet" href="/assets/js/wysihtml5/bootstrap-wysihtml5.css">

<!-- Bottom Scripts -->
<script src="/assets/js/gsap/main-gsap.js"></script>
<script src="/assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/joinable.js"></script>
<script src="/assets/js/resizeable.js"></script>
<script src="/assets/js/neon-api.js"></script>
<script src="/assets/js/zurb-responsive-tables/responsive-tables.js"></script>
<script src="/assets/js/neon-custom.js"></script>
<script src="/assets/js/neon-demo.js"></script>
<script src="/assets/js/bootstrap-switch.min.js"></script>
	<script src="/assets/js/wysihtml5/wysihtml5-0.4.0pre.min.js"></script>
	<script src="/assets/js/wysihtml5/bootstrap-wysihtml5.js"></script>
	<script src="/assets/js/ckeditor/ckeditor.js"></script>
	<script src="/assets/js/ckeditor/adapters/jquery.js"></script>
	<script src="/assets/js/fileinput.js"></script>
	<script src="/assets/js/dropzone/dropzone.js"></script>

	
	<script>
		$(document).ready(function(){
			
			$("sample_wysiwyg.editor").ckeditor(function( textarea ) {
				alert(textarea);	
   			});
		});
	</script>

	
	
</body>
</html>