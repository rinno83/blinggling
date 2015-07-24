$(window).ready(function(){
	/* slider */
	if($('.thum').length > 1)
	{
		$('.bxslider ul').bxSlider({
		  mode: 'horizontal',
		  auto: true,
		  autoControls: true,
		  pause: 5000
		});	
	}
	$('.list_style ul li .list_title').click(function(){
		$(this).toggleClass('on').next('.list_content').toggle();
	});
	$('.select_box > .btn_select').click(function(){
		$('.select_box').toggleClass('open');
	});
	$('.comment_list ul.cell > li').click(function(){
		$('.comment_list ul.cell > li').removeClass('active');
		$(this).addClass('active');
	});
	$('.btn_bin').click(function(){
		$(this).parent('div').parent('li').remove();
	});
});