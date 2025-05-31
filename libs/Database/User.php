<?php
if (!defined("REQUEST")) {
    exit("The Request Not Found");
}
class USER extends DATABASE
{
    private $__table = "users";
    private $__key = "id";
    public function __construct()
    {
        parent::connect();
    }
    public function __destruct()
    {
        parent::disconnect();
    }
    public function user_insert_data($data)
    {
        return parent::insert($this->__table, $data);
    }
    public function user_delete_by_id($id)
    {
        return $this->remove($this->__table, $this->__key . "=" . (int) $id);
    }
    public function user_update_by_id($data, $id)
    {
        return $this->update($this->__table, $data, $this->__key . "=" . (int) $id);
    }
    public function user_select_by_id($data, $id)
    {
        $sql = "SELECT " . $data . " FROM " . $this->__table . " WHERE " . $this->__key . " = " . (int) $id;
        return $this->get_row($sql);
    }
    public function user_get_row_data($conditions)
    {
        $sql = "SELECT * FROM " . $this->__table . " WHERE " . $conditions;
        return $this->get_row($sql);
    }
    public function user_get_list_data($conditions)
    {
        $sql = "SELECT * FROM " . $this->__table . " WHERE " . $conditions;
        return $this->get_list($sql);
    }
    public function user_num_rows_data($conditions)
    {
        $sql = "SELECT * FROM " . $this->__table . " WHERE " . $conditions;
        return $this->num_rows($sql);
    }
    public function plus_money($user_id, $amount, $reason, $trans_id = NULL)
    {
        if ($trans_id == NULL) {
            $trans_id = uniqid() . "_" . mt_rand(0, 9999999);
        }
        $isInsert = parent::insert("cash_flow", ["initial_amount" => get_user($user_id, "money"), "changed_amount" => $amount, "current_amount" => get_user($user_id, "money") + $amount, "created_time" => get_time(), "reason" => $reason, "user_id" => $user_id, "trans_id" => $trans_id]);
        if ($isInsert) {
            $isUpdate = parent::plus("users", "money", $amount, " `id` = '" . $user_id . "' ");
            if ($isUpdate) {
                parent::plus("users", "total_money", $amount, " `id` = '" . $user_id . "' ");
                return true;
            }
        }
        return false;
    }
    public function refund_money($user_id, $amount, $reason, $trans_id = NULL)
    {
        if ($trans_id == NULL) {
            $trans_id = uniqid() . "_" . mt_rand(0, 9999999);
        }
        $isInsert = parent::insert("cash_flow", ["initial_amount" => get_user($user_id, "money"), "changed_amount" => $amount, "current_amount" => get_user($user_id, "money") + $amount, "created_time" => get_time(), "reason" => $reason, "user_id" => $user_id, "trans_id" => $trans_id]);
        if ($isInsert) {
            $isUpdate = parent::plus("users", "money", $amount, " `id` = '" . $user_id . "' ");
            if ($isUpdate) {
                return true;
            }
        }
        return false;
    }
    public function minus_money($user_id, $amount, $reason, $trans_id = NULL)
    {
        if ($trans_id == NULL) {
            $trans_id = uniqid() . "_" . mt_rand(0, 9999999);
        }
        $isInsert = parent::insert("cash_flow", ["initial_amount" => get_user($user_id, "money"), "changed_amount" => $amount, "current_amount" => get_user($user_id, "money") - $amount, "created_time" => get_time(), "reason" => $reason, "user_id" => $user_id, "trans_id" => $trans_id]);
        if ($isInsert) {
            $isRemove = parent::minus("users", "money", $amount, " `id` = '" . $user_id . "' ");
            if ($isRemove) {
                return true;
            }
        }
        return false;
    }
    public function set_status_ban($user_id, $action)
    {
        $Mobile_Detect = new Detection\MobileDetect();
        parent::insert("logs", ["user_id" => $user_id, "ip" => get_ip(), "device" => $Mobile_Detect->getUserAgent(), "created_time" => get_time(), "action" => $action]);
        parent::update("users", ["status_ban" => 'on'], " `id` = '" . $user_id . "' ");
    }
    public function add_spin($user_id, $amount, $action)
    {
        $Mobile_Detect = new Detection\MobileDetect();
        parent::insert("logs", ["user_id" => $user_id, "ip" => get_ip(), "device" => $Mobile_Detect->getUserAgent(), "created_time" => get_time(), "action" => $action]);
        $isUpdate = parent::plus("users", "spin", $amount, " `id` = '" . $user_id . "' ");
        if ($isUpdate) {
            return true;
        }
        return false;
    }


}
