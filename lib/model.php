<?php
require_once 'db/revision.php';

function get_table_name($model) {
	return METABBS_TABLE_PREFIX . $model;
}
function get_column_pair($column) {
	return "$column->name=" . $column->to_string();
}

class Model
{
	var $id;

	function Model($attributes = null) {
		$this->db = get_conn();
		$this->import($attributes);
		$this->_init();
	}
	function _init() { }
	function import($attributes) {
		if (is_array($attributes)) {
			foreach ($attributes as $key => $value) {
				$this->$key = $value;
			}
		}
	}
	function exists() {
		return !!$this->id;
	}
	function get_id() {
		return $this->id;
	}
	function get_columns() {
		$columns = $this->db->get_columns($this->table);
		foreach ($columns as $key => $column) {
			$columns[$key]->set_value(@$this->{$column->name});
		}
		return $columns;
	}
	function create() {
		$columns = $this->get_columns();
		$query = "INSERT INTO $this->table";
		$query .= " (".implode(",", array_map('get_column_name', $columns)).")";
		$query .= " VALUES(".implode(",", array_map('column_to_string', $columns)).")";
		$this->db->query($query);
		$this->id = $this->db->insertid();
	}
	function update() {
		$columns = $this->get_columns();
		$query = "UPDATE $this->table SET ";
		$query .= implode(",", array_map('get_column_pair', $columns));
		$query .= " WHERE id=$this->id";
		$this->db->query($query);
	}
	function delete() {
		$this->db->query("DELETE FROM $this->table WHERE id=$this->id");
	}
}

// Local Variables:
// mode: php
// tab-width: 4
// c-basic-offset: 4
// indet-tabs-mode: t
// End:
// vim: set ts=4 sts=4 sw=4 noet:
?>
