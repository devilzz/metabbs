<?php
class Comment extends Model {
	var $model = 'comment';
	var $comments = array();
	var $parent = 0;
	var $name;
	var $body;

	function _init() {
		$this->table = get_table_name('comment');
		$this->user_table = get_table_name('user');
		$this->post_table = get_table_name('post');
	}
	function find($id) {
		$db = get_conn();
		$table = get_table_name('comment');
		return $db->fetchrow("SELECT * FROM $table WHERE id=?", 'Comment', array($id));
	}
	function create() {
		$this->created_at = time();
		$this->password = md5(@$this->password);
		Model::create();
		$post = $this->get_post();
		$post->update_comment_count();
	}
	function get_user() {
		return User::find($this->user_id);
	}
	function get_post() {
		return find_and_cache('post', $this->post_id);
	}
	function get_board() {
		$post = $this->get_post();
		return $post->get_board();
	}
	function get_parent() {
		if ($this->parent) return Comment::find($this->parent);
		else return null;
	}
	function has_child() {
		$count = $this->db->fetchone("SELECT COUNT(*) FROM $this->table WHERE parent=$this->id");
		return $count > 0;
	}
	function valid() {
		return !empty($this->body);
	}
	function delete() {
		if ($this->has_child()) {
			$this->name = '';
			$this->user_id = -1;
			$this->body = 'deleted by ' . $this->deleted_by;
			$this->password = '';
			$this->update();
		} else {
			Model::delete();
		}
		$post = $this->get_post();
		$post->update_comment_count();
	}
}
?>
