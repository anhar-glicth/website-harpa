import os
import subprocess
import base64
import fitz
import paramiko

logo_path = r'c:\xampp\htdocs\website-harpa\laravel\public\images\logo.png'
with open(logo_path, 'rb') as f:
    logo_b64 = base64.b64encode(f.read()).decode('utf-8')

html_content = f"""<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun Role & Kredensial - Harapan Palestina</title>
    <style>
        @page {{
            size: A4 portrait;
            margin: 10mm 12mm 10mm 12mm;
        }}
        * {{
            box-sizing: border-box;
        }}
        body {{
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 0;
            font-size: 10px;
            line-height: 1.35;
            background-color: #ffffff;
        }}
        .header {{
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #063f5c;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }}
        .logo-img {{
            height: 38px;
            width: auto;
        }}
        .header-title {{
            text-align: right;
        }}
        .header-title h1 {{
            margin: 0;
            font-size: 15px;
            font-weight: 900;
            color: #063f5c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }}
        .header-title p {{
            margin: 1px 0 0 0;
            font-size: 9.5px;
            color: #64748b;
            font-weight: 600;
        }}
        .alert-box {{
            background: linear-gradient(135deg, #063f5c, #0b557c);
            color: #ffffff;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }}
        .alert-text {{
            font-size: 10px;
            line-height: 1.4;
        }}
        .alert-text strong {{
            color: #f7ad1a;
            font-size: 11px;
        }}
        .password-badge {{
            background-color: #f7ad1a;
            color: #063f5c;
            padding: 4px 10px;
            border-radius: 5px;
            font-weight: 900;
            font-size: 11.5px;
            letter-spacing: 0.8px;
            text-align: center;
        }}
        table {{
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }}
        th {{
            background-color: #063f5c;
            color: #ffffff;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 5px 6px;
            text-align: left;
            border: 1px solid #063f5c;
        }}
        td {{
            padding: 4.5px 6px;
            border: 1px solid #e2e8f0;
            font-size: 9.5px;
            vertical-align: middle;
        }}
        tr:nth-child(even) {{
            background-color: #f8fafc;
        }}
        .badge {{
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }}
        .badge-admin {{ background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }}
        .badge-marketing {{ background-color: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; }}
        .badge-finance {{ background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }}
        .badge-crm {{ background-color: #fce7f3; color: #be185d; border: 1px solid #fbcfe8; }}
        .badge-program {{ background-color: #d1fae5; color: #047857; border: 1px solid #a7f3d0; }}
        .badge-operator {{ background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; }}
        .badge-field {{ background-color: #ffe4e6; color: #be123c; border: 1px solid #fecdd3; }}
        .badge-user {{ background-color: #ccfbf1; color: #0f766e; border: 1px solid #99f6e4; }}

        .code-val {{
            font-family: 'Consolas', monospace;
            background-color: #f1f5f9;
            padding: 1.5px 4px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: 700;
            color: #0f172a;
        }}
        .section-title {{
            font-size: 11px;
            font-weight: 800;
            color: #063f5c;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin: 8px 0 6px 0;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 3px;
        }}
        .grid-cards {{
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
            margin-bottom: 8px;
        }}
        .card {{
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 6px 8px;
            background-color: #ffffff;
        }}
        .card-header {{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2px;
        }}
        .card-title {{
            font-weight: 800;
            font-size: 9.5px;
            color: #0f172a;
        }}
        .card-desc {{
            font-size: 8.5px;
            color: #475569;
            line-height: 1.3;
        }}
        .footer {{
            margin-top: 10px;
            padding-top: 6px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 8px;
            color: #94a3b8;
            font-weight: 600;
        }}
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div>
            <img src="data:image/png;base64,{logo_b64}" class="logo-img" alt="Logo Indonesia Madani">
        </div>
        <div class="header-title">
            <h1>Daftar Akun Role & Kredensial</h1>
            <p>Sistem Platform Harapan Palestina & Harpa Data Engine</p>
        </div>
    </div>

    <!-- Alert / Master Password Notice -->
    <div class="alert-box">
        <div class="alert-text">
            <strong>PORTAL RESMI: https://harapanpalestina.com/login</strong><br>
            Login dapat menggunakan <strong>Username</strong> atau <strong>Alamat Email</strong> masing-masing akun.
        </div>
        <div>
            <div style="font-size: 8px; text-transform: uppercase; font-weight: 700; color: #cbd5e1; text-align: center; margin-bottom: 2px;">Password Bawaan:</div>
            <div class="password-badge">password123</div>
        </div>
    </div>

    <!-- Main Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 24px; text-align: center;">No</th>
                <th style="width: 75px;">Role</th>
                <th style="width: 130px;">Nama / Jabatan</th>
                <th style="width: 100px;">Username</th>
                <th style="width: 140px;">Email Login</th>
                <th style="width: 80px;">Password</th>
                <th>Cakupan Hak Akses Utama</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; font-weight: bold;">1</td>
                <td><span class="badge badge-admin">admin</span></td>
                <td><strong>Super Admin</strong></td>
                <td><span class="code-val">admin</span> / <span class="code-val" style="color:#0369a1;">admin_harpa</span></td>
                <td>admin@humanity.org<br><span style="color:#0369a1; font-weight:bold;">admin@harpa.id</span></td>
                <td><span class="code-val">password123</span></td>
                <td><strong>Akses Penuh:</strong> Kelola staff, settings, audit kinerja, Harpa A-F, modul marketing ads.</td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">2</td>
                <td><span class="badge badge-marketing">marketing</span></td>
                <td><strong>Staff Marketing & Ads</strong></td>
                <td><span class="code-val">marketing</span> / <span class="code-val" style="color:#6d28d9;">marketing_harpa</span></td>
                <td>marketing@humanity.org<br><span style="color:#6d28d9; font-weight:bold;">marketing@harpa.id</span></td>
                <td><span class="code-val">password123</span></td>
                <td><strong>Pusat Iklan:</strong> Dashboard Ads, input Meta/Google Ads, ROAS, CPC, CPA, Scale/Stop.</td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">3</td>
                <td><span class="badge badge-finance">finance</span></td>
                <td><strong>Staff Keuangan</strong></td>
                <td><span class="code-val">finance</span> / <span class="code-val" style="color:#b45309;">finance_harpa</span></td>
                <td>finance@humanity.org<br><span style="color:#b45309; font-weight:bold;">finance@harpa.id</span></td>
                <td><span class="code-val">password123</span></td>
                <td><strong>Keuangan:</strong> Verifikasi bukti donasi, data transaksi, bank pembayaran, realisasi penyaluran.</td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">4</td>
                <td><span class="badge badge-crm">crm_staff</span></td>
                <td><strong>Staff CRM Telemarketing</strong></td>
                <td><span class="code-val">crm</span> / <span class="code-val" style="color:#be185d;">crm_harpa</span></td>
                <td>crm@humanity.org<br><span style="color:#be185d; font-weight:bold;">crm@harpa.id</span></td>
                <td><span class="code-val">password123</span></td>
                <td><strong>CRM Donatur:</strong> Analisa RFM, follow-up prospek, blast Everpro, daftar kirim, WBS Pro.</td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">5</td>
                <td><span class="badge badge-program">program_manager</span></td>
                <td><strong>Manager Program</strong></td>
                <td><span class="code-val">program</span> / <span class="code-val" style="color:#047857;">program_harpa</span></td>
                <td>program@humanity.org<br><span style="color:#047857; font-weight:bold;">program@harpa.id</span></td>
                <td><span class="code-val">password123</span></td>
                <td><strong>Program:</strong> Kelola kampanye donasi, kategori program, distribusi bantuan, berita aksi.</td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">6</td>
                <td><span class="badge badge-operator">operator</span></td>
                <td><strong>Admin Data Entry</strong></td>
                <td><span class="code-val">operator</span> / <span class="code-val" style="color:#334155;">operator_harpa</span></td>
                <td>operator@humanity.org<br><span style="color:#334155; font-weight:bold;">operator@harpa.id</span></td>
                <td><span class="code-val">password123</span></td>
                <td><strong>Master Data:</strong> Input kantor, saluran donasi, entri donatur baru, rekap transaksi.</td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">7</td>
                <td><span class="badge badge-field">field_worker</span></td>
                <td><strong>Petugas Lapangan</strong></td>
                <td><span class="code-val">petugas</span> / <span class="code-val" style="color:#be123c;">petugas_harpa</span></td>
                <td>petugas@humanity.org<br><span style="color:#be123c; font-weight:bold;">petugas@harpa.id</span></td>
                <td><span class="code-val">password123</span></td>
                <td><strong>Portal Lapangan:</strong> Presensi GPS & foto selfie, to-do list harian, catat donasi tunai.</td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">8</td>
                <td><span class="badge badge-user">user</span></td>
                <td><strong>Donatur Umum</strong></td>
                <td><span class="code-val">donatur</span> / <span class="code-val" style="color:#0f766e;">donatur_harpa</span></td>
                <td>donatur@humanity.org<br><span style="color:#0f766e; font-weight:bold;">donatur@harpa.id</span></td>
                <td><span class="code-val">password123</span></td>
                <td><strong>Portal Publik:</strong> Dashboard riwayat donasi pribadi, unduh kuitansi, kalkulator zakat.</td>
            </tr>
        </tbody>
    </table>

    <!-- Ringkasan Otorisasi -->
    <div class="section-title">Panduan Otorisasi Menu Sidebar</div>
    <div class="grid-cards">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Super Admin (admin)</span>
                <span class="badge badge-admin">Semua Akses</span>
            </div>
            <div class="card-desc">
                Akses penuh ke semua menu: Kelola Staff, Evaluasi Kinerja, Settings, Master Data, Donatur, Transaksi, Engine Harpa, dan Marketing.
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Staff Marketing (marketing)</span>
                <span class="badge badge-marketing">Iklan & Analisa</span>
            </div>
            <div class="card-desc">
                Akses khusus ke <strong>Dashboard & Analisa Ads</strong>, <strong>Data Iklan & Input</strong>, Analisa RFM, Kampanye, Absensi, serta Daftar Tugas.
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Staff Keuangan (finance)</span>
                <span class="badge badge-finance">Verifikasi & Transaksi</span>
            </div>
            <div class="card-desc">
                Verifikasi transaksi donasi masuk, kelola rekening bank, monitor laporan penerimaan donasi dan realisasi penyaluran.
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Staff CRM (crm_staff)</span>
                <span class="badge badge-crm">Donatur & Broadcast</span>
            </div>
            <div class="card-desc">
                Retensi donatur: Analisa RFM, daftar kirim pesan Jumat & Payday, pengenalan donatur, dan filter nomor WA mati (WBS Pro).
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div>Dokumen Resmi Yayasan Indonesia Madani &bull; Harapan Palestina &copy; 2026</div>
        <div>Klasifikasi: Terbatas (Internal Tim & Pengelola Sistem) &bull; Dicetak: 02 November 2026</div>
    </div>

</body>
</html>"""

