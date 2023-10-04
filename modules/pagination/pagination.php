<?php function botascopia_module_pagination($data)
{
	$defaults = [
		'id'        => '',
		'page'      => 1,
		'totalPage' => 1,
		'href'      => ''
	];
	
	$data = botascopia_styleguide_data($defaults, $data);
	
	$page      = $data->page;
	$totalPage = $data->totalPage;
	$prevPage = $page - 1;
	$nextPage = $page + 1;
	
	echo sprintf('<div id="%s" class="pagination">', $data->id);
	
	if ($page > 2) {
		echo sprintf('

			  <a class="page-numbers" href="%s?t=1">
				<span class="meta-nav screen-reader-text">Page </span>
				1
				</a>
				<span class="page-numbers dots">…</span>', $data->href);
	}
	
	if ($page != 1){
		echo sprintf('
			<a class="prev page-numbers" href="%s?t=%s")>Page précédente</a>
			<a class="page-numbers" href="%s?t=%s"">
				<span class="meta-nav screen-reader-text">Page </span>
				%s
			</a>', $data->href, $prevPage, $data->href, $prevPage, $prevPage);
	}
	
	echo sprintf('
 		<span aria-current="page" class="page-numbers current">
			<span class="meta-nav screen-reader-text">Page </span>
				%s
		</span>', $page);
	
	if ($page != $totalPage){
		echo sprintf('
			<a class="next page-numbers" href="%s?t=%s")>Page suivante</a>
			<a class="page-numbers" href="%s?t=%s"">
				<span class="meta-nav screen-reader-text">Page </span>
				%s
			</a>', $data->href, $nextPage, $data->href, $nextPage, $nextPage);
	}
	
	if ($page != ($totalPage - 1) && $page != $totalPage){
		echo sprintf('
<span class="page-numbers dots">…</span>
			  <a class="page-numbers" href="%s?t=%s">
				<span class="meta-nav screen-reader-text">Page </span>
				%s
				</a>', $data->href, $totalPage, $totalPage);
	}
	
	echo '</div>';
}