<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
		<title></title>
		<meta name="author" content="Xenix Studio" />
		<META http-equiv="Expires" content="-1"> 
		<META http-equiv="Pragma" content="no-cache"> 
		<META http-equiv="Cache-Control" content="No-Cache"> 

		<link rel="stylesheet" type="text/css" href="/assets/mobile/css/reset.css" />
		<link rel="stylesheet" type="text/css" href="/assets/mobile/css/fonts.css" />
		<link rel="stylesheet" type="text/css" href="/assets/mobile/css/style.css" />
	
		<script type="text/javascript" src="/assets/mobile/js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="/assets/mobile/js/common.js"></script>
		<script type="text/javascript" src="/assets/mobile/js/jquery.bxslider.min.js"></script>
	</head>
	<body>
		<div class="wrap">
			<section class="visual_slider bxslider type2">
				<ul>
				<?php 
					foreach($menuImage as $row):
				?>
					<li><a class="thum" href="#"><img src="<?php echo $row['menuImageUrl']; ?>" alt="" /></a></li>
				<?php 
					endforeach;
				?>
				</ul>
			</section>
			<section class="content list_style2">
				<div class="cell cell1">
					<p class="dt">결제상품</p>
					<div class="right text2">
					<?php 
						foreach($menu as $row):
					?>
						<p><?php echo $row['menuName']; ?> <?php echo $row['menuCount']; ?>세트</p>
					<?php 
						if($row['addPerson'])
						{
					?>
						<p style="font-size: .9em; position: relative;top: -20px;">추가 <?php echo $row['addPerson']; ?>인분</p>
					<?php
						} 
						endforeach;
					?>
					</div>
				</div>
				<hr class="dott_line" />
				<div class="cell cell1">
					<p class="left dt">예약일시</p>
					<div class="right text2"><p><?php echo $order_datetime; ?></p></div>
				</div>
				<div class="cell cell1">
					<p class="left dt">예약자 연락처</p>
					<div class="right text2"><p><?php echo $customer_phone; ?></p></div>
				</div>
				<div class="cell cell1">
					<p class="left dt">수령지</p>
					<div class="right text2"><p><?php if($store_name != ''){echo $store_name;}else{echo $seller_name;} ?></p></div>
				</div>
				<div>
					<div class="cell cell1">
						<p class="dt">메모</p>
					</div>
					<div class="memo">
						<p><?php echo $memo; ?></p>
					</div>
				</div>
				<div>
					<div class="cell cell1">
						<p class="dt">위치</p>
					</div>
					<div class="tb_con map_wrap">
						<div>
							<p>
								<a target="_blank" style="width: 100%;height: auto;" href="<?php echo $origin_location_image_url; ?>">
									<img src="<?php echo $location_image_url; ?>" alt="" />
								</a>
							</p>
							<div>
								<p class="title">노량진 수산시장</p>
								<p>서울특별시 동작구 노량진동 13-8</p>
							</div>
						</div>
						<div>
							<p><img src="<?php echo $market_image_url; ?>" alt="" /></p>
							<div>
								<p class="title"><?php echo $seller_name; ?></p>
								<p></p>
							</div>
						</div>
					</div>
				</div>
			</section>
			<footer class="copy" style="margin-bottom: 0px;">
				<p>
					<?php echo $corp['name']; ?> (대표 <?php echo $corp['ceo']; ?>) / 사업자등록번호 : <?php echo $corp['regist_number']; ?> / 통신
					판매업신고 : <?php echo $corp['communication_business_report']; ?> / <?php echo $corp['address']; ?> / 고객센터 <?php echo $corp['phone']; ?> / <?php echo $corp['email']; ?>
				</p>
			</footer>
		</div>
	</body>
</html>