<?php
/**
 * DIAGNOSTIC FILE - Hapus setelah selesai debugging!
 * Akses: https://lpkpaiton.com/debug-upload.php
 */

echo "<html><head><title>Server Debug Info</title>";
echo "<style>body{font-family:monospace;background:#1a1a2e;color:#e0e0e0;padding:20px;} ";
echo "h2{color:#00d4ff;border-bottom:1px solid #333;padding-bottom:8px;} ";
echo "table{border-collapse:collapse;width:100%;margin-bottom:30px;} ";
echo "td,th{border:1px solid #333;padding:6px 10px;text-align:left;} ";
echo "th{background:#16213e;color:#00d4ff;} ";
echo "td:first-child{color:#e94560;font-weight:bold;width:300px;} ";
echo ".ok{color:#0f0;font-weight:bold;} .bad{color:#f00;font-weight:bold;}</style></head><body>";

echo "<h1>🔍 Server Diagnostic for Livewire Upload</h1>";
echo "<p>Waktu: " . date('Y-m-d H:i:s') . "</p>";

// 1. Key Server Variables
echo "<h2>1. Server Variables (Yang Menentukan HTTP/HTTPS)</h2>";
echo "<table>";
$keys = ['HTTPS', 'SERVER_PORT', 'SERVER_PROTOCOL', 'REQUEST_SCHEME', 'HTTP_HOST', 'SERVER_NAME', 'SERVER_ADDR', 'REMOTE_ADDR', 'SERVER_SOFTWARE'];
foreach ($keys as $k) {
    $v = $_SERVER[$k] ?? '<span class="bad">TIDAK ADA</span>';
    echo "<tr><td>$k</td><td>$v</td></tr>";
}
echo "</table>";

// 2. Forwarded Headers (Dari Cloudflare/Proxy)
echo "<h2>2. Forwarded Headers (Dari Cloudflare/Proxy)</h2>";
echo "<table>";
$fwd = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED_PROTO', 'HTTP_X_FORWARDED_HOST', 'HTTP_X_FORWARDED_PORT', 'HTTP_CF_CONNECTING_IP', 'HTTP_CF_RAY', 'HTTP_CF_VISITOR', 'HTTP_X_REAL_IP'];
foreach ($fwd as $k) {
    $v = $_SERVER[$k] ?? '<span class="bad">TIDAK ADA</span>';
    $label = '';
    if ($k === 'HTTP_X_FORWARDED_PROTO') {
        $label = ($v === 'https') ? ' <span class="ok">✓ BENAR</span>' : ' <span class="bad">✗ SALAH! Harus "https"</span>';
    }
    echo "<tr><td>$k</td><td>$v $label</td></tr>";
}
echo "</table>";

// 3. The Critical Test
echo "<h2>3. Tes Kritis: Apakah PHP tahu ini HTTPS?</h2>";
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$isForwardedHttps = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
echo "<table>";
echo "<tr><td>\$_SERVER['HTTPS'] == 'on'?</td><td>" . ($isHttps ? '<span class="ok">YA ✓</span>' : '<span class="bad">TIDAK ✗</span>') . "</td></tr>";
echo "<tr><td>X-Forwarded-Proto == 'https'?</td><td>" . ($isForwardedHttps ? '<span class="ok">YA ✓</span>' : '<span class="bad">TIDAK ✗</span>') . "</td></tr>";
echo "</table>";

// 4. APP_KEY test
echo "<h2>4. Laravel APP_KEY</h2>";
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (preg_match('/^APP_KEY=(.+)$/m', $envContent, $m)) {
        $key = trim($m[1]);
        $masked = substr($key, 0, 15) . '...' . substr($key, -5);
        echo "<p>APP_KEY: <code>$masked</code> (masked)</p>";
        echo "<p>Panjang: " . strlen($key) . " karakter</p>";
    }
    if (preg_match('/^APP_URL=(.+)$/m', $envContent, $m)) {
        echo "<p>APP_URL: <code>" . trim($m[1]) . "</code></p>";
    }
    if (preg_match('/^APP_ENV=(.+)$/m', $envContent, $m)) {
        echo "<p>APP_ENV: <code>" . trim($m[1]) . "</code></p>";
    }
} else {
    echo '<p class="bad">.env file tidak ditemukan!</p>';
}

