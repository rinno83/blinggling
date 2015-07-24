<!DOCTYPE html>
<html lang="en"><head>
	<meta charset="utf-8">
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Neon Admin Panel">
	<meta name="author" content="">
	
	<title>xenixstudio 서비스 플랫폼 - 1:1 문의 수정</title>
	
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
			$('#main-menu > li:nth-child(3)').addClass('opened');
			$('#main-menu > li > ul > li:nth-child(3)').addClass('active');			
			
			$('#write_btn').click(function(){
			
				var m_inquiry_id = $('#inquiry_id').val();
				var m_phone = $('#phone').val();
				var m_email = $('#email').val();
				var m_content = $('#content').val();
				var m_answer = $('#answer').val();
				var m_current_page = $('#current_page').val();
				
				if(m_answer == '')
				{
					alert('답변을 입력해주세요.');
					$('#answer').focus();
				}
				else
				{	
					$.ajax({ 
						type: 'POST',
						url: "/inquiry/modify",
						dataType: "json",
						data: 'inquiry_id='+m_inquiry_id+'&phone='+m_phone+'&email='+m_email+'&content='+encodeURIComponent(m_content)+'&answer='+encodeURIComponent(m_answer),
						error:function(data){
							alert('등록 실패했습니다.\nstatusCode : ' + data.readyState + '\nresponseText : ' + data.responseText);
							console.log(data.responseText);
						},
						success:function(result){
							if(result.result == 1)
							{
								alert('등록되었습니다.');
								window.location.href = '/inquiry?current_page='+m_current_page;
							}
							else
							{
								alert('등록 실패했습니다. 개발자에게 문의바랍니다.');
							}							
						}
					});
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
				<strong>1:1 문의</strong>
			</li>
		</ol>
		
		<h2>1:1 문의 수정</h2>
		<br/>



		<div class="row">
			<div class="col-md-12">
			
				<div class="panel panel-primary" data-collapsed="0">
		
					<div class="panel-body">
						
						<form role="form" class="form-horizontal form-groups-bordered" enctype="multipart/form-data">
						
							<input type="hidden" id="inquiry_id" value="<?php echo $inquiry['inquiry_id']; ?>" />
							<input type="hidden" id="current_page" value="<?php echo $current_page; ?>" />
						

							
							<div class="form-group">
								<label class="col-sm-3 control-label">Email</label>
								
								<div class="col-sm-5">
									<input type="text" class="form-control" id="email" disabled="true" value="<?php echo $inquiry['email']; ?>">
								</div>
							</div>							

							<div class="form-group">
								<label class="col-sm-3 control-label">전화번호</label>
								
								<div class="col-sm-5">
									<input type="text" class="form-control" id="phone" disabled="true" value="<?php echo $inquiry['phone']; ?>">
								</div>
							</div>							
							
							<div class="form-group">
								<label class="col-sm-3 control-label">내용</label>
								
								<div class="col-sm-9">
									<textarea class="form-control" id="content" rows="10"><?php echo $inquiry['content']; ?></textarea>
								</div>								
							</div>
							
							
							<div class="form-group">
								<label class="col-sm-3 control-label">답변</label>
								
								<div class="col-sm-9">
									<textarea class="form-control" id="answer" rows="10"><?php echo $inquiry['answer']; ?></textarea>
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