<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> — DLP 관리 콘솔</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Malgun Gothic', 'Apple SD Gothic Neo', sans-serif; background: #f5f5f5; color: #333; }
        .header { background: #1a237e; color: #fff; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 18px; font-weight: 600; }
        .header .nav a { color: #bbdefb; text-decoration: none; margin-left: 20px; font-size: 14px; }
        .header .nav a:hover { color: #fff; }
        .container { max-width: 1200px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.12); padding: 24px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        table th { background: #e8eaf6; padding: 10px 12px; text-align: left; font-weight: 600; border-bottom: 2px solid #c5cae9; }
        table td { padding: 10px 12px; border-bottom: 1px solid #eee; }
        table tr:hover { background: #f5f5f5; }
        .btn { display: inline-block; padding: 6px 14px; border-radius: 4px; font-size: 13px; cursor: pointer; text-decoration: none; border: none; }
        .btn-primary { background: #1a237e; color: #fff; }
        .btn-danger { background: #c62828; color: #fff; }
        .btn-secondary { background: #757575; color: #fff; }
        .btn:hover { opacity: 0.85; }
        .search-form { display: flex; gap: 8px; margin-bottom: 16px; }
        .search-form input, .search-form select { padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .search-form input[type="text"] { flex: 1; }
        .pagination { display: flex; gap: 4px; justify-content: center; margin-top: 20px; }
        .pagination a, .pagination span { padding: 6px 12px; border: 1px solid #ccc; border-radius: 4px; text-decoration: none; color: #333; font-size: 13px; }
        .pagination span.active { background: #1a237e; color: #fff; border-color: #1a237e; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #e8f5e9; color: #2e7d32; }
        .badge-warning { background: #fff3e0; color: #e65100; }
        .badge-danger { background: #ffebee; color: #c62828; }
        .text-muted { color: #999; font-size: 13px; }
        .mb-2 { margin-bottom: 8px; }
        .mt-3 { margin-top: 16px; }
        .text-right { text-align: right; }
        .footer { text-align: center; padding: 24px; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DLP 관리 콘솔</h1>
        <div class="nav">
            <a href="/export_log">반출 로그</a>
            <a href="/export_log/stats">통계</a>
            <a href="/export_log/export_csv">CSV 내보내기</a>
        </div>
    </div>

    <div class="container">
        <?= $content ?>
    </div>

    <div class="footer">
        &copy; 2024 DLP Console v1.0 — 이 시스템은 채용 과제용 가상 애플리케이션입니다.
    </div>
</body>
</html>
