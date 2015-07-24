<!DOCTYPE html>
<html lang="en"><head>
	<meta charset="utf-8">
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Neon Admin Panel">
	<meta name="author" content="">
	
	<title>crazyfish - 바로 예약</title>
	
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
			$('#main-menu > li > ul > li:nth-child(1)').addClass('active');
			
			
			$('#main_menu_size').change(function(){
				var main_menu_size = parseInt($('#main_menu_size').val());

				var main_menu_select_size = parseInt($('#main_menu_select_size').val());
				$('#main_menu_select_size').val(main_menu_size);
				
				var total_price = parseInt($('#total_price').val());
				var add_price = parseInt(((main_menu_size - main_menu_select_size)) * 20000);
				$('#total_price').val(total_price + add_price);
			});


			$('#other_menu_size').change(function(){
				var other_menu_size = parseInt($('#other_menu_size').val());

				var other_menu_select_size = parseInt($('#other_menu_select_size').val());
				$('#other_menu_select_size').val(other_menu_size);
				
				var total_price = parseInt($('#total_price').val());
				var add_price = parseInt(((other_menu_size - other_menu_select_size)) * 10000);
				$('#total_price').val(total_price + add_price);
			});


			$('#topping_menu_size').change(function(){
				var topping_menu_size = parseInt($('#topping_menu_size').val());

				var topping_menu_select_size = parseInt($('#topping_menu_select_size').val());
				$('#topping_menu_select_size').val(topping_menu_size);
				
				var total_price = parseInt($('#total_price').val());
				var add_price = parseInt(((topping_menu_size - topping_menu_select_size)) * 5000);
				$('#total_price').val(total_price + add_price);
			});
			
			
			$('#other_menu').change(function(){
				var other_menu_id = $('#other_menu').val();
				if(other_menu_id != 0)
				{
					var html = '<option value="2" selected="true">2인분</option><option value="3">3인분</option><option value="4">4인분</option><option value="5">5인분</option><option value="6">6인분</option><option value="7">7인분</option><option value="8">8인분</option><option value="9">9인분</option><option value="10">10인분</option>';
					$('#other_menu_size').html(html);
					
					$('#other_menu_select_size').val(2);
					
					var total_price = parseInt($('#total_price').val());
					var add_price = 30000;
					$('#total_price').val(total_price + add_price);
				}
				else
				{
					$('#other_menu_size').html('<option value="0">------------</option>');
					
					var other_menu_select_size = parseInt($('#other_menu_select_size').val());
					
					var total_price = parseInt($('#total_price').val());
					var add_price = parseInt(((other_menu_select_size - 2) * 10000) + 30000);
					$('#total_price').val(total_price - add_price);
				}
			});


			$('#topping_menu').change(function(){
				var other_menu_id = $('#topping_menu').val();
				if(other_menu_id != 0)
				{
					var html = '<option value="1" selected="true">1인분</option><option value="2">2인분</option><option value="3">3인분</option><option value="4">4인분</option><option value="5">5인분</option><option value="6">6인분</option><option value="7">7인분</option><option value="8">8인분</option><option value="9">9인분</option><option value="10">10인분</option>';
					$('#topping_menu_size').html(html);
					
					$('#topping_menu_select_size').val(1);
					
					var total_price = parseInt($('#total_price').val());
					var add_price = 10000;
					$('#total_price').val(total_price + add_price);
				}
				else
				{
					$('#topping_menu_size').html('<option value="0">------------</option>');
					
					var topping_menu_select_size = parseInt($('#topping_menu_select_size').val());
					
					var total_price = parseInt($('#total_price').val());
					var add_price = parseInt(((topping_menu_select_size - 1) * 5000) + 10000);
					$('#total_price').val(total_price - add_price);
				}
			});
		});	
		
	</script>
	
</head>
<body class="page-body" data-url="http://neon.dev">

<div class="page-container" style="min-height:100%;padding-left: 0;"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<div class="main-content" style="min-height: 100%;">
		
		<h2>바로 예약</h2>
		<br/>



		<div class="row">
			<div class="col-md-12">
			
				<div class="panel panel-primary" data-collapsed="0">
		
					<div class="panel-body">
						
						<form role="form" class="form-horizontal form-groups-bordered" action="/pay/confirm" method="post">
							<input type="hidden" id="main_menu_select_size" value="2"/>
							<input type="hidden" id="other_menu_select_size" value="0"/>
							<input type="hidden" id="topping_menu_select_size" value="0"/>
							<input type="hidden" name="menu_name" value="미친제철"/>
							
							
						
							<div class="form-group">
								<label class="col-sm-3 control-label">미친제철</label>								
							</div>


							<div class="form-group">
								<img src="http://52.68.20.211/images/0000000001.jpg" width="100%"/>
							</div>


							<div class="form-group">
								<label class="col-sm-3 control-label">사이즈</label>
								
								<div class="col-sm-2">
									<select class="form-control" id="main_menu_size">
										<option value="2">2인분</option>
										<option value="3">3인분</option>
										<option value="4">4인분</option>
										<option value="5">5인분</option>
										<option value="6">6인분</option>
										<option value="7">7인분</option>
										<option value="8">8인분</option>
										<option value="9">9인분</option>
										<option value="10">10인분</option>
									</select>
								</div>
								
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">다른 상품</label>
								
								<div class="col-sm-2">
									<select class="form-control" id="other_menu">
										<option value="0">선택안함</option>
										<option value="1">싱싱커플</option>
										<option value="2">싱싱광연제</option>
										<option value="3">싱싱제철</option>
										<option value="4">싱싱세트</option>
										<option value="5">미친자연산</option>
									</select>
								</div>
								
								<div class="col-sm-2">
									<select class="form-control" id="other_menu_size">
										<option value="0">------------</option>
									</select>
								</div>
								
							</div>
							
							
							<div class="form-group">
								<label class="col-sm-3 control-label">토핑</label>
								
								<div class="col-sm-2">
									<select class="form-control" id="topping_menu">
										<option value="0">선택안함</option>
										<option value="1">해산물</option>
										<option value="2">초밥</option>
									</select>
								</div>
								
								<div class="col-sm-2">
									<select class="form-control" id="topping_menu_size">
										<option value="0">------------</option>
									</select>
								</div>
								
							</div>
							
							


							<div class="form-group">
								<label class="col-sm-3 control-label">결제 금액</label>
								
								<div class="col-sm-5">
									<input type="text" class="form-control" style="width: 90%;float: left;text-align: right;" id="total_price" name="total_price" readonly="true" value="50000" /><span class="form-control" style="border: none;">&nbsp;원</span>

								</div>
							</div>							
							
							<div class="form-group">
								<label class="col-sm-3 control-label">메모</label>
								
								<div class="col-sm-9">
									<textarea class="form-control" id="memo" name="memo"></textarea>
								</div>								
							</div>
							
							<div style="text-align:right;padding-top: 13px;">
								<button type="submit" class="btn btn-info">결제하기</button>
							</div>
						</form>
						
					</div>
					
				</div>
			</div>						
		</div>
	
	</div> <!-- main-content end -->
</div>


<link rel="stylesheet" href="/assets/js/zurb-responsive-tables/responsive-tables.css">
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