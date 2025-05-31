<?php
if (!defined('REQUEST')) {
    exit('The Request Not Found');
}
include_once(__DIR__ . '/../vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
session_start();
class DATABASE
{
    private $connect;

    public function connect()
    {
        if (!$this->connect) {
            $this->connect = new mysqli(
                $_ENV['DB_HOST'],
                $_ENV['DB_USERNAME'],
                $_ENV['DB_PASSWORD'],
                $_ENV['DB_DATABASE']
            );
            if ($this->connect->connect_error) {
                exit('Kết nối database thất bại !');
            }

            if (!$this->connect->set_charset("utf8")) {
                exit('Lỗi ký tự UTF8 !');
            }
        }
    }

    public function disconnect()
    {
        if ($this->connect) {
            $this->connect->close();
            $this->connect = null;
        }
    }

    public function setting($data)
    {
        $this->connect();
        $stmt = $this->connect->prepare("SELECT `setting_value` FROM `settings` WHERE `setting_key` = ?");
        $stmt->bind_param('s', $data);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['setting_value'] ?? null;
    }

    public function query($sql)
    {
        $this->connect();
        $stmt = $this->connect->prepare($sql);
        if (!$stmt) {
            exit('Câu truy vấn chưa chính xác !');
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $return = array();
        while ($row = $result->fetch_assoc()) {
            $return[] = $row;
        }
        $stmt->close();
        return $return;
    }

    public function plus($table, $data, $number, $where)
    {
        $this->connect();
        $sql = "UPDATE `$table` SET `$data` = `$data` + ? WHERE $where";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('d', $number);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function minus($table, $data, $number, $where)
    {
        $this->connect();
        $sql = "UPDATE `$table` SET `$data` = `$data` - ? WHERE $where";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param('d', $number);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function insert($table, $data)
    {
        $this->connect();
        $fields = implode(",", array_keys($data));
        $placeholders = implode(",", array_fill(0, count($data), '?'));
        $types = str_repeat('s', count($data));
        $stmt = $this->connect->prepare("INSERT INTO $table ($fields) VALUES ($placeholders)");
        if (!$stmt) {
            exit('Câu truy vấn chưa chính xác !');
        }
        $stmt->bind_param($types, ...array_values($data));
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function update($table, $data, $where)
    {
        $this->connect();
        $set = implode(", ", array_map(fn($k) => "$k = ?", array_keys($data)));
        $types = str_repeat('s', count($data));
        $stmt = $this->connect->prepare("UPDATE $table SET $set WHERE $where");
        $stmt->bind_param($types, ...array_values($data));
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function update_value($table, $data, $where, $limit)
    {
        $this->connect();
        $set = implode(", ", array_map(fn($k) => "$k = ?", array_keys($data)));
        $types = str_repeat('s', count($data)) . 'i';
        $stmt = $this->connect->prepare("UPDATE $table SET $set WHERE $where LIMIT ?");
        $stmt->bind_param($types, ...array_merge(array_values($data), [$limit]));
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function remove($table, $where)
    {
        $this->connect();
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $this->connect->prepare($sql);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function get_list($sql)
    {
        $this->connect();
        $stmt = $this->connect->prepare($sql);
        if (!$stmt) {
            exit('Câu truy vấn chưa chính xác !');
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $return = array();
        while ($row = $result->fetch_assoc()) {
            $return[] = $row;
        }
        $stmt->close();
        return $return;
    }

    public function get_row($sql)
    {
        $this->connect();
        $stmt = $this->connect->prepare($sql);
        if (!$stmt) {
            exit('Câu truy vấn chưa chính xác !');
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ?: false;
    }

    public function num_rows($sql)
    {
        $this->connect();
        $result = $this->connect->query($sql);
        if (!$result) {
            exit('Câu truy vấn chưa chính xác !');
        }
        $row_count = $result->num_rows;
        $result->free();
        return $row_count;
    }
}
