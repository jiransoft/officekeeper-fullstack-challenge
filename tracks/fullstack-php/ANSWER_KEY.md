# Part A — 정답 및 기대 수정 (면접관용)

> 이 문서는 **비공개**입니다. 지원자에게 노출되지 않도록 주의하세요.

---

## Bug #1: SQL Injection (7점)

### 위치
`part-a-legacy/application/models/Export_log_model.php` — `get_logs_by_user()` 메서드

### 심어진 코드
```php
public function get_logs_by_user($username)
{
    $query = $this->db->query(
        "SELECT * FROM export_logs WHERE user_name = '" . $username . "' ORDER BY created_at DESC"
    );
    return $query->result();
}
```

### 문제
사용자 입력 `$username`이 이스케이프 없이 SQL 쿼리에 직접 연결됨.
`' OR '1'='1` 입력 시 모든 로그 노출, `'; DROP TABLE export_logs; --` 로 테이블 삭제 가능.

### 기대 수정

**방법 1: Query Binding (권장)**
```php
$query = $this->db->query(
    "SELECT * FROM export_logs WHERE user_name = ? ORDER BY created_at DESC",
    array($username)
);
```

**방법 2: Active Record**
```php
$this->db->where('user_name', $username);
$this->db->order_by('created_at', 'DESC');
$query = $this->db->get('export_logs');
```

### 채점
- 식별: 3점
- 올바른 수정: 3점 (Query Binding 또는 Active Record)
- FIXES.md 근거: 1점

### 미발견 시
**불합격 기준 해당.** 4년차 PHP 개발자가 SQL Injection을 발견하지 못하면 보안 인식 심각한 결여.

---

## Bug #2: XSS — Reflected + Stored (6점)

### 위치
`part-a-legacy/application/views/export_log/index.php`

### 심어진 코드

**(a) Reflected XSS** — 검색어 출력:
```php
<p class="text-muted mb-2">
    검색 결과: "<strong><?= $keyword ?></strong>" (<?= $total ?>건)
</p>
```

**(b) Stored XSS** — DB 데이터 출력:
```php
<td><?= $log->file_name ?></td>
<td><?= $log->reason ?></td>
```

### 기대 수정
```php
<!-- (a) -->
<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>

<!-- (b) -->
<td><?= htmlspecialchars($log->file_name, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($log->reason, ENT_QUOTES, 'UTF-8') ?></td>
```

### 채점
- $_GET 입력 수정: 2점
- DB 데이터 출력 수정: 2점
- FIXES.md 근거: 2점
- `detail.php`에서도 동일 패턴 수정하면 가산

---

## Bug #3: N+1 Query (6점)

### 위치
`part-a-legacy/application/controllers/Export_log.php` — `index()`, `search()` 메서드

### 심어진 코드
```php
$logs = $this->export_log_model->get_all_logs($per_page, $offset);
foreach ($logs as $log) {
    $log->user = $this->user_model->get_user_by_id($log->user_id);
    $log->department = $this->department_model->get_department($log->dept_id);
}
```

### 문제
로그 N건 → 쿼리 2N+1회. 20건이면 41회, 100건이면 201회.

### 기대 수정

**방법 1: JOIN 쿼리 (권장)**
```php
public function get_all_logs_with_relations($limit, $offset)
{
    $this->db->select('export_logs.*, users.name as user_name_display, departments.name as dept_name');
    $this->db->join('users', 'users.id = export_logs.user_id', 'left');
    $this->db->join('departments', 'departments.id = export_logs.dept_id', 'left');
    $this->db->order_by('export_logs.created_at', 'DESC');
    $this->db->limit($limit, $offset);
    return $this->db->get('export_logs')->result();
}
```

**방법 2: WHERE IN 일괄 조회**
```php
$user_ids = array_unique(array_column($logs, 'user_id'));
$users = $this->user_model->get_users_by_ids($user_ids);
// 매핑...
```

### 채점
- 식별: 2점
- JOIN 또는 IN 구현: 3점
- FIXES.md 근거: 1점

---

## Bug #4: PHP 8.0 비호환 — each() (4점)

### 위치
`part-a-legacy/application/controllers/Export_log.php` — `stats()` 메서드

### 심어진 코드
```php
reset($logs_by_dept);
while ($item = each($logs_by_dept)) {
    $dept = $this->department_model->get_department($item['key']);
    // ...
}
```

### 문제
`each()`는 PHP 7.2에서 deprecated, **PHP 8.0에서 제거됨** (Fatal Error).

