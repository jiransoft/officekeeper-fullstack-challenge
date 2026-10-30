<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 사용자 모델
 */
class User_model extends CI_Model
{
    protected $table = 'users';

    /**
     * ID로 사용자 조회
     */
    public function get_user_by_id($user_id)
    {
        $query = $this->db->where('id', $user_id)->get($this->table);
        return $query->row();
    }

    /**
     * 전체 사용자 목록
     */
    public function get_all_users()
    {
        $query = $this->db->order_by('name', 'ASC')->get($this->table);
        return $query->result();
    }
}
