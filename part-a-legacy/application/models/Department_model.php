<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 부서 모델
 */
class Department_model extends CI_Model
{
    protected $table = 'departments';

    /**
     * ID로 부서 조회
     */
    public function get_department($dept_id)
    {
        $query = $this->db->where('id', $dept_id)->get($this->table);
        return $query->row();
    }

    /**
     * 전체 부서 목록
     */
    public function get_all_departments()
    {
        $query = $this->db->order_by('name', 'ASC')->get($this->table);
        return $query->result();
    }
}