### 기대 수정
```php
foreach ($logs_by_dept as $dept_id => $dept_logs) {
    $dept = $this->department_model->get_department($dept_id);
    $dept_name = $dept ? $dept->name : '알 수 없음';
    $stats[] = array(
        'department' => $dept_name,
        'count'      => count($dept_logs),
        'total_size' => array_sum(array_column($dept_logs, 'file_size')),
    );
}
```

### 채점
- 식별: 1점
- 올바른 수정: 2점
- FIXES.md 근거: 1점

---

## Bug #5: PHP 8.2 비호환 — utf8_encode() + 동적 프로퍼티 (3점)

### 위치
`part-a-legacy/application/models/Export_log_model.php` — `export_csv()` 메서드

### 심어진 코드
```php
foreach ($logs as $log) {
    $log->file_name_encoded = utf8_encode($log->file_name);
    // ...
}
```

### 문제
1. `utf8_encode()`는 PHP 8.2에서 deprecated (ISO-8859-1 → UTF-8 전용, 오해의 소지)
2. stdClass 객체에 동적 프로퍼티 추가(`$log->file_name_encoded`)는 PHP 8.2에서 deprecated

### 기대 수정
```php
foreach ($logs as $log) {
    $encoded_name = mb_convert_encoding($log->file_name, 'UTF-8', 'auto');
    // 동적 프로퍼티 대신 지역 변수 또는 배열 사용
    $output .= sprintf("...", $encoded_name, ...);
}
```

### 채점
- 식별: 1점
- 수정: 1.5점
- 근거: 0.5점

### 참고
이 버그는 **우대 수준**. PHP 8.2를 경험하지 않은 지원자는 발견하지 못할 수 있으며, 미발견 시 감점은 적음.

---

## Bug #6: CSRF 보호 미적용 (4점)

### 위치
1. `part-a-legacy/application/config/config.php` — `$config['csrf_protection'] = FALSE;`
2. `part-a-legacy/application/views/export_log/index.php` — 삭제 폼에 CSRF 토큰 없음
3. `part-a-legacy/application/views/export_log/detail.php` — 동일

### 기대 수정

**(a) config.php:**
```php
$config['csrf_protection'] = TRUE;
```

**(b) 뷰 파일 폼:**
```php
<form method="POST" action="/export_log/delete">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
           value="<?= $this->security->get_csrf_hash() ?>">
    <input type="hidden" name="log_id" value="<?= $log->id ?>">
    <button type="submit" class="btn btn-danger">삭제</button>
</form>
```

### 채점
- config 수정: 1점
- 폼에 토큰 필드 추가: 2점
- FIXES.md 근거: 1점

---

## 추가 발견 가능 항목 (보너스)

지원자가 위 6개 외에 추가로 지적할 수 있는 사항:

| 항목 | 설명 |
|------|------|
| 에러 핸들링 부재 | DB 쿼리 실패 시 예외 처리 없음 |
| 로깅 부재 | 보안 이벤트(삭제 등) 로깅 없음 |
| 삭제 권한 체크 | 누구나 로그를 삭제할 수 있음 (인증/인가 없음) |
| 페이지네이션 검색 | search()에서 페이지네이션 미구현 (total 부정확) |
| CSV 내보내기 보안 | CSV Injection 가능성 (수식 삽입) |
| 입력 유효성 검증 | page 파라미터 등 정수 검증 없음 |

이들은 추가 발견 시 가산점이며, 미발견 시 감점하지 않음.

---

## 면접 질문 예시

### Part A 기반
1. "SQL Injection 외에 이 코드에서 가장 심각한 보안 취약점은 무엇이라고 생각하세요?"
2. "N+1 문제를 발견했는데, 실무에서 이런 문제를 어떻게 조기에 발견하세요?"
3. "PHP 7에서 8로 마이그레이션한 경험이 있다면, 가장 까다로웠던 호환성 이슈는?"

### Part B 기반
4. "DECISIONS.md에서 가장 고민이 많았던 결정은 무엇이었나요?"
5. "인가 처리를 X 방식으로 구현하셨는데, Y 방식과 비교했을 때 장단점은?"
6. "'대용량 파일'의 기준을 X로 잡으셨는데, 실무에서는 어떤 기준을 쓰세요?"

### AI 활용 기반
7. "AI 도구가 제안한 코드 중 가장 많이 수정한 부분은 어디였나요?"
8. "AI가 틀린 제안을 한 경우가 있었나요? 어떻게 알아챘나요?"
