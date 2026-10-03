<?php
/**
 * SCRIPT DIAGNOSA & PERBAIKAN UPLOAD FOTO PROFIL
 * 
 * CARA PAKAI DI HOSTINGER:
 * 1. Upload file ini ke folder: public_html/setup_storage.php
 * 2. Buka di browser: https://yourdomain.com/setup_storage.php
 * 3. HAPUS file ini setelah selesai!
 */

// Keamanan dasar - ganti dengan token acak
$token = $_GET['token'] ?? '';
if ($token !== 'setup2024') {
    die('Akses ditolak. Tambahkan ?token=setup2024 ke URL.');
}

$laravelRoot = dirname(__DIR__); // folder laravel/
$publicPath  = __DIR__;          // folder public/

echo "<style>body{font-family:monospace;padding:20px;} .ok{color:green;} .err{color:red;} .warn{color:orange;}</style>";
echo "<h2>🔧 Diagnosa Storage & Foto Profil</h2>";

// 1. Cek direktori storage/app/public
$storagePublic = $laravelRoot . '/storage/app/public';
echo "<h3>1. Direktori storage/app/public</h3>";
if (is_dir($storagePublic)) {
    echo "<p class='ok'>✅ Exists: $storagePublic</p>";
    $writable = is_writable($storagePublic);
    echo "<p class='" . ($writable ? 'ok' : 'err') . "'>" . ($writable ? '✅' : '❌') . " Writable: " . ($writable ? 'YES' : 'NO') . "</p>";
} else {
    echo "<p class='err'>❌ TIDAK ADA! Buat direktori ini.</p>";
    if (mkdir($storagePublic, 0755, true)) {
        echo "<p class='ok'>✅ Berhasil dibuat.</p>";
    }
}

// 2. Cek direktori profiles
$profilesDir = $storagePublic . '/profiles';
echo "<h3>2. Direktori profiles</h3>";
if (!is_dir($profilesDir)) {
    echo "<p class='warn'>⚠️ Belum ada. Membuat...</p>";
    if (mkdir($profilesDir, 0755, true)) {
        echo "<p class='ok'>✅ Berhasil dibuat: $profilesDir</p>";
    } else {
        echo "<p class='err'>❌ Gagal membuat direktori profiles!</p>";
    }
} else {
    echo "<p class='ok'>✅ Exists: $profilesDir</p>";
}

$profilesWritable = is_writable($profilesDir);
echo "<p class='" . ($profilesWritable ? 'ok' : 'err') . "'>" . ($profilesWritable ? '✅' : '❌') . " Writable: " . ($profilesWritable ? 'YES' : 'NO - CHMOD 755 tidak cukup, coba 775') . "</p>";

// Coba set permission 775
if (!$profilesWritable) {
    chmod($profilesDir, 0775);
    $profilesWritable = is_writable($profilesDir);
    echo "<p>Setelah chmod 775: " . ($profilesWritable ? "<span class='ok'>✅ Writable</span>" : "<span class='err'>❌ Masih tidak writable</span>") . "</p>";
}

// 3. Cek symlink public/storage
echo "<h3>3. Symlink public/storage</h3>";
$symlinkPath = $publicPath . '/storage';
if (file_exists($symlinkPath)) {
    echo "<p class='ok'>✅ public/storage exists</p>";
    if (is_link($symlinkPath)) {
        echo "<p class='ok'>✅ Ini adalah symlink → " . readlink($symlinkPath) . "</p>";
    } else {
        echo "<p class='warn'>⚠️ Ini BUKAN symlink, tapi folder/directory biasa</p>";
    }
} else {
    echo "<p class='err'>❌ public/storage TIDAK ADA. Perlu membuat symlink!</p>";
    // Coba buat symlink
    if (symlink($storagePublic, $symlinkPath)) {
        echo "<p class='ok'>✅ Symlink berhasil dibuat!</p>";
    } else {
        echo "<p class='err'>❌ Gagal membuat symlink. Shared hosting mungkin tidak support symlink.</p>";
        echo "<p class='warn'>💡 Solusi alternatif: Gunakan folder copy bukan symlink.</p>";
        
        // Buat folder dan copy .gitignore sebagai penanda
        if (!is_dir($symlinkPath)) {
            mkdir($symlinkPath, 0755, true);
        }
        // Buat file marker agar tau ini sudah di-setup
        file_put_contents($symlinkPath . '/README.txt', 'This folder should be a symlink to ../storage/app/public. Created by setup script.');
        echo "<p class='warn'>⚠️ Folder public/storage dibuat manual. Upload foto akan disimpan di storage/app/public/profiles/ tapi mungkin tidak accessible via URL.</p>";
    }
}

// 4. Cek file yang sudah diupload
echo "<h3>4. File foto yang sudah diupload</h3>";
$files = glob($profilesDir . '/*.*');
if (empty($files)) {
    echo "<p class='warn'>⚠️ Belum ada foto yang diupload di profiles/</p>";
} else {
    echo "<p class='ok'>✅ " . count($files) . " file ditemukan:</p><ul>";
    foreach ($files as $f) {
        $size = round(filesize($f)/1024, 1);
        echo "<li>" . basename($f) . " ({$size} KB) - " . date('Y-m-d H:i', filemtime($f)) . "</li>";
    }
    echo "</ul>";
}

// 5. Test write permission dengan file dummy
echo "<h3>5. Test Tulis File</h3>";
$testFile = $profilesDir . '/test_write_' . time() . '.txt';
if (file_put_contents($testFile, 'test') !== false) {
    echo "<p class='ok'>✅ Berhasil menulis file test: " . basename($testFile) . "</p>";
    unlink($testFile);
    echo "<p class='ok'>✅ File test dihapus.</p>";
} else {
    echo "<p class='err'>❌ GAGAL menulis ke folder profiles/! Ini penyebab foto tidak tersimpan.</p>";
    echo "<p class='warn'>💡 Hubungi Hostinger support untuk set permission folder storage/app/public/profiles ke 755 atau 775.</p>";
}

// 6. Test URL akses foto via storage symlink
echo "<h3>6. URL Akses Storage</h3>";
$appUrl = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'yourdomain.com');
echo "<p>APP_URL seharusnya: <strong>$appUrl</strong></p>";
echo "<p>URL foto akan berbentuk: <code>$appUrl/storage/profiles/namafile.jpg</code></p>";

// 7. Database check - cek apakah ada user dengan profile_photo
echo "<h3>7. Ringkasan & Rekomendasi</h3>";
echo "<ol>";
echo "<li>Pastikan folder <code>storage/app/public/profiles</code> bisa ditulis (writable)</li>";
echo "<li>Pastikan <code>public/storage</code> adalah symlink yang mengarah ke <code>../storage/app/public</code></li>";
echo "<li>Pastikan <code>APP_URL</code> di file <code>.env</code> sesuai dengan domain Hostinger Anda</li>";
echo "<li>Jalankan: <code>php artisan storage:link</code> via SSH/terminal Hostinger jika tersedia</li>";
echo "<li>Setelah git pull di server, coba upload foto profil kembali</li>";
echo "</ol>";

echo "<hr><p style='color:red'><strong>⚠️ HAPUS FILE INI SETELAH SELESAI DIAGNOSA!</strong></p>";
echo "<p>Hapus: <code>public_html/setup_storage.php</code></p>";
