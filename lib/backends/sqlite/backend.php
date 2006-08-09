<?php
/*
SQLite backend by LuzLuna
*/

function get_conn() {
    static $conn;
    global $config;
    if (!isset($conn)) {
        $conn = new SQLiteAdapter;
        $conn->connect($config->get('dbfile'));
    }
    return $conn;
}

class SQLiteAdapter
{
    var $conn;

    function connect($dbfilename) {
        $this->conn = sqlite_open($dbfilename, 0666);
        register_shutdown_function(array(&$this, 'disconnect'));
    }
    function disconnect() {
        @sqlite_close($this->conn);
    }

    function query($query, $check_error = true) {
        if (!$query) {
            return;
        }
//echo $query;
        $result = @sqlite_query($query, $this->conn);
        if (!$result && $check_error) {
            trigger_error(sqlite_error_string(sqlite_last_error($this->conn)), E_USER_WARNING);
            exit;
        }
        return $result;
    }
    function querymany($queries) {
        foreach ($queries as $query) {
            $this->query($query);
        }
    }
    function query_from_file($name) {
        $data = trim(implode('', file($name)));
        $statements = explode(';', $data);
        array_walk($statements, array($this, 'query'));
    }
    function fetchall($query, $model = 'Model') {
        $results = array();
        $result = $this->query($query);
        while ($data = sqlite_fetch_array($result, SQLITE_ASSOC)) {
            $results[] = new $model($data);
        }
        return $results;
    }
    function fetchrow($query, $model = 'Model') {
        $data = sqlite_fetch_array($this->query($query), SQLITE_ASSOC);
        return new $model($data);
    }
    function fetchone($query) {
        $value = sqlite_fetch_single($this->query($query));
        return $value;
    }
    function insertid() {
        return sqlite_last_insert_rowid($this->conn);
    }
    function add_table($table) {
        $this->querymany($table->to_sql());
    }
    function rename_table($ot, $t) {
        $this->query("ALTER TABLE meta_$ot RENAME TO meta_$t");
    }
    function drop_table($table) {
        $this->query("DROP TABLE meta_$table");
    }
}
function model_datetime() {
    return date("YmdHis");
}

?>
