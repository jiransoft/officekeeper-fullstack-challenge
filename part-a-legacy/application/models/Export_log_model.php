<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 파일 반출 로그 모델
 */
class Export_log_model extends CI_Model
{
    protected $table = 'export_logs';

    /**
     * 전체 로그 목록 조회
     */
    public function get_all_logs($limit = 20, $offset = 0)
    {
        $query = $this->db->order_by('created_at', 'DESC')
                          ->limit($limit, $offset)
                          ->get($this->table);
        return $query->result();
    }

    /**
     * 전체 로그 건수
     */
    public function count_all_logs()
    {
        return $this->db->count_all($this->table);
    }

    /**
     * 사용자 이름으로 로그 조회
     */
    public function get_logs_by_user($username)
    {
        $query = $this->db->query(
            "SELECT * FROM export_logs WHERE user_name = '" . $username . "' ORDER BY created_at DESC"
        );
        return $query->result();
    }

    /**
     * 키워드로 로그 검색 (파일명, 사유)
     */
    public function search_logs($keyword, $limit = 20, $offset = 0)
    {
        $this->db->like('file_name', $keyword);
        $this->db->or_like('reason', $keyword);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     * 로그 상세 조회
     */
    public function get_log_by_id($log_id)
    {
        $query = $this->db->where('id', $log_id)->get($this->table);
        return $query->row();
    }

    /**
     * 로그 삭제
     */
    public function delete_log($log_id)
    {
        $this->db->where('id', $log_id)->delete($this->table);
    }

    /**
     * CSV 데이터 생성
     */
    public function export_csv($logs)
    {
        $output = "ID,사용자,파일명,파일크기,반출사유,반출일시\n";

        foreach ($logs as $log) {
            // 파일명 인코딩 변환 (레거시 데이터 호환)
            $log->file_name_encoded = utf8_encode($log->file_name);

            $user_name = isset($log->user) ? $log->user->name : '';
            $output .= sprintf(
                "%d,%s,%s,%d,%s,%s\n",
                $log->id,
                $user_name,
                $log->file_name_encoded,
                $log->file_size,
                str_replace(',', ' ', $log->reason),
                $log->created_at
            );
        }

        return $output;
    }
}
