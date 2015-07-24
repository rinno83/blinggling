<!DOCTYPE html>
<html lang="en"><head>
	<meta charset="utf-8">
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Neon Admin Panel">
	<meta name="author" content="">
	
	<title>xenixstudio 서비스 플랫폼 - FAQ 작성</title>
	
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
			$('#main-menu > li > ul > li:nth-child(2)').addClass('active');
			
			
			
			$('#write_btn').click(function(){
			
				var m_service_id = $('#service').val();
				var m_service_key = $('#service option:selected').attr('service_key');
				var m_title = $('#title').val();
				var m_content = $( 'textarea.ckeditor' ).val();
				var m_lang_code = $('#lang').val();
				var m_is_show = $('#is_show').parent().hasClass('switch-on');
				
				if(m_title == '')
				{
					alert('제목을 입력해주세요.');
					$('#title').focus();
				}
				else if(m_content == '')
				{
					alert('내용을 입력해주세요.');
					CKEDITOR.instances.editor1.focus();
				}
				else
				{	
					$.ajax({ 
						type: 'POST',
						url: "/faq/write",
						dataType: "json",
						data: 'service_id='+m_service_id+'&service_key='+m_service_key+'&title='+encodeURIComponent(m_title)+'&content='+encodeURIComponent(m_content)+'&lang_code='+m_lang_code+'&is_show='+m_is_show,
						error:function(data){
							alert('등록 실패했습니다.\nstatusCode : ' + data.readyState + '\nresponseText : ' + data.responseText);
							console.log(data.responseText);
						},
						success:function(result){
							if(result.result == 1)
							{
								alert('등록되었습니다.');
								window.location.href = '/faq';
							}
							else
							{
								alert('등록 실패했습니다. 개발자에게 문의바랍니다.');
							}							
						}
					});
				}				
			});
			
			$('#make_url_btn').click(function(){
				var url_array = new Array();
				
				$('.fileinput-preview').each(function() {
					if($(this).children().length > 0)
					{
						url_array.push($(this).children().attr('src'));
					}
				});
				
				if(url_array.length > 0)
				{
					$.ajax({ 
						type: 'POST',
						url: "/file/make_url",
						dataType: "json",
						data: 'urls='+encodeURIComponent(JSON.stringify(url_array)),
						error:function(data){
							console.log('message/write fail');	
							console.log(data);
						},
						success:function(result){
							console.log(result);
							if(result.result == 1)
							{
								for(var i=0;i<result.data.length;i++)
								{
									$('#image_url'+i).val(result.data[i]);
								}
							}
							else
							{
								alert('실패');
							}							
						}
					});
				}
				else
				{
					alert('URL을 만들 이미지를 올려주세요.');
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
				<strong>FAQ</strong>
			</li>
		</ol>
		
		<h2>FAQ 작성</h2>
		<br/>



		<div class="row">
			<div class="col-md-12">
			
				<div class="panel panel-primary" data-collapsed="0">
		
					<div class="panel-body">
						
						<form role="form" class="form-horizontal form-groups-bordered" enctype="multipart/form-data">
						
							<div class="form-group">
								<label class="col-sm-3 control-label">서비스</label>
								
								<div class="col-sm-2">
									<select class="form-control" id="service">
									
									<?php 
										foreach($service as $row):
									?>
										<option value="<?php echo $row['service_id']; ?>" service_key="<?php echo $row['key']; ?>"><?php echo $row['name']; ?></option>
									<?php 
										endforeach;
									?>
										
									</select>
								</div>
								
							</div>


							<div class="form-group">
								<label class="col-sm-3 control-label">언어</label>
								
								<div class="col-sm-2">
									<select class="form-control" id="lang">
									
									<?php 
										foreach($language as $row):
									?>
										<option value="<?php echo $row['lang_code']; ?>"><?php echo $row['lang_name']; ?></option>
									<?php 
										endforeach;
									?>
										
									</select>
								</div>
								
							</div>


							<div class="form-group">
								<label class="col-sm-3 control-label">보임 설정</label>
								
								<div class="col-sm-5" id="typeDiv">
									<div id="label-switch" class="make-switch" data-on-label="ON" data-off-label="OFF">
										<input type="checkbox" id="is_show" checked>
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">제목</label>
								
								<div class="col-sm-5">
									<input type="text" class="form-control" id="title">
								</div>
							</div>							
							
							<div class="form-group">
								<label class="col-sm-3 control-label">내용</label>
								
								<div class="col-sm-9">
									<textarea class="form-control ckeditor">
											
									</textarea>
								</div>								
							</div>

							
							<div class="form-group">
								<label class="col-sm-3 control-label">파일 첨부</label>
								
								<div class="col-sm-9">
								
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
											<img src="http://placehold.it/200x150" alt="..." onchange="ajaxFileUpload(this)" />
										</div>
										<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
										<div>
											<span class="btn btn-white btn-file">
												<span class="fileinput-new">Select image</span>
												<span class="fileinput-exists">Change</span>
												<input type="file" name="..." accept="image/*">
											</span>
											<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
											
										</div>
										<input type="text" class="form-control" id="image_url0" value=""/>
									</div>
									
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
											<img src="http://placehold.it/200x150" alt="...">
										</div>
										<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
										<div>
											<span class="btn btn-white btn-file">
												<span class="fileinput-new">Select image</span>
												<span class="fileinput-exists">Change</span>
												<input type="file" name="..." accept="image/*">
											</span>
											<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
										</div>
										<input type="text" class="form-control" id="image_url1" value=""/>
									</div>
									
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
											<img src="http://placehold.it/200x150" alt="...">
										</div>
										<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
										<div>
											<span class="btn btn-white btn-file">
												<span class="fileinput-new">Select image</span>
												<span class="fileinput-exists">Change</span>
												<input type="file" name="..." accept="image/*">
											</span>
											<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
										</div>
										<input type="text" class="form-control" id="image_url2" value=""/>
									</div>
									
									<div class="fileinput fileinput-new" style="text-align:right">
										<button type="button" class="btn btn-green" style="" id="make_url_btn">URL 만들기</button>
									</div>
									
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