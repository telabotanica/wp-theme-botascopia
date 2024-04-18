<?php function botascopia_module_pagination($data)
{
	$defaults = [
		'id'        => '',
		'page'      => 1,
		'totalPage' => 1,
		'href'      => '',
		'schema'	=> 't'
	];
	
	$data = botascopia_styleguide_data($defaults, $data);
	
	$page      = $data->page;
	$totalPage = $data->totalPage;
	$prevPage = $page - 1;
	$nextPage = $page + 1;
	$schema = $data->schema;
	
	echo sprintf('<div id="%s" class="pagination">', $data->id);
	
	if ($page > 2) {
		$html= "<a class='page-numbers' href='%s?$schema=1'>
		  <span class='meta-nav screen-reader-text'>Page </span>
		  1
		  </a>
		  <span class='page-numbers dots'>…</span>";
		echo sprintf($html, $data->href);
	}
	
	if ($page != 1){
		$html = "<a class='prev page-numbers' href='%s?$schema=%s')>Page précédente</a>
		<a class='page-numbers' href='%s?$schema=%s'>
			<span class='meta-nav screen-reader-text'>Page </span>
			%s
		</a>";
		echo sprintf($html, $data->href, $prevPage, $data->href, $prevPage, $prevPage);
	}
	
	echo sprintf('
 		<span aria-current="page" class="page-numbers current">
			<span class="meta-nav screen-reader-text">Page </span>
				%s
		</span>', $page);
	
	if ($page != $totalPage){
		$html = "
		<a class='next page-numbers' href='%s?$schema=%s')>Page suivante</a>
		<a class='page-numbers' href='%s?$schema=%s'>
			<span class='meta-nav screen-reader-text'>Page </span>
			%s
		</a>";
		echo sprintf($html, $data->href, $nextPage, $data->href, $nextPage, $nextPage);
	}
	
	if ($page != ($totalPage - 1) && $page != $totalPage){
		$html = "
		<span class='page-numbers dots'>…</span>
	  <a class='page-numbers' href='%s?$schema=%s'>
		<span class='meta-nav screen-reader-text'>Page </span>
		%s
		</a>";
		echo sprintf($html, $data->href, $totalPage, $totalPage);
	}
	
	echo '</div>';
}