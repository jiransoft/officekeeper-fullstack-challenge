# 오피스키퍼 웹개발팀 — FullStack Engineer(PHP) 개발 과제

오피스키퍼 웹개발팀에 합류할 **FullStack Engineer(PHP)** 를 위한 개발 과제입니다.

> **도메인**: 이 과제는 가상의 "DLP 관리 콘솔" 미니 버전을 다룹니다. 실제 오피스키퍼 소스와는 **무관**합니다.
>
> **AI 도구 사용**: 적극 허용합니다. 다만 `AI_USAGE.md`에 활용 내역을 기록해 주세요.

---

## 과제 시작

1. 이 레포를 **"Use this template"** → 본인 계정 **Private 레포**로 생성
2. **`serithemage`를 Collaborator로 초대** (첫 커밋 전에 보내주세요 — 이것이 과제 시작 신고입니다)
3. 아래 과제를 구현하고, 마감 전 최종 커밋을 push

---

## 작업 시간 규칙

| 항목 | 설명 |
|------|------|
| **권장 소요** | 15~20시간 |
| **작업 창** | 첫 커밋부터 **168시간(7일)** 이내 커밋만 평가 대상 |
| **하드 마감** | 채용 담당자가 별도 안내 |
| **창 밖 커밋** | 채점에서 제외됩니다 (README 정리·오탈자 포함) |

착수 시점은 자유입니다. 창을 다 쓰지 않아도 감점 없습니다.

---

## 과제 구성

### Part A — 레거시 유지보수 (필수, 30%)

`part-a-legacy/` 에 CodeIgniter 3 스타일로 작성된 **파일 반출 로그 조회** 화면이 있습니다.
이 코드에는 **보안 취약점, 성능 문제, PHP 8 비호환 코드**가 의도적으로 포함되어 있습니다.

**할 일**:
1. 코드를 리뷰하고 문제점을 찾아 수정하세요
2. 수정한 내용을 **`FIXES.md`** 에 기록하세요 (템플릿 제공)

**평가 관점**: 보안 감각, PHP 깊이, 코드 리딩 능력, 수정 근거의 명확성

### Part B — 신규 기능 개발 (필수, 25%)

`part-b-feature/` 에 최소한의 시작점(모델 3개, 라우트, `composer.json`)이 제공됩니다.
**Laravel 프로젝트 초기 셋업(`artisan`, `bootstrap/`, `config/` 등)은 지원자가 직접 구성해야 합니다.**
JIRA 티켓 형식의 기획서 [`docs/TICKET-DLP-1024.md`](docs/TICKET-DLP-1024.md)를 읽고 **파일 반출 승인 요청** 기능을 구현하세요.

**할 일**:
1. REST API 구현 (Laravel)
2. 화면 구현 (jQuery)
3. DB 마이그레이션 작성
4. 기획서에서 모호한 부분에 대한 결정을 **`DECISIONS.md`** 에 기록

**참고 자료**:
- 기획서: [`docs/TICKET-DLP-1024.md`](docs/TICKET-DLP-1024.md)
- 시안: [`docs/mockup-export-approval.svg`](docs/mockup-export-approval.svg)
- DB 스키마: [`docs/database-schema.md`](docs/database-schema.md)

**평가 관점**: REST API 설계, Laravel 활용도, jQuery 구현, Feature 테스트, 의사결정 능력

### Part C — Docker (필수, 10%)

`docker compose up` **한 번**으로 전체 환경이 실행되도록 구성하세요.

- `docker-compose.yml` 스켈레톤이 제공됩니다 (주석 처리된 부분을 완성)
- Part A(CI3 앱) + Part B(Laravel 앱) + MySQL이 모두 기동
- 시드 데이터(`mysql/init.sql`)가 자동 적용

**평가 관점**: Docker 실행 여부(합/불 게이트), Dockerfile 품질, 환경 설정

### Part D — 5.0 전환 역량 (택1 필수, 10% + 가산)

오피스키퍼 5.0은 **Kotlin + React** 스택입니다. 아래 중 **하나를 선택**하여 구현하세요. 둘 다 구현 시 가산점.

- **Kotlin + Spring Boot**: Part B API 일부를 Kotlin으로 구현
- **React / Next.js**: Part B 화면을 React로 구현

상세: [`part-d/README.md`](part-d/README.md)

