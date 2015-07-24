<!DOCTYPE html>
<html lang="en"><head>
	<meta charset="utf-8">
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Neon Admin Panel">
	<meta name="author" content="">
	
	<title>xenixstudio 서비스 플랫폼 - 푸시 상세보기</title>
	
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
			$('#main-menu > li:nth-child(2)').addClass('opened');
			$('#main-menu > li > ul > li:nth-child(1)').addClass('active');
			
			$('#confirm_btn').click(function(){
				var m_current_page = $('#current_page').val();
				
				document.location.href = '/push/history?current_page=' + m_current_page;
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
				<strong>푸시</strong>
			</li>
		</ol>
		
		<h2>푸시 상세보기</h2>
		<br/>



		<div class="row">
			<div class="col-md-12">
			
				<div class="panel panel-primary" data-collapsed="0">
		
					<div class="panel-body">
						
						<form role="form" class="form-horizontal form-groups-bordered" enctype="multipart/form-data">
						
							<input type="hidden" id="current_page" value="<?php echo $current_page; ?>" />
						
							<div class="form-group">
								<label class="col-sm-3 control-label">푸시 ID</label>
								
								<div class="col-sm-2">
									<input type="text" class="form-control" value="<?php echo $push['push_id']; ?>" disabled="true" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">서비스</label>
								
								<div class="col-sm-3">
									<input type="text" class="form-control" value="<?php echo $push['name']; ?>" disabled="true" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">디바이스</label>
								
								<div class="col-sm-2">
									<input type="text" class="form-control" value="<?php echo $push['device']; ?>" disabled="true" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">제목</label>
								
								<div class="col-sm-7">
									<input type="text" class="form-control" value="<?php echo $push['title']; ?>" disabled="true" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">Payload</label>
								
								<div class="col-sm-7">
									<input type="text" class="form-control" value='<?php echo $push['payload']; ?>' disabled="true" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">상태</label>
								
								<div class="col-sm-2">
									<input type="text" class="form-control" value="<?php echo $push['status']; ?>" disabled="true" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">실패 이유</label>
								
								<div class="col-sm-7">
									<textarea class="form-control">
										<?php echo $push['fail_reason']; ?>
									</textarea>									
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">전송 날짜</label>
								
								<div class="col-sm-3">
									<input type="text" class="form-control" value="<?php echo $push['send_date']; ?>" disabled="true" />
								</div>
							</div>

						</form>
						
					</div>
					
				</div>
				
				
				<div style="text-align:right">
					<button type="button" class="btn btn-info" style="" id="confirm_btn">확인</button>
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