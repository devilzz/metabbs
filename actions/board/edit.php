<?php
if (!$account->is_admin()) {
	access_denied();
}
function get_skins() {
	$skins = array();
	$dir = opendir('skins');
	while ($file = readdir($dir)) {
		if ($file{0} != '_' && $file{0} != '.' && is_dir("skins/$file")) {
			$skins[] = $file;
		}
	}
	closedir($dir);
	return $skins;
}
if (is_post()) {
	if ($_GET['tab'] == 'general') {
		$_board = new Board($_POST['board']);
		if ($_board->name != $board->name && !$_board->validate()) {
			$flash = "Board '$_board->name' already exists.";
			$skin = '_admin';
			$skins = get_skins();
			render('edit');
			return;
		}
	}
	$board->import($_POST['board']);
	$board->update();
	if ($_GET['tab'] == 'category') {
		foreach ($_POST['categories'] as $_category) {
			if (!empty($_category)) {
				$board->add_category(new Category(array('name' => $_category)));
			}
		}
	}
	redirect_to(url_for($board, 'edit', array('tab'=>$_GET['tab'])));
}
$skin = '_admin';
$skins = get_skins();
render('edit');

// Local Variables:
// mode: php
// tab-width: 4
// c-basic-offset: 4
// indet-tabs-mode: t
// End:
// vim: set ts=4 sts=4 sw=4 noet:
?>