> 완벽한 구현보다 **기본기 확인**이 목적입니다. 핵심 기능이 동작하면 충분합니다.

---

## 제출 산출물

| 파일 | 내용 | 필수 |
|------|------|:----:|
| **Part A~C 코드** | 위 과제 구현 | O |
| **Part D 코드** | Kotlin API 또는 React 화면 (택1, 둘 다 시 가산) | O |
| **`FIXES.md`** | Part A 수정 내역 및 근거 | O |
| **`DECISIONS.md`** | Part B 설계 결정 및 가정 | O |
| **`AI_USAGE.md`** | AI 도구 사용 내역 | O |

---

## 평가 기준

| 영역 | 비중 | 주요 관점 |
|------|-----:|----------|
| **Part A: 레거시 유지보수** | 30% | 보안 취약점 식별, PHP 깊이, 성능 인식 |
| **Part B: 신규 기능·API** | 25% | REST 설계, Laravel 활용, jQuery, Feature 테스트 |
| **Part C: Docker** | 10% | `docker compose up` 실행 여부, 구성 품질 |
| **Part D: 5.0 전환 (택1)** | 10% | Kotlin 또는 React 기본기 (둘 다 시 가산) |
| **협업 산출물** | 13% | DECISIONS.md 품질 (FIXES.md는 Part A에 포함) |
| **AI 활용** | 12% | AI_USAGE.md, 효과적 활용과 검증 |

### 불합격 기준

아래에 해당하면 다른 점수와 무관하게 **불합격**입니다:

- `docker compose up`으로 **실행 불가**
- Part A에서 SQL Injection을 **발견하지 못함**
- Part B REST API가 **전혀 동작하지 않음**
- `FIXES.md`, `DECISIONS.md` **미작성**
- 기존 오픈소스 프로젝트를 **통째로 복사**

---

## 환경 설정

### 사전 요구사항

- Docker & Docker Compose
- PHP 8.3+ (로컬 개발 시)
- Composer
- Git

### 실행 방법

```bash
# 1. 레포 클론
git clone <your-private-repo-url>
cd <repo-name>

# 2. 환경 변수
cp .env.example .env

# 3. 실행
docker compose up

# Part A: http://localhost:8080
# Part B: http://localhost:8000
```

### 테스트 계정

| 역할 | 로그인 ID | 비밀번호 |
|------|-----------|----------|
| 관리자 | admin | password123 |

---

## AI 도구 사용 정책

AI 도구(Claude, ChatGPT, GitHub Copilot 등)의 사용을 **적극 허용**합니다.
다만 `AI_USAGE.md`에 다음을 기록해 주세요:

1. **어떤 도구**를 **어떤 작업**에 사용했는지
2. AI가 생성한 코드를 **어떻게 검증**했는지
3. AI 제안을 **수정한 경우** 그 이유

**"AI를 썼다"는 사실 자체는 감점이 아닙니다.** 얼마나 효과적으로 활용하고 검증했는지를 봅니다.

---

## FAQ

**Q. 실제 오피스키퍼 소스를 참고해도 되나요?**
A. 이 과제는 실제 오피스키퍼 소스와 **무관**합니다. 참고하지 마세요.

**Q. Part A에서 버그를 몇 개 찾아야 하나요?**
A. 개수를 공개하지는 않습니다. 가능한 한 많이 찾아주세요.

**Q. Part B 기획서에 모호한 부분이 있는데요?**
A. 의도입니다. 본인이 합리적으로 판단하고 `DECISIONS.md`에 기록하세요. 정답은 없습니다.

**Q. 15~20시간 안에 다 못 하면요?**
A. 부분 구현도 평가 대상입니다. 완성도보다 **코드 품질과 의사결정 과정**을 더 중요하게 봅니다.

**Q. 커밋은 어떻게 하면 되나요?**
A. 작업 단위로 의미 있는 커밋을 남겨주세요. 커밋 히스토리도 참고합니다.

**Q. Dependabot 보안 경고가 뜨는데요?**
A. 의도적입니다. 이 과제는 레거시 프레임워크(CodeIgniter 3)를 사용하며, 프레임워크 자체의 취약점 경고는 무시해도 됩니다. **애플리케이션 코드 수준의 보안 문제**에 집중하세요.

---

_이 과제는 오피스키퍼 웹개발팀의 실제 업무 환경을 반영하여 설계되었습니다._
