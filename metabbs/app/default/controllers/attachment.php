<?php
if (!$params['id']) {
	header('HTTP/1.1 404 Not Found');
	print_notice(i('Attachment not found'), i("Attachment %s doesn't exist.", !$params['id']));
}
$attachment = Attachment::find($params['id']);
if (!$attachment->exists() || !$attachment->file_exists()) {
	header('HTTP/1.1 404 Not Found');
	print_notice(i('Attachment not found'), i("Attachment #%d doesn't exist.", $params['id']));
}
?>