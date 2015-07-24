<!DOCTYPE html>
<html lang="en"><head>
	<meta charset="utf-8">
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Neon Admin Panel">
	<meta name="author" content="">
	
	<title>xenixstudio 서비스 플랫폼 - 푸시 전송 내역</title>
	
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
			if("<?php echo $timezone; ?>".length==0){
	            var visitortime = new Date();
	            var visitortimezone = visitortime.getTimezoneOffset();
	            $.ajax({
	                type: "GET",
	                url: "/timezone",
	                data: 'time='+ visitortimezone,
	                success: function(){
	                    location.reload();
	                }
	            });
	        }
		
		
		
			$('#main-menu > li:nth-child(2)').addClass('opened');
			$('#main-menu > li > ul > li:nth-child(1)').addClass('active');
			
			// Page Click
			$('body').on('click', '.pagination > li', function(e) {
				e.preventDefault();
				var m_current_page = $('#current_page').val();
				var m_current_block = $('#current_block').val();

				if(m_current_page != $(this).text())
				{
					if($(this).attr('id') == 'prev')
					{
						if(!$(this).hasClass('disabled'))
						{
							$.ajax_paging((m_current_block - 1) * 10);
						}
					}
					else if($(this).attr('id') == 'next')
					{
						if(!$(this).hasClass('disabled'))
						{
							$.ajax_paging((m_current_block * 10) + 1);
						}
					}
					else
					{
						$.ajax_paging($(this).text());
					}
				}
			});
			
			
			$.ajax_paging = function(m_page) {
				console.log(m_page);
				
				var keyword = $('#search_input').val();
				
				$.ajax({ 
					type: 'POST',
					url: "/push/history_paging",
					dataType: "json",
					data: 'current_page='+m_page+'&keyword='+keyword,
					error:function(data){
						console.log('ajax_paging fail');	
						console.log(data);
					},
					success:function(result){
						if(result != undefined && result != null)
						{
							//var data = JSON.parse(result);
							if(result.result == 1)
							{
								console.log(result);
								if(result.db_data.length == 0 && m_page > 1)
								{
									$.ajax_paging(m_page - 1);
								}
								else
								{
									var html = '';
								
									for(var i=0;i<result.db_data.length;i++)
									{
										html += '<tr>';									
										html += '<td>'+result.db_data[i]['push_id']+'</td>';
										html += '<td>'+result.db_data[i]['name']+'</td>';
										html += '<td>'+result.db_data[i]['device']+'</td>';
										html += '<td><a href="/push/detail?push_id='+result.db_data[i]['push_id']+'&current_page='+result.page_data['page_info']['current_page']+'">'+result.db_data[i]['title']+'</td>';
										html += '<td>'+result.db_data[i]['status']+'</td>';
										html += '<td>'+result.db_data[i]['send_date']+'</td>';
										html += '</tr>';
									}	
									
									$('#table_list > tbody').html(html);
									
									$('#current_page').val(result.page_data['page_info']['current_page']);
									$('#current_block').val(result.page_data['page_info']['current_block']);
									$('.pagination').html(result.page_data['paging']);	
								}							
							}	
							else
							{
								console.log('ajax_paging fail');	
								console.log(result);
							}
						}
					}
				});			
			}
			
			
			// Keyword
			$('#search_input').keypress(function(event){
				if(event.which == 13)
				{
					$.ajax_paging(1);
				}
			});
			
			
			
			
			$('#write_form_btn').click(function(){
				document.location.href = '/notice/write_form';
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
				<strong>푸시</strong>
			</li>
		</ol>
		
		<h2>푸시 발송 내역
			<span style="font-size:15px;float:right;">검색 : <input type="text" style="font-size:12px;" id="search_input" /></span>
		</h2>
		<br/>



		<div class="row">
			<div class="col-md-12">
			
				<input type="hidden" id="current_page" value="<?php echo $page_info['current_page']; ?>" />
				<input type="hidden" id="current_block" value="<?php echo $page_info['current_block']; ?>" />			
				
				<!-- <h4>Hover Rows</h4> -->
				
				<table class="table table-hover" id="table_list">
					<thead align="center">
						<tr>
							<th>#</th>
							<th>서비스</th>
							<th>디바이스</th>
							<th>제목</th>
							<th>상태</th>
							<th>등록일</th>
						</tr>
					</thead>
					
					<tbody>
					<?php 
						foreach($list as $row):
					?>
						<tr>
							<td><?php echo $row['push_id']; ?></td>
							<td><?php echo $row['name']; ?></td>
							<td><?php echo $row['device']; ?></td>							
							<td>
								<a href="/push/detail?push_id=<?php echo $row['push_id']; ?>&current_page=<?php echo $page_info['current_page']; ?>">
									<?php echo $row['title']; ?>
								</a>
							</td>
							<td><?php echo $row['status']; ?></td>							
							<td><?php echo $row['send_date']; ?></td>							
						</tr>
					<?php 
						endforeach;
					?>
					</tbody>
				</table>
				
				<ul class="pagination">
					<?php echo $paging; ?>
				</ul>
				
				
			</div>			
			
			<div class="modal-footer">				
			</div>
			
		</div>

	<?php include('include/footer.php'); ?>
	
	</div> <!-- main-content end -->
</div>


<link rel="stylesheet" href="/assets/js/zurb-responsive-tables/responsive-tables.css">

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


	
	
</body>
</html>