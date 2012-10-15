<?php

/**
 * <Create your comment here>
 *
 * $Revision: $
 * $Id: $
 * $Date:  $
 *
 * @Author: $Author: $
 * @version $Revision: $
 */
class DBClass {

    var $db;
    var $error_message;
    var $result;
    var $debug = false;

    public function __construct($dbfile) {
        $this->db = $db = new SQLiteDatabase($dbfile);
        $this->initDB();
    }

    public function initDB() {
        global $IP;
        $struct = FALSE;

        if (file_exists("{$IP}/db/db.init.php")) {
            $struct = include "{$IP}/db/db.init.php";
        }
        if (is_array($struct)) {
            foreach ($struct as $table => $defs) {
                if (is_array($defs)) {
                    if (!$this->tableExists($table)) {
                        $this->createTable($table, $defs);
                    }
                }
            }
        }
    }

    public function query($sql) {
        error_reporting(0);
        try {
            logger($sql);
            $this->result = $this->db->query($sql, SQLITE_ASSOC, $this->error_message);
            if ($this->db->lastError())
                throw new Exception("ошибка при запросе");
        } catch (Exception $exc) {
            logger($sql);
            logger($this->error_message);
            logger($exc->getTraceAsString());
        }
        return $this->result;
    }

    public function countRecords($table, $countField = '*') {
        $res = $this->query("SELECT count({$countField}) as count FROM {$table}");
        $row = $res->fetchObject();
        return $row->count;
    }

    public function tableExists($tablename) {
        $res = $this->db->query("SELECT name FROM sqlite_master where name='{$tablename}'", SQLITE_ASSOC, $this->error_message);
        if ($res->numRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function describe($tablename) {
        global $filter_fields;
        if ($this->tableExists($tablename)) {
            $describe = array();
            $res = $this->query("PRAGMA table_info({$tablename})");
            if ($res->numRows() > 0) {
                while ($row = $res->fetchObject()) {
                    if (in_array($row->name, $filter_fields)) {
                        $describe[$row->name] = $row->type;
                    }
                }
                return $describe;
            }
        }
        return array();
    }

    public function createTable($tablename, $tabledef) {
        if (is_array($tabledef)) {
            $sql = "CREATE TABLE {$tablename} (";
            $defarray = array();
            foreach ($tabledef as $field => $type) {
                $defarray[] = $field . " " . $type;
            }
            $sql.= implode(', ', $defarray) . ")";
            $this->db->query($sql, SQLITE_ASSOC, $this->error_message);
        }
    }

    public function select($table, $fields = '*', $conds = '', $opts = '') {
        $sql = implode(' ', array(
            "SELECT",
            $this->makeFieldList($fields),
            "FROM",
            $table,
            $this->makeConditions($conds),
            $opts,
                )
        );
        $this->result = $this->query($sql);
        return $this->result;
    }

    public function insert($table, $values) {
        $sql = implode(' ', array(
            'INSERT INTO',
            $table,
            $this->makeInsertList($values),
                )
        );
        return $this->query($sql);
    }

    public function update($table, $values, $conds) {
        $sql = implode(' ', array(
            'UPDATE',
            $table,
            'SET',
            $this->makeUpdateList($values),
            $this->makeConditions($conds),
                )
        );
        $this->result = $this->query($sql);
        return $this->db->changes();
    }

    public function delete($table, $conds) {
        return $this->query($sql = "DELETE FROM {$table} " . $this->makeConditions($conds));
    }

    public function drop($sql) {

    }

    public function beginTransaction() {
        $this->result = $this->db->query('BEGIN TRANSACTION', SQLITE_ASSOC, $this->error_message);
        return $this->result;
    }

    public function commitTransaction() {
        $this->result = $this->db->query('COMMIT TRANSACTION', SQLITE_ASSOC, $this->error_message);
        return $this->result;
    }

    public function rollbackTransaction() {
        $this->result = $this->db->query('ROLLBACK TRANSACTION', SQLITE_ASSOC, $this->error_message);
        return $this->result;
    }

    public function makeFieldList($fields) {
        if (is_array($fields)) {
            return implode(', ', $fields);
        } else {
            return $fields;
        }
    }

    public function makeConditions($conds) {
        if ($conds == '') {
            return '';
        } elseif (is_string($conds)) {
            return "WHERE {$conds}";
        } elseif (is_array($conds)) {
            $whereClause = array();
            foreach ($conds as $key => $value) {
                $whereClause[] = "{$key}={$value}";
            }
            return "WHERE " . $this->makeList($whereClause, ' AND ');
        }
    }

    public function makeList($array, $glue = ', ') {
        if (is_array($array)) {
            return implode($glue, $array);
        }
    }

    public function makeKeyList($array, $glue = ', ') {
        $keys = array_keys($array);
        foreach ($keys as $key) {
            if (is_int($key)) {
                return '';
            }
        }
        return implode($glue, $keys);
    }

    public function makeInsertList($array) {
        $fields = $this->makeKeyList($array, ', ');
        $values = "(" . $this->makeList($array, ', ') . ")";
        return ($fields != '' ? "({$fields}) " : "") . "VALUES {$values}";
    }

    public function makeUpdateList($array) {
        $updates = array();
        foreach ($array as $key => $value) {
            $updates[] = "{$key}={$value}";
        }
        return $this->makeList($updates, ', ');
    }

}
