<?php
permission_required('list', $board);

$style = $board->get_style();

if (isset($_GET['search'])) {
	// backward compatibility
	$_GET['search']['keyword'] = $_GET['search']['text'];
	unset($_GET['search']['text']);
	redirect_to(query_string_for($_GET['search']));
}

$finder = new PostFinder($board);
$board->finder = $finder;
$finder->set_page(get_requested_page());
$finder->get_post_body = $style->skin->get_option('get_body_in_the_list', true);

if (isset($_GET['keyword']) && trim($_GET['keyword'])) {
	$keyword = $_GET['keyword'];
	$finder->set_keyword($keyword);
	foreach (array('title', 'body', 'comment') as $key) {
		if (isset($_GET[$key]) && $_GET[$key]) {
			$finder->add_condition($key);
		}
	}
}

if ($board->use_category) {
	$categories = $board->get_categories();
	$un = new UncategorizedPosts($board);
	if ($un->exists())
		array_unshift($categories, $un);
	if (isset($_GET['category']) && $_GET['category'] !== '') {
		if ($_GET['category'] == 0)
			$category = new UncategorizedPosts($board);
		else
			$category = Category::find($_GET['category']);
		$finder->set_category($category);
	}
}
$posts = $finder->get_posts();
apply_filters_array('PostList', $posts);

$post_count = $board->get_post_count();
$matched_post_count = $finder->get_post_count();
?>
