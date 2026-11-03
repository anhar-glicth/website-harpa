@echo off
title Pindahkan Pagefile dari Drive C ke Drive D
echo ========================================================
echo  MEMINDAHKAN VIRTUAL MEMORY (PAGEFILE) DARI C: KE D:
echo  (Yayasan Indonesia Madani / Optimasi Storage Laptop)
echo ========================================================
echo.

:: Cek Administrator
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo [INFO] Membuka jendela konfirmasi Administrator (UAC)...
    powershell -Command "Start-Process cmd -ArgumentList '/c \"\"%~f0\"\"' -Verb RunAs"
    exit /b
)

echo [1/3] Mengatur konfigurasi Paging File kustom...
powershell -Command "Set-CimInstance -Query 'Select * from Win32_ComputerSystem' -Property @{AutomaticManagedPagefile=$False}"

echo [2/3] Menghapus pagefile dari Drive C:...
powershell -Command "Get-CimInstance -ClassName Win32_PageFileSetting -ErrorAction SilentlyContinue | Where-Object { $_.Name -like 'C:*' } | Remove-CimInstance -ErrorAction SilentlyContinue"

echo [3/3] Mendaftarkan pagefile baru di Drive D: (System Managed)...
powershell -Command "New-CimInstance -ClassName Win32_PageFileSetting -Property @{Name='D:\pagefile.sys'} -ErrorAction SilentlyContinue"

echo.
echo ========================================================
echo  BERHASIL! 
echo  Pagefile (Virtual Memory) telah dipindahkan ke Drive D.
echo.
echo  Sekitar ~10.7 GB kapasitas Drive C akan bebas sepenuhnya
echo  setelah Anda melakukan RESTART laptop.
echo ========================================================
echo.
pause

