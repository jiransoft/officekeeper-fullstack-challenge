<?php
/**
 * CI3 PHP 8.2+ 호환 패치
 *
 * CodeIgniter 3.1.13은 PHP 8.2의 동적 프로퍼티 deprecation에 대응하지 않습니다.
 * 이 스크립트는 system/ 디렉터리의 클래스에 #[\AllowDynamicProperties] 어트리뷰트를 추가합니다.
 */

$systemDir = dirname(__DIR__) . '/system';

if (!is_dir($systemDir)) {
    echo "system/ 디렉터리가 없습니다. composer install을 먼저 실행하세요.\n";
    exit(1);
}

$count = 0;
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($systemDir, RecursiveDirectoryIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if ($file->getExtension() !== 'php') {
        continue;
    }

    $content = file_get_contents($file->getPathname());

    // 이미 패치된 파일은 건너뜀
    if (str_contains($content, '#[\AllowDynamicProperties]')) {
        continue;
    }

    // class 선언 앞에 어트리뷰트 추가 (abstract class, final class 포함)
    $patched = preg_replace(
        '/^((?:abstract\s+|final\s+)?class\s+\w+)/m',
        "#[\\AllowDynamicProperties]\n$1",
        $content,
        -1,
        $replaced
    );

    if ($replaced > 0 && $patched !== $content) {
        file_put_contents($file->getPathname(), $patched);
        $count += $replaced;
    }
}

echo "CI3 PHP 8.2+ 패치 완료: {$count}개 클래스에 #[\\AllowDynamicProperties] 추가\n";
