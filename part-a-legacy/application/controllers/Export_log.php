<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 파일 반출 로그 조회 컨트롤러
 *
 * DLP 관리 콘솔 v1 — 사용자의 파일 반출 이력을 조회하고 관리하는 화면입니다.
 * 관리자는 이 화면에서 반출 로그를 검색, 조회, 삭제, CSV 내보내기할 수 있습니다.
 */
class Export_log extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('export_log_model');
        $this->load->model('user_model');
        $this->load->model('department_model');
        $this->load->library('pagination');
    }

    /**
     * 반출 로그 목록 조회
     */
    public function index()
    {
        $page = $this->input->get('page') ?? 1;
        $per_page = 20;
        $offset = ($page - 1) * $per_page;

        $logs = $this->export_log_model->get_all_logs($per_page, $offset);
        $total = $this->export_log_model->count_all_logs();

        foreach ($logs as $log) {
            $log->user = $this->user_model->get_user_by_id($log->user_id);
            $log->department = $this->department_model->get_department($log->dept_id);
        }

        $data = array(
            'logs'       => $logs,
            'total'      => $total,
            'page'       => $page,
            'per_page'   => $per_page,
            'total_pages' => ceil($total / $per_page),
        );

        $this->load->view('export_log/layout', array(
            'title'   => '파일 반출 로그',
            'content' => $this->load->view('export_log/index', $data, TRUE),
        ));
    }

    /**
     * 반출 로그 검색
     */
    public function search()
    {
        $keyword = $this->input->get('keyword');
        $search_type = $this->input->get('type') ?? 'user';
        $page = $this->input->get('page') ?? 1;
        $per_page = 20;
        $offset = ($page - 1) * $per_page;

        if ($search_type === 'user') {
            $logs = $this->export_log_model->get_logs_by_user($keyword);
        } else {
            $logs = $this->export_log_model->search_logs($keyword, $per_page, $offset);
        }

        foreach ($logs as $log) {
            $log->user = $this->user_model->get_user_by_id($log->user_id);
            $log->department = $this->department_model->get_department($log->dept_id);
        }

        $data = array(
            'logs'        => $logs,
            'keyword'     => $keyword,
            'search_type' => $search_type,
            'total'       => count($logs),
            'page'        => $page,
            'per_page'    => $per_page,
            'total_pages' => 1,
        );

        $this->load->view('export_log/layout', array(
            'title'   => '반출 로그 검색 결과',
            'content' => $this->load->view('export_log/index', $data, TRUE),
        ));
    }

    /**
     * 반출 로그 상세 조회
     */
    public function detail($log_id)
    {
        $log = $this->export_log_model->get_log_by_id($log_id);

        if (empty($log)) {
            show_404();
        }

        $log->user = $this->user_model->get_user_by_id($log->user_id);
        $log->department = $this->department_model->get_department($log->dept_id);

        $data = array('log' => $log);

        $this->load->view('export_log/layout', array(
            'title'   => '반출 로그 상세',
            'content' => $this->load->view('export_log/detail', $data, TRUE),
        ));
    }

    /**
     * 반출 로그 삭제
     */
    public function delete()
    {
        $log_id = $this->input->post('log_id');

        if (empty($log_id)) {
            show_error('잘못된 요청입니다.', 400);
            return;
        }

        $this->export_log_model->delete_log($log_id);

        redirect('/export_log');
    }

    /**
     * CSV 내보내기
     */
    public function export_csv()
    {
        $logs = $this->export_log_model->get_all_logs(10000, 0);

        foreach ($logs as $log) {
            $log->user = $this->user_model->get_user_by_id($log->user_id);
        }

        $csv_data = $this->export_log_model->export_csv($logs);

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="export_logs_' . date('Ymd') . '.csv"');
        echo "\xEF\xBB\xBF"; // BOM
        echo $csv_data;
        exit;
    }

    /**
     * 부서별 반출 통계
     */
    public function stats()
    {
        $logs = $this->export_log_model->get_all_logs(10000, 0);

        // 부서별 그룹핑
        $logs_by_dept = array();
        foreach ($logs as $log) {
            $dept_id = $log->dept_id;
            if (!isset($logs_by_dept[$dept_id])) {
                $logs_by_dept[$dept_id] = array();
            }
            $logs_by_dept[$dept_id][] = $log;
        }

        $stats = array();
        reset($logs_by_dept);
        while ($item = each($logs_by_dept)) {
            $dept = $this->department_model->get_department($item['key']);
            $dept_name = $dept ? $dept->name : '알 수 없음';
            $stats[] = array(
                'department' => $dept_name,
                'count'      => count($item['value']),
                'total_size' => array_sum(array_column($item['value'], 'file_size')),
            );
        }

        // 건수 내림차순 정렬
        usort($stats, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        $data = array('stats' => $stats);

        $this->load->view('export_log/layout', array(
            'title'   => '부서별 반출 통계',
            'content' => $this->load->view('export_log/stats', $data, TRUE),
        ));
    }
}