html_path = r'c:\xampp\htdocs\website-harpa\scratch\daftar_akun_role.html'
pdf_path_root = r'c:\xampp\htdocs\website-harpa\DAFTAR_AKUN_ROLE_HARAPAN_PALESTINA.pdf'
pdf_path_public = r'c:\xampp\htdocs\website-harpa\laravel\public\DAFTAR_AKUN_ROLE_HARAPAN_PALESTINA.pdf'

with open(html_path, 'w', encoding='utf-8') as f:
    f.write(html_content)

edge_path = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
cmd = [
    edge_path,
    "--headless",
    "--disable-gpu",
    "--no-pdf-header-footer",
    f"--print-to-pdf={pdf_path_root}",
    html_path
]

subprocess.run(cmd, capture_output=True, text=True)

import shutil
shutil.copyfile(pdf_path_root, pdf_path_public)

doc = fitz.open(pdf_path_root)
print('Page count:', len(doc))

# Render new preview image
page = doc[0]
pix = page.get_pixmap(dpi=150)
preview_local = r'c:\xampp\htdocs\website-harpa\scratch\pdf_preview.png'
preview_artifact = r'C:\Users\ASUS\.gemini\antigravity\brain\5bc9546d-7464-49d7-a6ee-18bda4c46ec5\pdf_preview.png'
pix.save(preview_local)
pix.save(preview_artifact)
print('Updated preview image saved!')

# Upload to live Hostinger server
client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect('153.92.10.174', port=65002, username='u290212134', password='f1D022013.')
sftp = client.open_sftp()
sftp.put(pdf_path_root, '/home/u290212134/domains/harapanpalestina.com/public_html/laravel/public/DAFTAR_AKUN_ROLE_HARAPAN_PALESTINA.pdf')
sftp.close()
client.close()
print('Uploaded new PDF to live server!')
