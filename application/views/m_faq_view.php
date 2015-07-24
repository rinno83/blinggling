<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
		<title></title>
		<meta name="author" content="Xenix Studio" />

		<style>
			html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,embed,font,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,dl,dt,dd,ol,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td {
				margin:0;padding:0;border:0;vertical-align:baseline;
			}
			img {
				border:0;
			}
			form, fieldset{
				border:0;margin:0;padding:0;
			}
			.type2 {
				margin:0;padding:0;border:0;vertical-align:baseline;
			}
		</style>
		<link rel="stylesheet" type="text/css" href="/assets/mobile/css/style.css" />
	
		<script type="text/javascript" src="/assets/mobile/js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="/assets/mobile/js/common.js"></script>
		<script type="text/javascript" src="/assets/mobile/js/jquery.bxslider.min.js"></script>
		
		<script>
			$(document).ready(function() {
				$('#title').click(function(){
					
				});
			});
			
			function show_content(nid)
			{
				var content_notice = $('#'+nid);
				
				if(content_notice.css('display') == 'none')
				{
					$('.tr_bg_gray').hide();
					$('.ico_more > img').attr('src', '/assets/images/mobile/ico_arrow.png');
					
					content_notice.slideDown('slow');
					$('#arrow_img_'+nid).attr('src', '/assets/images/mobile/ico_arrow_down.png');
				}
				else
				{
					content_notice.slideUp('fast');
					$('#arrow_img_'+nid).attr('src', '/assets/images/mobile/ico_arrow.png');
				}
				
				$('html,body').animate({scrollTop:$('#title'+nid).offset().top}, 500);
			}
		</script>
		
	</head>
	<body class="list">
		<div class="wrap">
			<section class="list_style">
				<ul class="type2">
				<?php 
					foreach($list as $row):
				?>
					<li>
						<div class="list_title">
							<p>
								<?php 
									echo $row['title']; 
									if($row['is_new'] == 'Y')
									{
										echo '<i class="new">N</i>';
									}
								?>
							</p>
						</div>
						<div class="list_content">
							<?php echo $row['content']; ?>
						</div>
					</li>
				<?php 
					endforeach;
				?>
				</ul>
			</section>
		</div>
	</body>
</html>