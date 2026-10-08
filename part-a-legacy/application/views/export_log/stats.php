<div class="card">
    <h2 class="mb-2">부서별 반출 통계</h2>

    <?php if (empty($stats)): ?>
        <p class="text-muted mt-3">통계 데이터가 없습니다.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>순위</th>
                    <th>부서</th>
                    <th>반출 건수</th>
                    <th>총 파일 크기</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats as $index => $stat): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $stat['department'] ?></td>
                    <td><?= number_format($stat['count']) ?>건</td>
                    <td><?= number_format($stat['total_size'] / 1024 / 1024, 2) ?> MB</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="mt-3">
        <a href="/export_log" class="btn btn-secondary">목록으로</a>
    </div>
</div>
