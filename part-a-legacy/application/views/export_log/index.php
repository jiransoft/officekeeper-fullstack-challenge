<div class="card">
    <h2 class="mb-2">파일 반출 로그</h2>

    <!-- 검색 폼 -->
    <form class="search-form" method="GET" action="/export_log/search">
        <select name="type">
            <option value="user">사용자</option>
            <option value="keyword">파일명/사유</option>
        </select>
        <input type="text" name="keyword" placeholder="검색어를 입력하세요"
               value="<?= isset($keyword) ? $keyword : '' ?>">
        <button type="submit" class="btn btn-primary">검색</button>
        <a href="/export_log" class="btn btn-secondary">초기화</a>
    </form>

    <?php if (isset($keyword) && $keyword): ?>
        <p class="text-muted mb-2">
            검색 결과: "<strong><?= $keyword ?></strong>" (<?= $total ?>건)
        </p>
    <?php endif; ?>

    <?php if (empty($logs)): ?>
        <p class="text-muted mt-3">조회된 반출 로그가 없습니다.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>번호</th>
                    <th>사용자</th>
                    <th>부서</th>
                    <th>파일명</th>
                    <th>파일크기</th>
                    <th>반출사유</th>
                    <th>반출일시</th>
                    <th>상태</th>
                    <th>관리</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $index => $log): ?>
                <tr>
                    <td><?= $log->id ?></td>
                    <td><?= isset($log->user) ? $log->user->name : '-' ?></td>
                    <td><?= isset($log->department) ? $log->department->name : '-' ?></td>
                    <td><?= $log->file_name ?></td>
                    <td><?= number_format($log->file_size / 1024, 1) ?> KB</td>
                    <td><?= $log->reason ?></td>
                    <td><?= $log->created_at ?></td>
                    <td>
                        <?php if ($log->status === 'approved'): ?>
                            <span class="badge badge-success">승인</span>
                        <?php elseif ($log->status === 'pending'): ?>
                            <span class="badge badge-warning">대기</span>
                        <?php else: ?>
                            <span class="badge badge-danger">반려</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/export_log/detail/<?= $log->id ?>" class="btn btn-secondary">상세</a>
                        <form method="POST" action="/export_log/delete" style="display:inline;">
                            <input type="hidden" name="log_id" value="<?= $log->id ?>">
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('정말 삭제하시겠습니까?');">삭제</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- 페이지네이션 -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($i == $page): ?>
                    <span class="active"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<div class="text-right mt-3">
    <span class="text-muted">총 <?= $total ?>건</span>
</div>
