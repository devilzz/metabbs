<?php
if ($action != 'edit') {
	if (!isset($id)) {
		print_notice(i('No user id'), i('Please append the user id.'));
	}
	$user = User::find_by_user($id);
	if (!$user->exists()) {
		print_notice(i('User not found'), i("User #%d doesn't exist.", $id));
	}
}
$title = htmlspecialchars($user->name);
?>
