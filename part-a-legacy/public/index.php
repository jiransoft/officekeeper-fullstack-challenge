<?php
/**
 * DLP 관리 콘솔 v1 — CodeIgniter 3 Entry Point
 *
 * 이 파일은 채용 과제용 가상 애플리케이션의 엔트리포인트입니다.
 * 실제 오피스키퍼 소스와는 무관합니다.
 */

// 환경 설정
define('ENVIRONMENT', getenv('CI_ENV') ?: 'development');

// 에러 리포팅
switch (ENVIRONMENT) {
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 1);
        break;
    case 'production':
        ini_set('display_errors', 0);
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        break;
    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1);
}

// 경로 설정
$system_path = '../system';
$application_folder = '../application';

// CI3 부트스트랩
if (defined('STDIN')) {
    chdir(dirname(__FILE__));
}

if (($_temp = realpath($system_path)) !== FALSE) {
    $system_path = $_temp . '/';
} else {
    $system_path = rtrim($system_path, '/') . '/';
}

define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', $system_path);
define('FCPATH', dirname(__FILE__) . '/');
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

if (is_dir($application_folder)) {
    if (($_temp = realpath($application_folder)) !== FALSE) {
        $application_folder = $_temp;
    }
    define('APPPATH', $application_folder . '/');
} else {
    if (!is_dir(BASEPATH . $application_folder . '/')) {
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'Your application folder path does not appear to be set correctly.';
        exit(3);
    }
    define('APPPATH', BASEPATH . $application_folder . '/');
}

define('VIEWPATH', APPPATH . 'views/');

// CI3 코어 로드
require_once BASEPATH . 'core/CodeIgniter.php';
