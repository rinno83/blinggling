
<!DOCTYPE html>
<html lang="en"><head>
	<meta charset="utf-8">
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Neon Admin Panel">
	<meta name="author" content="">
	
	<title>crazyfish - 결제 화면</title>
	
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
			
		});	
		
	</script>
	
</head>
<body class="page-body" data-url="http://neon.dev">

<div class="page-container" style="min-height:100%;padding-left: 0;"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	
	
	<div class="main-content" style="min-height: 100%;">
		
		<div class="row">
			<div class="col-md-12">
			
				<div class="panel panel-primary" data-collapsed="0">
		
					<div class="panel-body">
						
						
						
						
						
						
						
						<form role="form" class="form-horizontal form-groups-bordered" id="LGD_PAYINFO" action="/xpay/payreq_crossplatform.php" method="post">
							<input type="hidden" name="LGD_BUYERIP" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>"/>
							<input type="hidden" name="LGD_BUYERID" value="1"/>
							<input type="hidden" name="LGD_TIMESTAMP" value="<?php echo time(); ?>"/>
							<input type="hidden" name="CST_PLATFORM" value="test"/>
							
						    <div>
						        <table class="table table-hover" id="table_list">
						            <tr>
						                <td>상점아이디(t를 제외한 아이디) </td>
						                <td><input type="text" class="form-control" name="CST_MID" value="mediau"/></td>
						            </tr>
						            <tr>
						                <td>구매자 이름 </td>
						                <td><input type="text" class="form-control" name="LGD_BUYER" value="홍길동"/></td>
						            </tr>
						            <tr>
						                <td>상품정보 </td>
						                <td><input type="text" class="form-control" name="LGD_PRODUCTINFO" value="<?php echo $menu_name; ?>"/></td>
						            </tr>
						            <tr>
						                <td>결제금액 </td>
						                <td><input type="text" class="form-control" style="width: 83%;float: left;text-align: right;" name="LGD_AMOUNT" value="<?php echo $total_price; ?>"/><span class="form-control" style="border: none;">&nbsp;원</span></td>
						            </tr>
						            <tr>
						                <td>구매자 이메일 </td>
						                <td><input type="text" class="form-control" name="LGD_BUYEREMAIL" value="<?php echo $email; ?>"/></td>
						            </tr>
						            <tr>
						                <td>주문번호 </td>
						                <td><input type="text" class="form-control" name="LGD_OID" value="<?php echo $order_code; ?>"/></td>
						            </tr>
						            <tr>
						                <td>구매 날짜 </td>
						                <td><input type="text" class="form-control" value="<?php date_default_timezone_set('Asia/Seoul'); echo date('Y-m-d H:i:s', time()); ?>"/></td>
						            </tr>           
						            <tr>
						                <td>초기결제수단 </td>
						                <td><select class="form-control" name="LGD_CUSTOM_USABLEPAY">
													<option value="SC0010">신용카드</option>				
													<option value="SC0030">계좌이체</option>				
													<option value="SC0040">무통장입금</option>				
													<option value="SC0060">휴대폰</option>				
						<!--
													<option value="SC0070">유선전화결제</option>				
													<option value="SC0090">OK캐쉬백</option>				
													<option value="SC0111">문화상품권</option>				
													<option value="SC0112">게임문화상품권</option>				
						-->
										</select></td>
									</tr>
						            <tr>
						                <td colspan="2">
<!-- 						                <input type="submit" value="결제하기" /><br/> -->
											<div style="text-align:right;padding-top: 13px;">
												<button type="submit" class="form-control btn btn-info">결제하기</button>
											</div>
						                </td>
						            </tr>
						        </table>
						    </div>
						</form>
						
						
						
						
						
						
						
						
						
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>






	
	
</body>
</html>