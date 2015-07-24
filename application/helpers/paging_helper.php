<?php 
	function pagingHTML($action, $current_page, $total_row)
	{
		$page_result = array();
		
		$page_block = 5;
		$page_record = 10;
		//$current_page = isset($_GET['current_page'])?$_GET['current_page']:1;
		//var_dump('current_page => '.$current_page);
		$current_block = ceil($current_page / $page_block);
		//var_dump('current_block => '.$current_block);
		
		$total_page = ceil($total_row / $page_record);
		//var_dump('total_page => '.$total_page);
		$total_block = ceil($total_page / $page_block);		
		//var_dump('total_block => '.$total_block);
		
		$start_record = (($current_page-1) * $page_record) + 1;
		//var_dump('start_record => '.$start_record);
		$start_page = (($current_block-1) * $page_block) + 1;
		//var_dump('start_page => '.$start_page);
		$end_page = ( ($start_page+$page_block) <= $total_page )? ($start_page+$page_block) : $total_page;
		//var_dump('end_page => '.$end_page);
		
		$page_html = '';
		
		if($total_block > 1 && $current_block > 1)
		{
			$page_html = '<li><a href="'.$action.'current_page='.(($current_block - 1) * 5).'"><i class="entypo-left-open-mini"></i></a></li>';
		}
		else
		{
			$page_html = '<li class="disabled"><a href="#"><i class="entypo-left-open-mini"></i></a></li>';
		}
		
		for($i = $start_page; $i < $end_page; $i++)
		{
			if($current_page == $i)
			{
				$page_html .= '<li class="active"><a href="#">'.$i.'</a></li>';
			}
			else
			{
				$page_html .= '<li><a href="'.$action.'current_page='.$i.'">'.$i.'</a></li>';	
			}			
		}
		
		if($total_block > 1)
		{
			if($total_block > $current_block)
			{
				$page_html .= '<li><a href="'.$action.'current_page='.($current_page + 5).'"><i class="entypo-right-open-mini"></i></a></li>';		
			}
			else
			{
				$page_html .= '<li class="disabled"><a href=""><i class="entypo-right-open-mini"></i></a></li>';		
			}
		}
		else
		{
			$page_html .= '<li class="disabled"><a href=""><i class="entypo-right-open-mini"></i></a></li>';	
		}
		
		//var_dump($page_html);
		
		return $page_html;
	}
	
	function ajax_pagingHTML($current_page, $total_row)
	{
		$page_result = array();
		
		$page_block = 5;
		$page_record = 10;
		//$current_page = isset($_GET['current_page'])?$_GET['current_page']:1;
		//var_dump('current_page => '.$current_page);
		$current_block = ceil($current_page / $page_block);
		//var_dump('current_block => '.$current_block);
		
		$total_page = ceil($total_row / $page_record);
		//var_dump('total_page => '.$total_page);
		$total_block = ceil($total_page / $page_block);		
		//var_dump('total_block => '.$total_block);
		
		$start_record = (($current_page-1) * $page_record) + 1;
		//var_dump('start_record => '.$start_record);
		$start_page = (($current_block-1) * $page_block) + 1;
		//var_dump('start_page => '.$start_page);
		$end_page = ( ($start_page+($page_block - 1)) <= $total_page )? ($start_page+($page_block - 1)) : $total_page;
		//var_dump('end_page => '.$end_page);
		
		$page_html = '';
		
		if($total_block > 1 && $current_block > 1)
		{
			$page_html = '<li id="prev"><a href="#"><i class="entypo-left-open-mini"></i></a></li>';
		}
		else
		{
			$page_html = '<li id="prev" class="disabled"><a href="#"><i class="entypo-left-open-mini"></i></a></li>';
		}
		
		for($i = $start_page; $i <= $end_page; $i++)
		{
			if($current_page == $i)
			{
				$page_html .= '<li class="active"><a href="#">'.$i.'</a></li>';
			}
			else
			{
				$page_html .= '<li><a href="#">'.$i.'</a></li>';	
			}			
		}
		
		if($total_block > 1)
		{
			if($total_block > $current_block)
			{
				$page_html .= '<li id="next"><a href="#"><i class="entypo-right-open-mini"></i></a></li>';		
			}
			else
			{
				$page_html .= '<li id="next" class="disabled"><a href="#"><i class="entypo-right-open-mini"></i></a></li>';		
			}
		}
		else
		{
			$page_html .= '<li id="next" class="disabled"><a href="#"><i class="entypo-right-open-mini"></i></a></li>';	
		}
		
		//var_dump($page_html);
		
		return $page_html;
	}
	
	
	function ajax_pagingHTML_pageBlock($current_page, $total_row, $page_block)
	{
		$page_result = array();
		
		$page_record = 10;
		//$current_page = isset($_GET['current_page'])?$_GET['current_page']:1;
		//var_dump('current_page => '.$current_page);
		$current_block = ceil($current_page / $page_block);
		//var_dump('current_block => '.$current_block);
		
		$total_page = ceil($total_row / $page_record);
		//var_dump('total_page => '.$total_page);
		$total_block = ceil($total_page / $page_block);		
		//var_dump('total_block => '.$total_block);
		
		$start_record = (($current_page-1) * $page_record) + 1;
		//var_dump('start_record => '.$start_record);
		$start_page = (($current_block-1) * $page_block) + 1;
		//var_dump('start_page => '.$start_page);
		$end_page = ( ($start_page+($page_block - 1)) <= $total_page )? ($start_page+($page_block - 1)) : $total_page;
		//var_dump('end_page => '.$end_page);
		
		$page_html = '';
		
		if($total_block > 1 && $current_block > 1)
		{
			$page_html = '<li id="prev"><a href="#"><i class="entypo-left-open-mini"></i></a></li>';
		}
		else
		{
			$page_html = '<li id="prev" class="disabled"><a href="#"><i class="entypo-left-open-mini"></i></a></li>';
		}
		
		for($i = $start_page; $i <= $end_page; $i++)
		{
			if($current_page == $i)
			{
				$page_html .= '<li class="active"><a href="#">'.$i.'</a></li>';
			}
			else
			{
				$page_html .= '<li><a href="#">'.$i.'</a></li>';	
			}			
		}
		
		if($total_block > 1)
		{
			if($total_block > $current_block)
			{
				$page_html .= '<li id="next"><a href="#"><i class="entypo-right-open-mini"></i></a></li>';		
			}
			else
			{
				$page_html .= '<li id="next" class="disabled"><a href="#"><i class="entypo-right-open-mini"></i></a></li>';		
			}
		}
		else
		{
			$page_html .= '<li id="next" class="disabled"><a href="#"><i class="entypo-right-open-mini"></i></a></li>';	
		}
		
		//var_dump($page_html);
		
		return $page_html;
	}
	
	
	
	function get_page_info($current_page, $total_row)
	{
		$page_result = array();
		
		$page_block = 5;
		$page_record = 10;
		//var_dump('current_page => '.$current_page);
		$current_block = ceil($current_page / $page_block);
		$page_result['current_block'] = $current_block;
		//var_dump('current_block => '.$current_block);
		
		$total_page = ceil($total_row / $page_record);
		$page_result['total_page'] = $total_page;
		//var_dump('total_page => '.$total_page);
		$total_block = ceil($total_page / $page_block);		
		$page_result['total_block'] = $total_block;
		//var_dump('total_block => '.$total_block);
		
		$start_record = (($current_page-1) * $page_record) + 1;
		$page_result['start_record'] = $start_record;
		//var_dump('start_record => '.$start_record);
		$start_page = (($current_block-1) * $page_block) + 1;
		$page_result['start_page'] = $start_page;
		//var_dump('start_page => '.$start_page);
		$end_page = ( ($start_page+$page_block) <= $total_page )? ($start_page+$page_block) : $total_page;
		$page_result['end_page'] = $end_page;
		//var_dump('end_page => '.$end_page);
		
		return $page_result;
	}
	
	function get_page_info_pageBlock($current_page, $total_row, $page_block)
	{
		$page_result = array();
		
		$page_record = 10;
		//var_dump('current_page => '.$current_page);
		$current_block = ceil($current_page / $page_block);
		$page_result['current_block'] = $current_block;
		//var_dump('current_block => '.$current_block);
		
		$total_page = ceil($total_row / $page_record);
		$page_result['total_page'] = $total_page;
		//var_dump('total_page => '.$total_page);
		$total_block = ceil($total_page / $page_block);		
		$page_result['total_block'] = $total_block;
		//var_dump('total_block => '.$total_block);
		
		$start_record = (($current_page-1) * $page_record) + 1;
		$page_result['start_record'] = $start_record;
		//var_dump('start_record => '.$start_record);
		$start_page = (($current_block-1) * $page_block) + 1;
		$page_result['start_page'] = $start_page;
		//var_dump('start_page => '.$start_page);
		$end_page = ( ($start_page+$page_block) <= $total_page )? ($start_page+$page_block) : $total_page;
		$page_result['end_page'] = $end_page;
		//var_dump('end_page => '.$end_page);
		
		return $page_result;
	}
?>