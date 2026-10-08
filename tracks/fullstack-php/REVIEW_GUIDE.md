# FullStack PHP 채용 과제 — 리뷰어 가이드

> 대상: 지원자 제출물을 평가하는 리뷰어
> 기준: [`README.md`](../../README.md) (지원자용 PRD) + 이 가이드
> 평가 방식: Claude + Codex **2인 독립 리뷰 + 토의 합의**

---

## 0. TL;DR

- 이 과제는 **PHP FullStack(CI3+Laravel+Docker)** 역량을 4파트로 평가한다.
- AI 도구 사용은 허용이며, **"AI를 썼는가"는 평가 축이 아니다**.
- 평가 축은 3트랙:
  - **Track A — 산출물 (100점)**: 합/불 게이트
  - **Track B — Git 위생**: 보조 시그널 (단독 탈락 불가)
  - **Track C — 협업 산출물·AI 활용 (25점)**: FIXES.md + DECISIONS.md + AI_USAGE.md
- **채점 전 반드시 Phase 0 게이트를 통과시킨다.**

---

## 1. Phase 0 — 채점 착수 전 게이트

### 1.1 168시간 창 확정

```bash
cd candidates/<id>
git log --reverse --date=iso-strict --format='%h | %cI | %s'
```

1. T0 = 지원자의 첫 커밋 (template 생성 커밋 제외)
2. 창 종료 = T0 + 168h (하드 마감 초과 시 하드 마감에서 자름)
3. 채점 대상 커밋 = 창 종료 이전 마지막 커밋 → `git checkout --detach`

### 1.2 실행 검증 (필수)

```bash
cd candidates/<id>
docker compose up -d 2>&1 | tee /tmp/boot_<id>.log

# 검증 체크리스트:
# [ ] docker compose up 성공
# [ ] Part A: http://localhost:8080 접속 → 반출 로그 목록 표시
# [ ] Part B: http://localhost:8000 접속 → 승인 요청 화면
# [ ] MySQL 시드 데이터 로드 확인
```

**실행 불가 → 즉시 불합격 (채점 생략)**

---

## 2. Track A — 산출물 Rubric (100점)

### 2.1 Part A: 레거시 유지보수 (30점)

| 항목 | 배점 | 평가 관점 |
|------|------|----------|
| SQL Injection 수정 | 7 | 식별 3 + 올바른 수정 3 + FIXES.md 근거 1 |
| XSS 수정 (Reflected + Stored) | 6 | $_GET 수정 2 + DB 데이터 수정 2 + 근거 2 |
| N+1 Query 수정 | 6 | 식별 2 + JOIN/IN 구현 3 + 근거 1 |
| PHP 8.0 호환 (each) | 4 | 식별 1 + 수정 2 + 근거 1 |
| PHP 8.2 호환 (utf8_encode/동적 프로퍼티) | 3 | 식별 1 + 수정 1.5 + 근거 0.5 |
| CSRF 수정 | 4 | config 수정 1 + 폼 수정 2 + 근거 1 |

**즉시 탈락**: SQL Injection을 발견하지 못한 경우 → **불합격**

**보너스** (+α): 위 6개 외 추가 발견 (에러 핸들링, 로깅 부재, 입력 유효성 등)

정답 상세: [`ANSWER_KEY.md`](./ANSWER_KEY.md) 참조

### 2.2 Part B: 신규 기능·API (30점)

| 항목 | 배점 | 평가 관점 |
|------|------|----------|
| REST API 설계 | 10 | URL 구조, HTTP 메서드, 상태 코드, 유효성 검증, 에러 응답 일관성 |
| Laravel 활용 | 8 | Eloquent 관계, Form Request, Resource, Middleware, 마이그레이션 |
| jQuery 화면 | 5 | 기능 완성도, 시안 반영도, UX |
| DECISIONS.md 품질 | 7 | 모호한 요구사항 식별 수, 의사결정의 합리성, 가정의 명확성 |

**모호한 요구사항 대응 체크**:

| 기획서 모호 표현 | 체크할 것 |
|-----------------|----------|
| "적절한 인증/인가 처리" | 역할 구분을 어떻게 설계했는가 |
| "대용량 파일 요청에 대한 고려" | 임계값 정의 여부, 별도 처리 유무 |
| "승인 이력을 관리" | 이력 테이블 설계, 보존 범위 |
| "알림 기능 고려" | 구현 범위 결정의 합리성 |

### 2.3 Part C: Docker (15점)

