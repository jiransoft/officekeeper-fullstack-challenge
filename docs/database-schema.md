# DLP 관리 콘솔 — 데이터베이스 스키마

## 기존 테이블 (mysql/init.sql에 정의)

### departments

| 컬럼 | 타입 | 설명 |
|------|------|------|
| id | INT, PK, AUTO_INCREMENT | 부서 ID |
| name | VARCHAR(100) | 부서명 |
| code | VARCHAR(20), UNIQUE | 부서 코드 |
| created_at | TIMESTAMP | 생성일시 |
| updated_at | TIMESTAMP | 수정일시 |

### users

| 컬럼 | 타입 | 설명 |
|------|------|------|
| id | INT, PK, AUTO_INCREMENT | 사용자 ID |
| name | VARCHAR(100) | 이름 |
| email | VARCHAR(255), UNIQUE | 이메일 |
| user_name | VARCHAR(50), UNIQUE | 로그인 ID |
| password | VARCHAR(255) | 비밀번호 (bcrypt) |
| role | ENUM('admin','manager','user') | 역할 |
| dept_id | INT, FK → departments.id | 소속 부서 |
| is_active | TINYINT(1) | 활성 여부 |
| created_at | TIMESTAMP | 생성일시 |
| updated_at | TIMESTAMP | 수정일시 |

### export_logs (Part A에서 사용)

| 컬럼 | 타입 | 설명 |
|------|------|------|
| id | INT, PK, AUTO_INCREMENT | 로그 ID |
| user_id | INT, FK → users.id | 반출 사용자 |
| user_name | VARCHAR(50) | 반출 사용자 로그인 ID |
| dept_id | INT, FK → departments.id | 반출 사용자 부서 |
| file_name | VARCHAR(500) | 파일명 |
| file_path | VARCHAR(1000) | 파일 경로 |
| file_size | BIGINT | 파일 크기 (bytes) |
| export_method | ENUM('usb','email','cloud','print','network') | 반출 방법 |
| reason | TEXT | 반출 사유 |
| status | ENUM('pending','approved','rejected') | 상태 |
| reviewed_by | INT, FK → users.id | 검토자 |
| reviewed_at | TIMESTAMP | 검토일시 |
| created_at | TIMESTAMP | 생성일시 |
| updated_at | TIMESTAMP | 수정일시 |

## 시드 데이터

- 부서: 7개 (개발팀, 기획팀, 디자인팀, 영업팀, 인사팀, 경영지원팀, 품질관리팀)
- 사용자: 10명 (admin 1, manager 2, user 7)
- 반출 로그: 50건 (다양한 상태, 부서, 반출 방법)
- 테스트 로그인: `admin` / `password123` (admin 역할)

## Part B에서 추가할 테이블

지원자가 **Laravel 마이그레이션**으로 직접 설계합니다.
`TICKET-DLP-1024.md`의 요구사항을 참고하되, 상세한 스키마 설계는 지원자의 판단에 맡깁니다.
설계 결정 사항은 `DECISIONS.md`에 기록해 주세요.
