# Part D — 5.0 전환 역량 (필수)

오피스키퍼 5.0은 **Kotlin(Spring Boot) 백엔드 + React(Next.js) 프론트엔드**로 구성됩니다.
3.0 유지보수와 함께 5.0 개발에도 참여하게 되므로, 두 스택 모두 기본기를 확인합니다.

---

## Part D-1: Kotlin + Spring Boot (필수, 10%)

Part B에서 구현한 **파일 반출 승인 요청 API**의 일부를 Kotlin + Spring Boot로 구현하세요.

- 최소 **2개 이상의 엔드포인트** 구현 (예: 목록 조회 + 승인/반려)
- `part-d-bonus/kotlin-api/` 디렉토리에 프로젝트 생성
- Part B와 **같은 MySQL DB**를 사용
- Docker로 실행 가능하면 가산점

**평가 관점**: Kotlin 기본 문법, Spring Boot 구조 이해, REST API 일관성

## Part D-2: React 또는 Next.js (필수, 10%)

Part B에서 구현한 **jQuery 화면**을 React (또는 Next.js)로 구현하세요.

- Part B의 **Laravel API를 그대로 호출**
- `part-d-bonus/react-app/` 디렉토리에 프로젝트 생성
- 반출 요청 **목록 조회 + 승인/반려** 기능 최소 구현
- Docker로 실행 가능하면 가산점

**평가 관점**: React 컴포넌트 설계, 상태 관리, API 연동, 기본 UI 구성

---

## 평가 기준 (공통)

| 관점 | 설명 |
|------|------|
| 기술 스택 기본기 | 해당 언어/프레임워크를 기본적으로 다룰 수 있는가 |
| Part B와의 일관성 | 같은 API 스펙, 동일한 비즈니스 로직 |
| 코드 품질 | 구조, 네이밍, 에러 처리 |
| Docker 통합 | docker-compose에 서비스 추가 (가산) |

> **참고**: 완벽한 구현보다 **기본기 확인**이 목적입니다.
> Part B 수준의 완성도를 기대하지 않습니다. 핵심 기능이 동작하면 충분합니다.
