-- ============================================================
-- DLP 관리 콘솔 — 시드 데이터
-- docker compose up 시 자동 실행됩니다.
-- ============================================================

CREATE DATABASE IF NOT EXISTS dlp_console
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE dlp_console;

-- 부서 테이블
CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 사용자 테이블
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    user_name VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'manager', 'user') DEFAULT 'user',
    dept_id INT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (dept_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 파일 반출 로그 테이블 (Part A에서 사용)
CREATE TABLE IF NOT EXISTS export_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    user_name VARCHAR(50) NOT NULL,
    dept_id INT,
    file_name VARCHAR(500) NOT NULL,
    file_path VARCHAR(1000) NOT NULL,
    file_size BIGINT NOT NULL DEFAULT 0,
    export_method ENUM('usb', 'email', 'cloud', 'print', 'network') NOT NULL,
    reason TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    reviewed_by INT NULL,
    reviewed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (dept_id) REFERENCES departments(id) ON DELETE SET NULL,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_name (user_name),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- 파일 반출 승인 요청 테이블 (Part B에서 사용 — 스키마만 제공, 지원자가 마이그레이션으로 생성해도 됨)
-- 아래는 참고용입니다. 지원자는 자유롭게 설계할 수 있습니다.
-- CREATE TABLE IF NOT EXISTS export_requests ( ... );

-- ============================================================
-- 시드 데이터
-- ============================================================

-- 부서
INSERT INTO departments (id, name, code) VALUES
(1, '개발팀', 'DEV'),
(2, '기획팀', 'PLAN'),
(3, '디자인팀', 'DESIGN'),
(4, '영업팀', 'SALES'),
(5, '인사팀', 'HR'),
(6, '경영지원팀', 'MGMT'),
(7, '품질관리팀', 'QA');

-- 사용자 (비밀번호는 bcrypt('password123'))
INSERT INTO users (id, name, email, user_name, password, role, dept_id) VALUES
(1,  '김관리', 'admin@dlpconsole.local',    'admin',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin',   1),
(2,  '이개발', 'dev.lee@dlpconsole.local',   'dev.lee',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user',    1),
(3,  '박기획', 'plan.park@dlpconsole.local',  'plan.park',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user',    2),
(4,  '최디자', 'design.choi@dlpconsole.local','design.choi','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user',    3),
(5,  '정영업', 'sales.jung@dlpconsole.local', 'sales.jung', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user',    4),
(6,  '한인사', 'hr.han@dlpconsole.local',     'hr.han',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager', 5),
(7,  '오경영', 'mgmt.oh@dlpconsole.local',    'mgmt.oh',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager', 6),
(8,  '윤품질', 'qa.yoon@dlpconsole.local',    'qa.yoon',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user',    7),
(9,  '서개발', 'dev.seo@dlpconsole.local',    'dev.seo',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user',    1),
(10, '강기획', 'plan.kang@dlpconsole.local',  'plan.kang',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user',    2);

-- 파일 반출 로그 (50건)
INSERT INTO export_logs (user_id, user_name, dept_id, file_name, file_path, file_size, export_method, reason, status, reviewed_by, reviewed_at, created_at) VALUES
(2,  'dev.lee',    1, '2024년_개발로드맵_v3.xlsx',           '/projects/roadmap/2024_roadmap_v3.xlsx',           245760,   'email',   '외부 파트너사 공유',                'approved', 1, '2024-09-01 10:30:00', '2024-09-01 09:15:00'),
(2,  'dev.lee',    1, 'API_설계서_인증모듈.pdf',              '/docs/api/auth_module_design.pdf',                 1048576,  'cloud',   '재택근무 중 참고',                   'approved', 1, '2024-09-01 14:00:00', '2024-09-01 11:20:00'),
(3,  'plan.park',  2, '고객요구사항_분석_Q3.docx',            '/planning/requirements/q3_analysis.docx',          524288,   'usb',     '고객사 미팅 자료',                   'approved', 6, '2024-09-02 09:00:00', '2024-09-01 16:45:00'),
(4,  'design.choi',3, 'UI_목업_대시보드_v2.fig',              '/design/mockups/dashboard_v2.fig',                 3145728,  'cloud',   '외부 디자이너 리뷰',                 'approved', 1, '2024-09-02 15:00:00', '2024-09-02 10:30:00'),
(5,  'sales.jung', 4, '영업실적_2024_Q2.xlsx',                '/sales/reports/2024_q2_performance.xlsx',          189440,   'email',   '본사 보고',                          'approved', 7, '2024-09-03 09:00:00', '2024-09-02 17:00:00'),
(2,  'dev.lee',    1, 'DB_스키마_설계서.sql',                  '/docs/database/schema_design.sql',                 32768,    'email',   'DBA 리뷰 요청',                      'approved', 1, '2024-09-03 11:00:00', '2024-09-03 09:30:00'),
(9,  'dev.seo',    1, '성능테스트_결과_리포트.pdf',             '/testing/performance/report_202409.pdf',           2097152,  'print',   '팀 회의 발표자료 인쇄',               'approved', 1, '2024-09-03 14:00:00', '2024-09-03 13:00:00'),
(3,  'plan.park',  2, '경쟁사_분석_보고서.pptx',               '/planning/competitor/analysis_2024.pptx',          4194304,  'usb',     '임원 보고',                          'approved', 6, '2024-09-04 10:00:00', '2024-09-04 08:30:00'),
(10, 'plan.kang',  2, '서비스_로드맵_2025.xlsx',               '/planning/roadmap/2025_service_roadmap.xlsx',      307200,   'email',   '파트너사 공유',                      'pending',  NULL, NULL,                '2024-09-04 14:20:00'),
(4,  'design.choi',3, '브랜드_가이드라인_v4.pdf',              '/design/brand/guideline_v4.pdf',                   8388608,  'cloud',   '외부 에이전시 전달',                 'approved', 1, '2024-09-05 09:00:00', '2024-09-04 16:00:00'),
(5,  'sales.jung', 4, '거래처_연락처_목록.csv',                '/sales/contacts/partner_list.csv',                 15360,    'email',   '신규 영업사원 공유',                 'rejected', 7, '2024-09-05 10:00:00', '2024-09-05 09:00:00'),
(6,  'hr.han',     5, '채용공고_초안.docx',                    '/hr/recruiting/job_posting_draft.docx',            102400,   'email',   '채용 플랫폼 등록',                   'approved', 7, '2024-09-05 14:00:00', '2024-09-05 11:30:00'),
(2,  'dev.lee',    1, 'docker-compose.yml',                    '/projects/infra/docker-compose.yml',               4096,     'cloud',   'CI/CD 파이프라인 구성',               'approved', 1, '2024-09-05 17:00:00', '2024-09-05 15:45:00'),
(8,  'qa.yoon',    7, '테스트_케이스_인증모듈.xlsx',            '/testing/testcases/auth_module_tc.xlsx',           153600,   'email',   '외부 QA팀 공유',                     'approved', 1, '2024-09-06 10:00:00', '2024-09-06 08:30:00'),
(9,  'dev.seo',    1, '장애보고서_20240905.pdf',               '/incidents/20240905_report.pdf',                   716800,   'print',   'CTO 보고',                           'approved', 1, '2024-09-06 11:00:00', '2024-09-06 09:15:00'),
(3,  'plan.park',  2, '사용자_설문조사_결과.xlsx',              '/planning/survey/user_survey_results.xlsx',        409600,   'usb',     '외부 컨설턴트 전달',                 'pending',  NULL, NULL,                '2024-09-06 14:30:00'),
(2,  'dev.lee',    1, 'source_backup_20240906.tar.gz',         '/backup/source_backup_20240906.tar.gz',           52428800, 'usb',     '개인 백업',                          'rejected', 1, '2024-09-06 18:00:00', '2024-09-06 17:30:00'),
(4,  'design.choi',3, '아이콘_세트_v3.zip',                    '/design/icons/icon_set_v3.zip',                   12582912, 'cloud',   '프리랜서 디자이너 전달',              'approved', 1, '2024-09-07 09:00:00', '2024-09-07 08:00:00'),
(5,  'sales.jung', 4, '제안서_A기업_2024.pptx',                '/sales/proposals/company_a_2024.pptx',            6291456,  'email',   '고객사 제출',                        'approved', 7, '2024-09-07 14:00:00', '2024-09-07 10:00:00'),
(10, 'plan.kang',  2, '프로젝트_WBS.xlsx',                     '/planning/wbs/project_wbs_2024.xlsx',             204800,   'email',   'PM 리뷰',                            'approved', 6, '2024-09-08 09:00:00', '2024-09-07 16:00:00'),
(2,  'dev.lee',    1, 'README.md',                              '/projects/main/README.md',                        8192,     'cloud',   '오픈소스 기여',                      'approved', 1, '2024-09-08 11:00:00', '2024-09-08 09:30:00'),
(6,  'hr.han',     5, '급여_명세서_템플릿.xlsx',                '/hr/payroll/salary_template.xlsx',                 71680,    'print',   '출력 후 보관',                       'rejected', 7, '2024-09-08 15:00:00', '2024-09-08 13:00:00'),
(8,  'qa.yoon',    7, '자동화_테스트_스크립트.py',               '/testing/automation/test_script.py',              20480,    'email',   '외부 QA 도구 연동',                  'approved', 1, '2024-09-09 09:00:00', '2024-09-08 17:00:00'),
(9,  'dev.seo',    1, 'Nginx_설정_파일.conf',                   '/infra/nginx/app.conf',                          2048,     'cloud',   '스테이징 서버 배포',                  'approved', 1, '2024-09-09 10:30:00', '2024-09-09 09:00:00'),
(3,  'plan.park',  2, '회의록_제품기획_0909.docx',              '/planning/meetings/20240909_product.docx',        163840,   'email',   '팀 전체 공유',                       'approved', 6, '2024-09-09 16:00:00', '2024-09-09 14:30:00'),
(2,  'dev.lee',    1, 'JWT_토큰_검증_모듈.php',                 '/app/modules/auth/JwtValidator.php',              12288,    'email',   '코드 리뷰 요청',                     'approved', 1, '2024-09-10 10:00:00', '2024-09-10 08:45:00'),
(4,  'design.choi',3, '와이어프레임_모바일_v1.sketch',           '/design/wireframes/mobile_v1.sketch',            15728640, 'cloud',   '개발팀 전달',                        'approved', 1, '2024-09-10 14:00:00', '2024-09-10 11:00:00'),
(5,  'sales.jung', 4, '계약서_B기업_초안.docx',                 '/sales/contracts/company_b_draft.docx',           256000,   'print',   '법무팀 검토용 인쇄',                 'pending',  NULL, NULL,                '2024-09-10 15:30:00'),
(7,  'mgmt.oh',    6, '예산_집행_현황_Q3.xlsx',                 '/mgmt/budget/q3_execution.xlsx',                  184320,   'email',   'CFO 보고',                           'approved', 1, '2024-09-10 17:00:00', '2024-09-10 16:00:00'),
(2,  'dev.lee',    1, 'CI_CD_파이프라인_설계.pdf',              '/docs/devops/cicd_pipeline_design.pdf',           1572864,  'email',   'DevOps 팀 리뷰',                     'approved', 1, '2024-09-11 09:00:00', '2024-09-11 08:00:00'),
(10, 'plan.kang',  2, '벤치마킹_리포트.pptx',                   '/planning/benchmark/report_2024.pptx',           3670016,  'usb',     '임원 보고',                          'approved', 6, '2024-09-11 14:00:00', '2024-09-11 10:30:00'),
(9,  'dev.seo',    1, 'error_log_분석.txt',                     '/logs/analysis/error_log_20240911.txt',          45056,    'email',   '인프라팀 공유',                      'approved', 1, '2024-09-11 16:00:00', '2024-09-11 14:30:00'),
(3,  'plan.park',  2, 'VOC_분석_리포트_Q3.pdf',                 '/planning/voc/q3_analysis.pdf',                  2621440,  'email',   '경영진 보고',                        'approved', 6, '2024-09-12 09:00:00', '2024-09-11 17:00:00'),
(8,  'qa.yoon',    7, '버그_리포트_sprint23.xlsx',              '/testing/bugs/sprint23_report.xlsx',              133120,   'email',   '개발팀 전달',                        'approved', 1, '2024-09-12 10:30:00', '2024-09-12 09:00:00'),
(4,  'design.choi',3, '프로토타입_데모영상.mp4',                '/design/prototype/demo_video.mp4',                31457280, 'cloud',   '고객사 데모',                        'pending',  NULL, NULL,                '2024-09-12 14:00:00'),
(6,  'hr.han',     5, '복리후생_안내문.pdf',                    '/hr/benefits/guide_2024.pdf',                     512000,   'print',   '신규 입사자 배포',                   'approved', 7, '2024-09-12 16:00:00', '2024-09-12 15:00:00'),
(2,  'dev.lee',    1, 'migration_script_v2.sql',                '/database/migrations/v2_schema.sql',              16384,    'email',   'DBA 리뷰',                           'approved', 1, '2024-09-13 09:00:00', '2024-09-12 17:30:00'),
(5,  'sales.jung', 4, '매출_분석_대시보드.xlsx',                '/sales/analytics/dashboard_data.xlsx',            368640,   'email',   '마케팅팀 공유',                      'approved', 7, '2024-09-13 11:00:00', '2024-09-13 09:30:00'),
(9,  'dev.seo',    1, '.env.production',                        '/projects/main/.env.production',                  1024,     'cloud',   '배포용',                             'rejected', 1, '2024-09-13 12:00:00', '2024-09-13 10:00:00'),
(7,  'mgmt.oh',    6, '이사회_보고자료.pptx',                   '/mgmt/board/board_meeting_q3.pptx',              5242880,  'usb',     '이사회 발표',                        'approved', 1, '2024-09-13 16:00:00', '2024-09-13 14:00:00'),
(3,  'plan.park',  2, '기능_명세서_v5.docx',                    '/planning/specs/feature_spec_v5.docx',           819200,   'email',   '개발팀 전달',                        'approved', 6, '2024-09-14 09:00:00', '2024-09-13 17:30:00'),
(2,  'dev.lee',    1, '보안_취약점_스캔_결과.pdf',              '/security/scan/vulnerability_report.pdf',         4718592,  'email',   '보안팀 리뷰',                        'approved', 1, '2024-09-14 11:00:00', '2024-09-14 09:00:00'),
(10, 'plan.kang',  2, '시장조사_보고서_2024.pdf',               '/planning/market/research_2024.pdf',              3145728,  'cloud',   '투자자 미팅',                        'pending',  NULL, NULL,                '2024-09-14 14:00:00'),
(4,  'design.choi',3, '디자인_시스템_컴포넌트.zip',             '/design/system/components_v2.zip',                20971520, 'cloud',   '프론트엔드팀 전달',                  'approved', 1, '2024-09-14 16:00:00', '2024-09-14 13:00:00'),
(8,  'qa.yoon',    7, '릴리즈_체크리스트.xlsx',                 '/testing/release/checklist_v2.xlsx',              92160,    'email',   '릴리즈 매니저 전달',                 'approved', 1, '2024-09-15 09:00:00', '2024-09-14 17:00:00'),
(5,  'sales.jung', 4, '파트너십_계약서.pdf',                    '/sales/contracts/partnership_agreement.pdf',      1048576,  'print',   '법무팀 날인',                        'approved', 7, '2024-09-15 11:00:00', '2024-09-15 09:30:00'),
(2,  'dev.lee',    1, 'k8s_deployment.yaml',                    '/infra/k8s/deployment.yaml',                      6144,    'cloud',   '인프라팀 리뷰',                      'approved', 1, '2024-09-15 14:00:00', '2024-09-15 11:00:00'),
(6,  'hr.han',     5, '교육_이수_현황.xlsx',                    '/hr/training/completion_status.xlsx',             143360,   'email',   '팀장 보고',                          'approved', 7, '2024-09-15 16:00:00', '2024-09-15 14:30:00'),
(9,  'dev.seo',    1, 'API_응답시간_모니터링.csv',              '/monitoring/api_response_times.csv',              409600,   'email',   '성능 분석',                          'approved', 1, '2024-09-15 17:30:00', '2024-09-15 16:00:00'),
(3,  'plan.park',  2, '스프린트_회고록_sprint24.docx',          '/planning/retro/sprint24_retro.docx',            122880,   'email',   '팀 전체 공유',                       'approved', 6, '2024-09-16 09:00:00', '2024-09-15 18:00:00');
