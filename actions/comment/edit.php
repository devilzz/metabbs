<?php
$post = $comment->get_post();
if (is_post() && ($account->level >= $board->perm_delete ||
		$comment->password == md5($_POST['password']))) {
	$comment->body = $_POST['body'];
	$comment->update();
	redirect_to(url_for($post) . '#comment_' . $comment->id);
} else {
	$link_cancel = url_for($post);

	if ($comment->user_id != 0 && $comment->user_id == $account->id ||
		$account->level >= $board->perm_delete) {
		$ask_password = false;
	} else {
		$ask_password = true;
	}
	render('edit_comment');
}

// Local Variables:
// mode: php
// tab-width: 4
// c-basic-offset: 4
// indet-tabs-mode: t
// End:
// vim: set ts=4 sts=4 sw=4 noet:
?>
