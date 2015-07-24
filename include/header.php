<script>
	$(document).ready(function() {
		$('#logout').click(function(){
			if(confirm('로그아웃 하시겠습니까?'))
			{
				$.ajax({
				  type: "POST",
				  url: "/logout",
				  dataType: "json",
				  data: {}
				}) .done(function( response ) {
					if(response.result == 1) {
						setTimeout("window.location.href = '/login'", 600);
					}
					else {	
						alert('로그아웃 실패하였습니다.');
					}
				});
			}
		});
	});
</script>

<div class="row">
	
	<!-- Profile Info and Notifications -->
	<div class="col-md-6 col-sm-8 clearfix">
		
		
	
	</div>
	
	
	<!-- Raw Links -->
	<div class="col-md-6 col-sm-4 clearfix hidden-xs">
		
		<ul class="list-inline links-list pull-right">
			<li>
				<a href="#"><?php echo $this->session->userdata('id'); ?> </a>
			</li>
			
			<li class="sep"></li>
			
			<li>
				<a href="#" id="logout">
					Log Out <i class="entypo-logout right"></i>
				</a>
			</li>
		</ul>
		
	</div>
	
</div>