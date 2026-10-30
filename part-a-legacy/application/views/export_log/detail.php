<div class="card">
    <h2 class="mb-2">반출 로그 상세</h2>

    <table>
        <tr>
            <th style="width: 150px;">로그 ID</th>
            <td><?= $log->id ?></td>
        </tr>
        <tr>
            <th>사용자</th>
            <td><?= isset($log->user) ? $log->user->name : '-' ?> (<?= isset($log->user) ? $log->user->email : '' ?>)</td>
        </tr>
        <tr>
            <th>부서</th>
            <td><?= isset($log->department) ? $log->department->name : '-' ?></td>
        </tr>
        <tr>
            <th>파일명</th>
            <td><?= $log->file_name ?></td>
        </tr>
        <tr>
            <th>파일 경로</th>
            <td><?= $log->file_path ?></td>
        </tr>
        <tr>
            <th>파일 크기</th>
            <td><?= number_format($log->file_size / 1024, 1) ?> KB</td>
        </tr>
        <tr>
            <th>반출 사유</th>
            <td><?= $log->reason ?></td>
        </tr>
        <tr>
            <th>반출 방법</th>
            <td><?= $log->export_method ?></td>
        </tr>
        <tr>
            <th>상태</th>
            <td>
                <?php if ($log->status === 'approved'): ?>
                    <span class="badge badge-success">승인</span>
                <?php elseif ($log->status === 'pending'): ?>
                    <span class="badge badge-warning">대기</span>
                <?php else: ?>
                    <span class="badge badge-danger">반려</span>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th>반출일시</th>
            <td><?= $log->created_at ?></td>
        </tr>
    </table>

    <div class="mt-3">
        <a href="/export_log" class="btn btn-secondary">목록으로</a>
        <form method="POST" action="/export_log/delete" style="display:inline;">
            <input type="hidden" name="log_id" value="<?= $log->id ?>">
            <button type="submit" class="btn btn-danger"
                    onclick="return confirm('정말 삭제하시겠습니까?');">삭제</button>
        </form>
    </div>
</div>
