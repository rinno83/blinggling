<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />
	<title>약관</title>
	<link href="/assets/css/default.css" rel="stylesheet" type="text/css" />
	
	<script src="/assets/js/jquery-1.10.2.min.js"></script>
	<script>
		$(document).ready(function() {
			$('#contact').click(function(){
				
			});
			
			$('#widthraw').click(function(){
				
			});
		});
		
		function show_content(termsid)
		{
			var content_notice = $('#'+termsid);
			
			if(content_notice.css('display') == 'none')
			{
				$('.tr_bg_gray').hide();
				$('.ico_more > img').attr('src', '/assets/images/mobile/ico_arrow.png');
				
				content_notice.slideDown('slow');
				$('#arrow_img_'+termsid).attr('src', '/assets/images/mobile/ico_arrow_down.png');
			}
			else
			{
				content_notice.slideUp('fast');
				$('#arrow_img_'+termsid).attr('src', '/assets/images/mobile/ico_arrow.png');
			}
			
			console.log($('#title'+termsid).position());
			console.log($('#title'+termsid).offset().top);
			
			$('html,body').animate({scrollTop:$('#title'+termsid).offset().top}, 500);
		}
	</script>
	
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;">
	<?php 
		foreach($list as $row):
	?>
        <tr class="tr_bg_white" id="title<?php echo $row['terms_id']; ?>" onclick="show_content(<?php echo $row['terms_id']; ?>)">
          <td class="txt_15_b" style="">
          		<span class="txt_body"><?php echo $row['title']; ?></span>
          		
          </td>
         
          <td class="ico_more"><img id="arrow_img_<?php echo $row['terms_id']; ?>" src="/assets/images/mobile/ico_arrow.png" width="22" height="22" /></td>
        </tr>
    
        <tr class="tr_bg_white">
          <td colspan="4" class="line_bg"></td>
        </tr>
        
        <tr class="tr_bg_gray" id="<?php echo $row['terms_id']; ?>" style="display:none;">
          <td colspan="3" class="txt_15_b"><?php echo $row['content']; ?></td>
        </tr>   
   	<?php 
	    endforeach;
    ?>     

   
</table>
</body>
</html>