| 항목 | 배점 | 평가 관점 |
|------|------|----------|
| 원커맨드 실행 | 6 | `docker compose up` 동작 여부 (Phase 0 게이트) |
| Dockerfile 품질 | 4 | 레이어 캐싱, 멀티스테이지, 불필요 파일 제외 |
| 환경 설정 | 3 | 환경 변수 분리, depends_on/healthcheck |
| 시드 데이터 | 2 | 자동 적용, 데이터 일관성 |

### 2.4 Part D: 가산점 (+α, 별도)

| 관점 | 체크할 것 |
|------|----------|
| 기술 스택 기본기 | Kotlin/Spring Boot 또는 React 코드 품질 |
| Part B와의 일관성 | 같은 API 스펙, 동일 기능 |
| Docker 통합 | docker-compose에 추가 서비스 등록 여부 |

---

## 3. Track B — Git 위생 (보조)

**단독 탈락 사유 아님.** Track A 경계선에서만 참조.

### Green Flag
- 3일 이상에 커밋 분산
- 의미 있는 커밋 메시지 (`fix:`, `feat:`, `refactor:`)
- Part A/B/C 별로 분리된 커밋

### Red Flag
- 전 커밋이 마감 ±2시간에 몰림
- 커밋 1~2개 (`initial commit`만)
- 작성자 identity 불일치 (대리 제출 의심)

### False Positive (Red Flag로 보지 말 것)
- AI 도구 사용 흔적 (PRD가 허용)
- Conventional Commits 형식
- 커밋 수가 많거나 적음

---

## 4. Track C — 협업 산출물·AI 활용 (25점)

| 산출물 | 배점 | 평가 관점 |
|--------|------|----------|
| **FIXES.md** | *(Part A 30점에 포함)* | 문제 분석 깊이, 근거 정확성, 참고 자료 |
| **DECISIONS.md** | 15 | 모호한 요구사항 식별 수, 의사결정 합리성, trade-off 서술 |
| **AI_USAGE.md** | 10 | 활용의 효과성, 검증의 성실성, AI 제안 수정 사례 |

### AI_USAGE.md 평가 기준

| 점수 | 기준 |
|------|------|
| 0~2 | 미작성 또는 "사용 안 함"만 기재 |
| 3~5 | 도구·용도 기재, 검증 과정 미흡 |
| 6~8 | 작업별 활용 상세, 검증 과정 기재, AI 제안 수정 사례 포함 |
| 9~10 | 위 + 비판적 수용 사례, AI 한계 인식, 학습 점 기재 |

---

## 5. 종합 판정

```
Phase 0 게이트
  ├─ 실행 불가 → 불합격
  └─ 통과
      ↓
Track A 채점
  ├─ 불합격 기준 해당 → 불합격
  ├─ ≥ 80점 → 합격 (Track C로 우선순위)
  ├─ 65~79점 → Track C 검토
  │     ├─ DECISIONS + AI_USAGE 강함 → 합격 경향
  │     └─ 약함 → 면접에서 재검증
  └─ < 65점 → 불합격 경향
```

### 판정 기록 템플릿

```markdown
## 후보: <id>

### Phase 0 게이트
- T0: `<sha>` <날짜>
- 채점 대상 커밋: `<sha>` <날짜>
- 실행 검증: [ ] 성공 [ ] 실패

### Track A (100점)
- Part A 레거시: __/30 — <근거>
- Part B 신규:   __/30 — <근거>
- Part C Docker: __/15 — <근거>
- **소계: __/75**

### Track C (25점)
- DECISIONS.md: __/15 — <근거>
- AI_USAGE.md:  __/10 — <근거>
- **소계: __/25**

### Track B (참고)
- Green/Red flags: <목록>

### Part D 가산점
- 구현 여부: [ ] 없음 [ ] Kotlin [ ] React
- 평가: <코멘트>

### 종합
- **총점: __/100 + Track C __/25 + 가산점**
- **판정**: [ ] 합격 [ ] 조건부(면접) [ ] 불합격
- **면접 질문 제안**:
  1.
  2.
  3.
```

---

## 6. 2인 독립 리뷰 프로토콜

1. **앵커링 방지**: Codex에게 Claude 점수를 먼저 노출하지 말 것
2. **동일 사료 원칙**: 동일 커밋 + 동일 실행 로그로 채점
3. **합의는 평균이 아님**: 근거가 강한 쪽 채택
4. **Codex 호출은 stdin 파이프**:
   ```bash
   cat /tmp/prompt.md | codex exec --sandbox read-only 2>&1 | tee /tmp/codex_out.log
   ```
5. Δ > 5점 또는 판정 불일치 시 최대 2라운드 토의
6. 합의 실패 → "지속 이견" 명시 + 매니저 결정

---

_이 가이드는 jiransoft/ai-fullstack-developer-hiring의 평가 프레임워크를 기반으로 작성되었습니다._