// 5. Config cache check
echo "<h2>5. Config Cache</h2>";
$cacheFile = __DIR__ . '/../bootstrap/cache/config.php';
echo "<p>Config cache exists? " . (file_exists($cacheFile) ? '<span class="bad">YA - bisa menyebabkan masalah!</span>' : '<span class="ok">TIDAK ✓</span>') . "</p>";

$routeCache = __DIR__ . '/../bootstrap/cache/routes-v7.php';
echo "<p>Route cache exists? " . (file_exists($routeCache) ? '<span class="bad">YA</span>' : '<span class="ok">TIDAK ✓</span>') . "</p>";

// 6. Storage/permissions check
echo "<h2>6. Storage & Permissions</h2>";
$dirs = [
    __DIR__ . '/../storage/app',
    __DIR__ . '/../storage/app/public',
    __DIR__ . '/../storage/app/public/livewire-tmp',
    __DIR__ . '/../storage/app/livewire-tmp',
    __DIR__ . '/storage',
];
echo "<table><tr><th>Directory</th><th>Exists?</th><th>Writable?</th><th>Permissions</th></tr>";
foreach ($dirs as $dir) {
    $shortDir = str_replace(__DIR__ . '/..', '', $dir);
    $exists = is_dir($dir);
    $writable = $exists ? is_writable($dir) : false;
    $perms = $exists ? substr(sprintf('%o', fileperms($dir)), -4) : '-';
    echo "<tr><td>$shortDir</td>";
    echo "<td>" . ($exists ? '<span class="ok">Ya</span>' : '<span class="bad">Tidak</span>') . "</td>";
    echo "<td>" . ($writable ? '<span class="ok">Ya</span>' : '<span class="bad">Tidak</span>') . "</td>";
    echo "<td>$perms</td></tr>";
}
echo "</table>";

// 7. Symlink check
echo "<h2>7. Storage Symlink</h2>";
$symlink = __DIR__ . '/storage';
echo "<p>public/storage exists? " . (file_exists($symlink) ? '<span class="ok">YA</span>' : '<span class="bad">TIDAK</span>') . "</p>";
echo "<p>Is symlink? " . (is_link($symlink) ? '<span class="ok">YA ✓</span>' : '<span class="bad">TIDAK ✗</span>') . "</p>";
if (is_link($symlink)) {
    echo "<p>Target: " . readlink($symlink) . "</p>";
}

// 8. PHP Info
echo "<h2>8. PHP Info</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Web Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
echo "<p>upload_max_filesize: " . ini_get('upload_max_filesize') . "</p>";
echo "<p>post_max_size: " . ini_get('post_max_size') . "</p>";
echo "<p>max_file_uploads: " . ini_get('max_file_uploads') . "</p>";

// 9. Full request URL reconstruction
echo "<h2>9. URL yang Dilihat PHP</h2>";
$scheme = $isHttps ? 'https' : ($isForwardedHttps ? 'https' : 'http');
$host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'unknown';
$uri = $_SERVER['REQUEST_URI'] ?? '/';
echo "<p>Reconstructed URL: <code>$scheme://$host$uri</code></p>";
echo "<p>SERVER_PORT: <code>" . ($_SERVER['SERVER_PORT'] ?? 'unknown') . "</code></p>";

echo "<hr><p style='color:#e94560;font-weight:bold;'>⚠️ HAPUS FILE INI SETELAH SELESAI DEBUGGING!</p>";
echo "</body></html>";
