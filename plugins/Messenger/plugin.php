<?php
class Message extends Model {
	var $model = 'message';

	function _init() {
		$this->table = get_table_name('message');
	}
	function find($id) {
		$db = get_conn();
		$table = get_table_name('message');
		return $db->fetchrow("SELECT * FROM $table WHERE id=?", 'Message', array($id));
	}
	function get_unread_messages_of($user) {
		$db = get_conn();
		$table = get_table_name('message');
		return $db->fetchall("SELECT * FROM $table WHERE `to`=$user->id AND NOT `read`", 'Message');
	}
	function mark_all_read($user) {
		$db = get_conn();
		$table = get_table_name('message');
		$db->query("UPDATE $table SET `read`=1 WHERE `to`=$user->id");
	}
	function get_sender() {
		return User::find($this->from);
	}
	function get_post() {
		return Post::find($this->post_id);
	}
	function mark_as_read() {
		$this->db->query("UPDATE $this->table SET `read`=1 WHERE id=$this->id");
	}
}

function print_unread_messages($messages) {
	$messages_count = count($messages);
?>
<div id="message-box">
<div class="message-actions">
<a href="<?=url_for('message', 'read')?>all" onclick="markAllRead(this.href); return false">Mark all read</a> /
<a href="#" onclick="$('message-box').hide()">Close</a>
</div>
<h1>You've got <span id="messages-count"><?=$messages_count?></span> message(s)</h1>
<ul>
<?php foreach ($messages as $n => $message) { ?>
	<li id="message-<?=$n+1?>"<? if ($n > 0) { ?> style="display: none"<? } ?>>
		<?=link_to_user($message->get_sender())?>, <?=date("Y-m-d H:i:s", $message->sent_at)?> / <a href="<?=url_for($message, 'read')?>" onclick="return true; markAsRead(this.href, 'message-<?=$n+1?>'); return false">Mark as read</a>
		<div class="message-body">
		<?=format_plain($message->body)?>
		</div>
		<? if ($message->post_id) { ?>
		(from <?=link_to_post($message->get_post())?>)
		<? } ?>
		<div class="message-nav">
		<? if ($n > 0) { ?><a href="#" onclick="showMessage(<?=$n?>); return false">&lsaquo; Previous</a><? } ?>
		<strong><?=$n+1?></strong> / <?=$messages_count?>
		<? if ($n != $messages_count-1) { ?><a href="#" onclick="showMessage(<?=$n+2?>); return false">Next &rsaquo;</a><? } ?>
		</div>
	</li>
<?php } ?>
</ul>
</div>

<script type="text/javascript">
var box = $('message-box');
box.setStyle({
	position: 'absolute',
	top: 0,
	right: 0,
	opacity: 0.9
});

function markAllRead(href) {
	if (window.confirm('<?=i('Are you sure?')?>')) {
		new Ajax.Request(href, {
			method: 'get',
			onSuccess: function () { $('message-box').hide(); }
		});
	}
}
function markAsRead(href, id) {
	new Ajax.Request(href, {
		method: 'get',
		onSuccess: function () {
			var count = $('messages-count');
			count.innerHTML = parseInt(count.innerHTML) - 1;
			if (count.innerHTML == '0') $('message-box').hide();
			else $(id).remove();
		}
	});
}

function showMessage(index) {
	$$('#message-box li').map(Element.hide);
	$('message-'+index).show();
}
</script>
<?php
}

class Messenger extends Plugin {
	var $description = 'Messaging service';

	function on_init() {
		global $account, $layout;
		$messages = Message::get_unread_messages_of($account);
		if ($messages) {
			ob_start();
			print_unread_messages($messages);
			$content = ob_get_contents();
			ob_end_clean();
			$layout->footer .= $content;
			$layout->add_stylesheet(METABBS_BASE_PATH . 'plugins/Messenger/style.css');
		}
		add_handler('message', 'read', array(&$this, 'action_read'));
		add_filter('PostComment', array(&$this, 'notify_reply'), 1024);
	}
	function notify_reply(&$comment) {
		global $account;
		$parent = $comment->get_parent();
		if ($parent && $parent->user_id) {
			$message = new Message;
			$message->from = $account->id;
			$message->to = $parent->user_id;
			$message->body = $comment->body;
			$message->post_id = $comment->post_id;
			$message->sent_at = time();
			$message->read = 0;
			$message->create();
		}
	}
	function action_read() {
		global $id, $account;
		if ($id == 'all') {
			Message::mark_all_read($account);
		} else {
			$message = Message::find($id);
			if ($message->to == $account->id) {
				$message->mark_as_read();
			}
			redirect_back();
		}
	}
	function on_install() {
		$db = get_conn();
		$t = new Table('message');
		$t->column('from', 'integer');
		$t->column('to', 'integer');
		$t->column('body', 'text');
		$t->column('post_id', 'integer');
		$t->column('sent_at', 'timestamp');
		$t->column('read', 'boolean');
		$t->add_index('to');
		$db->add_table($t);
	}
}

register_plugin('Messenger');
?>