Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host " MEMINDAHKAN VIRTUAL MEMORY (PAGEFILE) DARI C: KE D:" -ForegroundColor Yellow
Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host ""

try {
    Write-Host "[1/3] Menonaktifkan automatic pagefile pada Drive C..." -ForegroundColor Gray
    Get-CimInstance Win32_ComputerSystem | Set-CimInstance -Property @{AutomaticManagedPagefile=$false}

    Write-Host "[2/3] Menghapus pagefile dari Drive C:..." -ForegroundColor Gray
    Get-CimInstance Win32_PageFileSetting -ErrorAction SilentlyContinue | Where-Object { $_.Name -like "C:*" } | Remove-CimInstance -ErrorAction SilentlyContinue

    Write-Host "[3/3] Mendaftarkan pagefile baru di Drive D: (System Managed)..." -ForegroundColor Gray
    New-CimInstance -ClassName Win32_PageFileSetting -Property @{Name="D:\pagefile.sys"} -ErrorAction SilentlyContinue

    Write-Host ""
    Write-Host "==========================================================" -ForegroundColor Green
    Write-Host " BERHASIL! Pagefile telah dipindahkan ke Drive D:." -ForegroundColor Green
    Write-Host " Kapasitas Drive C akan bertambah ~10.7 GB lega" -ForegroundColor Green
    Write-Host " setelah Anda melakukan Restart laptop." -ForegroundColor Green
    Write-Host "==========================================================" -ForegroundColor Green
} catch {
    Write-Host "Gagal: $_" -ForegroundColor Red
}

Write-Host ""
Write-Host "Jendela ini akan tertutup otomatis dalam 6 detik..." -ForegroundColor Gray
Start-Sleep -Seconds 6

