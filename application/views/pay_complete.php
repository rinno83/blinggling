<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
		<title></title>
		<meta name="author" content="Xenix Studio" />

		<link rel="stylesheet" type="text/css" href="/assets/mobile/css/reset.css" />
		<link rel="stylesheet" type="text/css" href="/assets/mobile/css/fonts.css" />
		<link rel="stylesheet" type="text/css" href="/assets/mobile/css/style.css" />
	
		<script type="text/javascript" src="/assets/mobile/js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="/assets/mobile/js/common.js"></script>
		<script type="text/javascript" src="/assets/mobile/js/jquery.bxslider.min.js"></script>
		<script>
			$(document).ready(function() {
				$('#submit_btn').click(function(){
					$('form').submit();
				});
				
				$('.li_menu').click(function(){
					console.log($(this).attr('value'));
					
					$('#LGD_CUSTOM_USABLEPAY').val($(this).attr('value'));
					$('.btn_select').text($(this).text());
					
					$('.select_box').toggleClass('open');
				});
			});	
			
		</script>
	</head>
	<body>
		<div class="wrap">
			<section class="content list_style2">
				<form role="form" class="form-horizontal form-groups-bordered" id="LGD_PAYINFO" action="/xpay/payreq_crossplatform.php" method="post">
					<input type="hidden" name="LGD_BUYERIP" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>"/>
					<input type="hidden" name="LGD_BUYERID" value="1"/>
					<input type="hidden" name="LGD_TIMESTAMP" value="<?php echo time(); ?>"/>
					<input type="hidden" name="CST_PLATFORM" value="test"/>
					<input type="hidden" name="CST_MID" value="mediau"/>
					<input type="hidden" name="LGD_BUYER" value="<?php echo $name; ?>"/>
					<input type="hidden" name="LGD_PRODUCTINFO" value="<?php echo $menuString; ?>"/>
					<input type="hidden" name="LGD_AMOUNT" value="<?php echo $totalPrice; ?>"/>
					<input type="hidden" name="LGD_OID" value="<?php echo $orderCode; ?>"/>
					<input type="hidden" id="LGD_CUSTOM_USABLEPAY" name="LGD_CUSTOM_USABLEPAY" value="SC0010"/>
					
					<input type="hidden" name="LGD_BUYEREMAIL" value="<?php echo $email; ?>" />
					<div class="cell cell1">
						<p class="left dt">아이디</p>
						<div class="right text2"><p><?php echo $email; ?></p></div>
					</div>
					<div class="cell cell1">
						<p class="left dt">구매자 이름</p>
						<div class="right text2"><p><?php echo $name; ?></p></div>
					</div>
					<div class="cell cell1">
						<p class="left dt">상품정보</p>
						<div class="right text2"><p><?php echo $menuString; ?></p></div>
					</div>
					<div class="cell cell1">
						<p class="left dt">결제금액</p>
						<div class="right text2"><p><?php echo $totalPrice; ?>원</p></div>
					</div>
					<div class="cell cell1">
						<p class="left dt">주문번호</p>
						<div class="right text2"><p><?php echo $orderCode; ?></p></div>
					</div>
					<div class="cell cell1">
						<p class="left dt">구매날짜</p>
						<div class="right text2"><p><?php date_default_timezone_set('Asia/Seoul'); echo date('Y-m-d H:i:s', $registDate); ?></p></div>
					</div>
					<div class="cell cell1">
						<p class="left dt">결제방식</p>
						<div class="right text2">
							<div class="select_box">
<!--
								<select class="select_menu">
									<option>신용카드</option>
									<option>계좌이체</option>
									<option>무통장입금</option>
									<option>휴대폰</option>
								</select>
-->
							  <button type="button" class="btn_select">신용카드 </button>
							  <ul class="select_menu">
								<li><a class="li_menu" value="SC0010" href="#">신용카드</a></li>
								<li><a class="li_menu" value="SC0030" href="#">계좌이체</a></li>
								<li><a class="li_menu" value="SC0040" href="#">무통장입금</a></li>
								<li><a class="li_menu" value="SC0060" href="#">휴대폰</a></li>
							  </ul>
							</div>
						</div>
					</div>
					<hr class="dott_line" />
					<div class="cell cell1">
						<p class="dt">결제상품</p>
						<div class="right text2">
						<?php 
							foreach($menu as $key => $row):
						?>
							<p><?php echo $row['menuName']; ?> <?php echo $row['menuCount']; ?>인</p>
						<?php 
							endforeach;
						?>
						</div>
					</div>
					<hr class="dott_line" />
					<div class="cell cell1">
						<p class="left dt">예약일시</p>
						<div class="right text2"><p><?php echo date('Y-m-d H:i:s', $orderDateTime); ?></p></div>
					</div>
					<div class="cell cell1">
						<p class="left dt">예약자 연락처</p>
						<div class="right text2"><p><?php echo $phone_string; ?></p></div>
					</div>
					<div class="cell cell1">
						<p class="left dt">수령지</p>
						<div class="right text2"><p><?php echo $receiveStore; ?></p></div>
					</div>
					<div class="cell cell1">
						<p class="left dt">수령방법</p>
						<div class="right text2"><p><?php echo $receiveTypeName; ?></p></div>
					</div>
					<div>
						<div class="cell cell1">
							<p class="dt">메모</p>
						</div>
						<div class="memo">
							<p><?php echo $memo; ?></p>
						</div>
					</div>
				</form>
			</section>
			<footer class="copy">
				<p>
					(주)미디어유코프 (대표 이지선) / 사업자등록번호 : 107-86-88505 / 통신
					판매업신고 : 제 2013-서울강남-00523호 / 서울 강남구 선릉로135길 29 
					(논현동, 1층) / 고객센터 1644-2016 /crazyfish@mediau.net
				</p>
			</footer>
			<div class="btn_bottom">
				<p><button id="submit_btn">결제하기</button></p>
			</div>
		</div>
	</body>
</html>