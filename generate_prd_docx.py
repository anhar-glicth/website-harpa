# -*- coding: utf-8 -*-
"""
Script Generator: DOKUMENTASI PRD & PANDUAN PENGGUNAAN SISTEM DONATION-WEB
Format: Microsoft Word (.docx)
Target: Client-ready, Executive & Technical Standard
"""

import os
import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml, OxmlElement
from docx.oxml.ns import nsdecls, qn

def set_cell_background(cell, hex_color):
    """Memberikan warna latar belakang (shading) pada sel tabel."""
    shading_elm = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{hex_color}"/>')
    cell._tc.get_or_add_tcPr().append(shading_elm)

def set_cell_margins(cell, top=120, bottom=120, left=160, right=160):
    """Mengatur padding dalam sel tabel (dalam dxa, 20 dxa = 1 pt)."""
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = parse_xml(
        f'<w:tcMar {nsdecls("w")}>'
        f'  <w:top w:w="{top}" w:type="dxa"/>'
        f'  <w:bottom w:w="{bottom}" w:type="dxa"/>'
        f'  <w:left w:w="{left}" w:type="dxa"/>'
        f'  <w:right w:w="{right}" w:type="dxa"/>'
        f'</w:tcMar>'
    )
    tcPr.append(tcMar)

def set_table_borders(table, color="CBD5E1", sz="4", val="single"):
    """Mengatur border tipis elegan pada tabel."""
    tblPr = table._tbl.tblPr
    borders = parse_xml(
        f'<w:tblBorders {nsdecls("w")}>'
        f'  <w:top w:val="{val}" w:sz="{sz}" w:space="0" w:color="{color}"/>'
        f'  <w:bottom w:val="{val}" w:sz="{sz}" w:space="0" w:color="{color}"/>'
        f'  <w:left w:val="none"/>'
        f'  <w:right w:val="none"/>'
        f'  <w:insideH w:val="{val}" w:sz="{sz}" w:space="0" w:color="{color}"/>'
        f'  <w:insideV w:val="none"/>'
        f'</w:tblBorders>'
    )
    tblPr.append(borders)

def add_header_footer(doc):
    """Menambahkan Header dan Footer profesional pada dokumen."""
    for s in doc.sections:
        s.top_margin = Inches(0.8)
        s.bottom_margin = Inches(0.8)
        s.left_margin = Inches(0.9)
        s.right_margin = Inches(0.9)
        
        # Header
        header = s.header
        hp = header.paragraphs[0]
        hp.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        hrun = hp.add_run("Product Requirement Document (PRD) & Panduan Sistem | Donation-Web Enterprise")
        hrun.font.name = "Calibri"
        hrun.font.size = Pt(8.5)
        hrun.font.color.rgb = RGBColor(148, 163, 184)
        
        # Footer
        footer = s.footer
        fp = footer.paragraphs[0]
        fp.alignment = WD_ALIGN_PARAGRAPH.CENTER
        frun = fp.add_run("Dokumen Resmi Spesifikasi & Panduan Klien - Sistem Informasi Filantropi Terpadu")
        frun.font.name = "Calibri"
        frun.font.size = Pt(8.5)
        frun.font.color.rgb = RGBColor(148, 163, 184)

def add_callout(doc, text, title="INFORMASI PENTING", callout_type="info"):
    """Menambahkan kotak callout informatif dengan border aksen kiri tebal."""
    bg_color = "F0FDF4" if callout_type == "success" else ("FEF2F2" if callout_type == "danger" else "F8FAFC")
    border_color = "059669" if callout_type == "success" else ("DC2626" if callout_type == "danger" else "047857")
    
    table = doc.add_table(rows=1, cols=1)
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    table.autofit = False
    table.columns[0].width = Inches(6.7)
    
    cell = table.cell(0, 0)
    set_cell_background(cell, bg_color)
    set_cell_margins(cell, top=160, bottom=160, left=220, right=200)
    
    # Custom left border only
    tcPr = cell._tc.get_or_add_tcPr()
    borders = parse_xml(
        f'<w:tcBorders {nsdecls("w")}>'
        f'  <w:top w:val="none"/>'
        f'  <w:left w:val="single" w:sz="36" w:space="0" w:color="{border_color}"/>'
        f'  <w:bottom w:val="none"/>'
        f'  <w:right w:val="none"/>'
        f'</w:tcBorders>'
    )
    tcPr.append(borders)
    
    p = cell.paragraphs[0]
    p.paragraph_format.space_before = Pt(2)
    p.paragraph_format.space_after = Pt(4)
    run_title = p.add_run(f"📌 {title}: ")
    run_title.font.name = "Calibri"
    run_title.font.size = Pt(10)
    run_title.font.bold = True
    if callout_type == "success":
        run_title.font.color.rgb = RGBColor(5, 150, 105)
    elif callout_type == "danger":
        run_title.font.color.rgb = RGBColor(220, 38, 38)
    else:
        run_title.font.color.rgb = RGBColor(4, 120, 87)
        
    run_text = p.add_run(text)
    run_text.font.name = "Calibri"
    run_text.font.size = Pt(9.5)
    run_text.font.color.rgb = RGBColor(51, 65, 85)
    
    doc.add_paragraph().paragraph_format.space_after = Pt(4)

def add_heading_1(doc, text):
    """Menambahkan Heading 1 dengan styling elegan hijau emerald."""
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(18)
    p.paragraph_format.space_after = Pt(6)
    p.paragraph_format.keep_with_next = True
    run = p.add_run(text)
    run.font.name = "Calibri"
    run.font.size = Pt(16)
    run.font.bold = True
    run.font.color.rgb = RGBColor(4, 120, 87) # Emerald 700
    return p

def add_heading_2(doc, text):
    """Menambahkan Heading 2 dengan styling slate gelap."""
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(12)
    p.paragraph_format.space_after = Pt(4)
    p.paragraph_format.keep_with_next = True
    run = p.add_run(text)
    run.font.name = "Calibri"
    run.font.size = Pt(13)
    run.font.bold = True
    run.font.color.rgb = RGBColor(30, 41, 59) # Slate 800
    return p

def add_heading_3(doc, text):
    """Menambahkan Heading 3 dengan styling teal profesional."""
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(8)
    p.paragraph_format.space_after = Pt(2)
    p.paragraph_format.keep_with_next = True
    run = p.add_run(text)
    run.font.name = "Calibri"
    run.font.size = Pt(11)
    run.font.bold = True
    run.font.color.rgb = RGBColor(13, 148, 136) # Teal 600
    return p

def add_body(doc, text, bold_prefix=None, space_after=4):
    """Menambahkan teks paragraf dengan format rapi."""
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(0)
    p.paragraph_format.space_after = Pt(space_after)
    p.paragraph_format.line_spacing = 1.15
    
    if bold_prefix:
        run_p = p.add_run(bold_prefix)
        run_p.font.name = "Calibri"
        run_p.font.size = Pt(10)
        run_p.font.bold = True
        run_p.font.color.rgb = RGBColor(15, 23, 42)
        
    run_t = p.add_run(text)
    run_t.font.name = "Calibri"
    run_t.font.size = Pt(10)
    run_t.font.color.rgb = RGBColor(51, 65, 85)
    return p

def add_bullet(doc, text, bold_prefix=None, level=0):
    """Menambahkan bullet list terstruktur."""
    p = doc.add_paragraph(style='List Bullet')
    p.paragraph_format.space_before = Pt(1)
    p.paragraph_format.space_after = Pt(3)
    p.paragraph_format.left_indent = Inches(0.25 * (level + 1))
    
    if bold_prefix:
        r_b = p.add_run(bold_prefix)
        r_b.font.name = "Calibri"
        r_b.font.size = Pt(10)
        r_b.font.bold = True
        r_b.font.color.rgb = RGBColor(15, 23, 42)
        
    r_t = p.add_run(text)
    r_t.font.name = "Calibri"
    r_t.font.size = Pt(10)
    r_t.font.color.rgb = RGBColor(51, 65, 85)
    return p

def add_styled_table(doc, headers, data, col_widths=None):
    """Membuat tabel profesional dengan styling header emerald, zebra stripe, dan border halus."""
    table = doc.add_table(rows=len(data) + 1, cols=len(headers))
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    table.autofit = False
    set_table_borders(table, color="CBD5E1", sz="4")
    
    # Header Row
    hdr_row = table.rows[0]
    hdr_row._tr.get_or_add_trPr().append(parse_xml(f'<w:tblHeader {nsdecls("w")}/>'))
    for idx, heading in enumerate(headers):
        cell = hdr_row.cells[idx]
        set_cell_background(cell, "047857") # Emerald Dark
        set_cell_margins(cell, top=140, bottom=140, left=160, right=160)
        cell.vertical_alignment = WD_ALIGN_VERTICAL.CENTER
        
        p = cell.paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p.paragraph_format.space_before = Pt(0)
        p.paragraph_format.space_after = Pt(0)
        run = p.add_run(heading)
        run.font.name = "Calibri"
        run.font.size = Pt(9.5)
        run.font.bold = True
        run.font.color.rgb = RGBColor(255, 255, 255)
        
    # Data Rows
    for row_idx, row_data in enumerate(data):
        row = table.rows[row_idx + 1]
        bg = "F8FAFC" if row_idx % 2 == 1 else "FFFFFF"
        for col_idx, cell_value in enumerate(row_data):
            cell = row.cells[col_idx]
            set_cell_background(cell, bg)
            set_cell_margins(cell, top=100, bottom=100, left=140, right=140)
            cell.vertical_alignment = WD_ALIGN_VERTICAL.CENTER
            
            p = cell.paragraphs[0]
            p.paragraph_format.space_before = Pt(0)
            p.paragraph_format.space_after = Pt(0)
            
            # Text alignment logic
            if col_idx == 0 and len(cell_value) <= 5 and cell_value.isdigit():
                p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            elif any(sym in cell_value for sym in ["Rp", "%", "✓", "✗"]):
                p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            else:
                p.alignment = WD_ALIGN_PARAGRAPH.LEFT
                
            run = p.add_run(str(cell_value))
            run.font.name = "Calibri"
            run.font.size = Pt(9)
            if "✓" in cell_value:
                run.font.bold = True
                run.font.color.rgb = RGBColor(5, 150, 105) # Green
            elif "✗" in cell_value:
                run.font.color.rgb = RGBColor(148, 163, 184) # Muted Grey
            else:
                run.font.color.rgb = RGBColor(51, 65, 85)
                
    # Set Widths if specified
    if col_widths and len(col_widths) == len(headers):
        for row in table.rows:
            for idx, width in enumerate(col_widths):
                row.cells[idx].width = Inches(width)
                
    doc.add_paragraph().paragraph_format.space_after = Pt(6)
    return table

def build_prd_document(output_path):
    print("Mempersiapkan dokumen Word PRD...")
    doc = docx.Document()
    add_header_footer(doc)
    
    # -------------------------------------------------------------
    # HALAMAN JUDUL (COVER PAGE)
    # -------------------------------------------------------------
    cov_p = doc.add_paragraph()
    cov_p.paragraph_format.space_before = Pt(36)
    cov_p.paragraph_format.space_after = Pt(12)
    
    # Title Box / Banner Table
    cov_table = doc.add_table(rows=1, cols=1)
    cov_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    cov_table.autofit = False
    cov_table.columns[0].width = Inches(6.7)
    
    c_cell = cov_table.cell(0, 0)
    set_cell_background(c_cell, "064E3B") # Deep Forest Green
    set_cell_margins(c_cell, top=360, bottom=360, left=300, right=300)
    
    tp = c_cell.paragraphs[0]
    tp.alignment = WD_ALIGN_PARAGRAPH.CENTER
    trun = tp.add_run("PRODUCT REQUIREMENT DOCUMENT (PRD)\n& PANDUAN PENGGUNAAN SISTEM")
    trun.font.name = "Calibri"
    trun.font.size = Pt(22)
    trun.font.bold = True
    trun.font.color.rgb = RGBColor(255, 255, 255)
    
    sub_p = c_cell.add_paragraph()
    sub_p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    sub_p.paragraph_format.space_before = Pt(8)
    srun = sub_p.add_run("Sistem Informasi Manajemen Filantropi, Donasi, Penyaluran Bantuan, CRM, & Pengawasan Staf Terpadu (Donation-Web)")
    srun.font.name = "Calibri"
    srun.font.size = Pt(12)
    srun.font.color.rgb = RGBColor(167, 243, 208) # Emerald 200
    
    doc.add_paragraph().paragraph_format.space_after = Pt(20)
    
    # Metadata Table
    meta_headers = ["Parameter Dokumen", "Keterangan Spesifikasi"]
    meta_data = [
        ["Nama Aplikasi", "Donation-Web Platform (Enterprise Edition)"],
        ["Tipe Dokumen", "Product Requirement Document (PRD) & Master User Manual"],
        ["Versi Dokumen", "Versi 2.0 (Stabil & Dinamis Teruji)"],
        ["Tanggal Rilis", "Oktober 2026"],
        ["Basis Framework", "Laravel 10.x MVC, MySQL Relational DB, Blade View Engine"],
        ["Target Pengguna", "Klien Yayasan / Lembaga Filantropi, Stakeholders, Manajemen, Staf Kantor, Petugas Lapangan, & Donatur Publik"],
        ["Status Sistem", "Production Ready (Seluruh CRUD Dinamis & Terintegrasi Penuh)"]
    ]
    add_styled_table(doc, meta_headers, meta_data, col_widths=[2.3, 4.4])
    
    add_callout(doc, 
        "Dokumen ini disusun sebagai panduan menyeluruh bagi klien dan pengguna akhir untuk memahami arsitektur modul, cara kerja fitur di setiap antarmuka, relasi data antar-tabel, matriks hak akses 7 peran (Role-Based Access Control), serta Prosedur Operasional Standar (SOP) harian.",
        title="TUJUAN DOKUMEN", callout_type="success")
    
    doc.add_page_break()
    
    # -------------------------------------------------------------
    # DAFTAR ISI RINGKAS
    # -------------------------------------------------------------
    add_heading_1(doc, "DAFTAR ISI & STRUKTUR DOKUMENTASI")
    toc_items = [
        ("BAB 1: Pendahuluan & Ringkasan Eksekutif", "Latar belakang, urgensi digitalisasi filantropi, tujuan, dan profil arsitektur teknologi."),
        ("BAB 2: Arsitektur Data, Relasi Antar-Fitur & Alur Bisnis (ERD)", "Keterhubungan siklus hidup donatur, program kampanye, realisasi penyaluran, target bulanan, dan presensi staf."),
        ("BAB 3: Matriks Hak Akses & Pembagian 7 Peran Pengguna (RBAC)", "Rincian wewenang, batas akses, dan tabel matriks izin 25+ menu sistem untuk seluruh peran."),
        ("BAB 4: Panduan Operasional Lengkap Fitur Per Modul", "Panduan visual langkah-demi-langkah (input, edit, filter, ekspor) untuk 9 modul operasional."),
        ("BAB 5: Standar Operasional Prosedur (SOP) Alur Kerja", "SOP verifikasi donasi, penyaluran bantuan, prospek kemitraan, pembuatan kampanye, dan pelaporan lapangan."),
        ("BAB 6: FAQ & Panduan Troubleshooting Pengguna", "Solusi kendala teknis umum, filter tanggal, verifikasi transfer, dan manajemen berkas.")
    ]
    for title, desc in toc_items:
        add_bullet(doc, f" - {desc}", bold_prefix=title)
        
    doc.add_paragraph().paragraph_format.space_after = Pt(12)
    
    # -------------------------------------------------------------
    # BAB 1: PENDAHULUAN & RINGKASAN EKSEKUTIF
    # -------------------------------------------------------------
    add_heading_1(doc, "BAB 1: PENDAHULUAN & RINGKASAN EKSEKUTIF")
    
    add_heading_2(doc, "1.1 Latar Belakang & Urgensi Platform")
    add_body(doc, 
        "Lembaga amil zakat, infak, sedekah, dan yayasan filantropi modern menghadapi tantangan operasional yang kompleks. Pengelolaan donasi dari ribuan donatur, penyaluran bantuan kepada penerima manfaat (mustahik/asnaf), pemantauan calon donatur potensial (CRM), hingga koordinasi aktivitas petugas lapangan memerlukan sistem digital terintegrasi yang akuntabel dan transparan.")
    add_body(doc, 
        "Sistem Donation-Web hadir sebagai solusi terpusat (all-in-one philanthropy enterprise portal) yang menghilangkan pencatatan manual berbasis lembar kerja (spreadsheet) yang rentan terhadap kehilangan data, keterlambatan pelaporan, dan selisih rekonsiliasi keuangan.")

    add_heading_2(doc, "1.2 Tujuan & Nilai Manfaat Utama bagi Lembaga")
    add_bullet(doc, "Menjamin setiap rupiah donasi tercatat dengan status verifikasi yang jelas dan terhubung dengan kuitansi resmi.", bold_prefix="Akuntabilitas Keuangan Donasi: ")
    add_bullet(doc, "Setiap penyaluran bantuan wajib memiliki dokumentasi foto, pilihan asnaf, jumlah penerima manfaat, dan referensi program kampanye sumber.", bold_prefix="Transparansi Penyaluran Bantuan: ")
    add_bullet(doc, "Manajemen prospek donatur melalui tahapan pipeline terstruktur, log interaksi berkala, hingga otomatisasi konversi menjadi donatur tetap.", bold_prefix="Efektivitas Penghimpunan Dana (Fundraising CRM): ")
    add_bullet(doc, "Presensi berbasis koordinat GPS dan foto selfie serta pemantauan tugas lapangan secara real-time.", bold_prefix="Pengawasan Kinerja & Operasional Staf: ")
    add_bullet(doc, "Portal donatur untuk melihat rekam jejak infak/zakat, unduh kuitansi digital, dan kalkulator zakat terintegrasi.", bold_prefix="Peningkatan Kepercayaan Donatur Publik: ")

    add_heading_2(doc, "1.3 Profil Arsitektur Teknologi")
    add_body(doc, "Sistem dibangun dengan standar arsitektur industri berbasis teknologi terbuka (open-source) yang teruji kestabilannya:")
    tech_headers = ["Komponen Teknologi", "Implementasi Teknis", "Peran & Kegunaan"]
    tech_data = [
        ["Framework Backend", "Laravel 10.x (PHP 8.2+)", "Menangani routing, autentikasi RBAC, middleware, validasi data, dan logika bisnis (Controller & Model Eloquent)."],
        ["Database Engine", "MySQL 8.0 / MariaDB", "Penyimpanan data relasional terstruktur dengan Foreign Keys, Indexing optimal, dan integritas referensial antar tabel."],
        ["Antarmuka (Frontend)", "Blade View Engine, Tailwind CSS, Bootstrap", "Render tampilan responsif, modern, dan dioptimalkan baik untuk desktop maupun smartphone petugas lapangan."],
        ["Manajemen Berkas", "Laravel Public Storage (symlink)", "Penyimpanan aman bukti transfer donatur, dokumentasi penyaluran bantuan, foto selfie absensi, dan bukti hasil tugas."],
        ["Keamanan Sistem", "Bcrypt Password Hashing, CSRF Protection, SQL Injection Guard", "Proteksi data sensitif donatur, enkripsi kata sandi, dan proteksi serangan web standar OWASP."]
    ]
    add_styled_table(doc, tech_headers, tech_data, col_widths=[1.5, 2.2, 3.0])

    # -------------------------------------------------------------
    # BAB 2: ARSITEKTUR DATA, ALUR BISNIS & RELASI ANTAR-MODUL (ERD)
    # -------------------------------------------------------------
    add_heading_1(doc, "BAB 2: ARSITEKTUR DATA, RELASI ANTAR-MODUL & ALUR BISNIS")
    
    add_heading_2(doc, "2.1 Diagram Konseptual Keterhubungan Modul")
    add_body(doc, 
        "Kekuatan utama dari platform Donation-Web terletak pada keterpaduan aliran data antar-modul. Data tidak berdiri sendiri, melainkan mengalir secara logis dari hulu (penghimpunan prospek) hingga hilir (penyaluran bantuan dan laporan akuntabilitas).")
    
    add_callout(doc,
        "Aliran Siklus Inti Sistem:\n"
        "1. PROSPEK: Calon Donatur (Prospects) -> Follow-up Interaksi -> Closing & Konversi -> Donatur Resmi (Users).\n"
        "2. PENGHIMPUNAN: Program Kampanye (Campaigns) -> Transaksi Donasi Masuk (Donations) -> Verifikasi Finance -> Penerbitan Kuitansi.\n"
        "3. PENYALURAN: Saldo Program Terhimpun -> Perencanaan Penyaluran (Distributions) -> Eksekusi Lapangan & Bukti Foto -> Dashboard Analisis Penyaluran.\n"
        "4. KINERJA: Penetapan Target Bulanan (Master Targets) vs Realisasi Donasi Sukses -> Dashboard Target vs Capaian.",
        title="ALIRAN DATA TERPADU (DATA LIFECYCLE)", callout_type="info")

    add_heading_2(doc, "2.2 Penjelasan Alur Bisnis Kunci & Relasi Entitas")
    
    add_heading_3(doc, "A. Siklus Hidup Donatur (Donor CRM Lifecycle)")
    add_body(doc, 
        "Proses dimulai ketika staf kemitraan (CRM) atau petugas lapangan menginput data calon donatur di menu 'Calon Donatur / Prospek'.")
    add_bullet(doc, "Tabel `prospects` menyimpan profil calon donatur (nama, nomor WhatsApp, email, status minat Cold/Warm/Hot, estimasi potensi donasi, channel perkenalan, dan fundraiser PIC).")
    add_bullet(doc, "Setiap kali staf melakukan panggilan telepon, WhatsApp, atau presentasi, staf menambahkan entri pada log interaksi (`prospect_follow_ups` / `prospect_responses`).")
    add_bullet(doc, "Ketika calon donatur bersedia berdonasi ('Closing'), tombol 'Konversi ke Donatur' di antarmuka akan memicu pembuatan akun di tabel `users` dengan role 'user' dan membuat nomor ID Donatur permanen.")
    add_bullet(doc, "Setelah resmi menjadi donatur, setiap donasi yang disetorkan akan tercatat di tabel `donations` berelasi dengan `user_id`, sehingga sistem dapat menghitung metrik RFM (Recency, Frequency, Monetary).")

    add_heading_3(doc, "B. Siklus Program Kampanye & Penyaluran Bantuan (Distribution Lifecycle)")
    add_body(doc, 
        "Siklus penyaluran bantuan memastikan amanah donatur tersalurkan tepat sasaran:")
    add_bullet(doc, "Manager Program mengelompokkan program ke dalam Rumpun Program (`program_groups`) seperti Pendidikan, Kesehatan, Kemanusiaan, dan Dakwah.")
    add_bullet(doc, "Di bawah rumpun tersebut dibuat Program Kampanye spesifik (`campaigns`) lengkap dengan target anggaran, target tanggal selesai, dan rekening penampungan.")
    add_bullet(doc, "Donatur menyalurkan dana ke kampanye tersebut. Dana yang terverifikasi (status 'Success') menjadi saldo himpunan dana kampanye.")
    add_bullet(doc, "Staf penyaluran mengeksekusi bantuan di lapangan dan menginput data di menu 'Penyaluran Bantuan' (`distributions`). Data wajib memuat: Kampanye rujukan (`campaign_id`), Asnaf penerima (Fakir, Miskin, Fisabilillah, dll), Sumber Dana, Nominal pengeluaran, Jumlah Penerima Manfaat (PM), Wilayah kecamatan/kelurahan, dan Unggahan foto dokumentasi kegiatan.")
    add_bullet(doc, "Halaman 'Dashboard Penyaluran' membaca tabel `distributions` secara real-time dan menyajikan agregasi grafik penyerapan dana, proporsi asnaf, dan sebaran wilayah.")

    add_heading_3(doc, "C. Siklus Target & Capaian Penghimpunan Dana (Target vs Achievement)")
    add_body(doc, 
        "Manajemen menetapkan target penghimpunan bulanan di menu `Master Targets` (`master_targets`) berdasarkan tahun, bulan, nominal target, dan kategori rumpun program atau PIC fundraiser.")
    add_body(doc, 
        "Halaman 'Target vs Capaian' secara dinamis membandingkan nilai target tersebut dengan agregat donasi riil (`SUM(donations.amount) WHERE payment_status = 'Success'`). Sistem menghitung persentase capaian dan mengidentifikasi apakah program mencapai target surplus atau defisit.")

    add_heading_3(doc, "D. Siklus Manajemen Staf, Absensi GPS & Delegasi Tugas")
    add_body(doc, 
        "Setiap staf terdaftar di tabel `karyawan` (NIK, nama, jabatan, divisi, kontak) yang ditautkan ke akun login di tabel `users` via `karyawan_id`.")
    add_bullet(doc, "Staf kantor dan petugas lapangan wajib melakukan presensi harian di menu Absensi (`attendances`). Sistem mencatat jam masuk, jam pulang, titik koordinat GPS (latitude/longitude), dan foto selfie kehadiran.")
    add_bullet(doc, "Atasan atau Operator mendistribusikan pekerjaan harian di menu Tugas (`todo_lists`). Staf mengerjakan tugas, mengubah status menjadi 'In Progress' / 'Completed', serta mengunggah foto bukti penyelesaian.")
    add_bullet(doc, "Menu 'Evaluasi Kinerja' mengalkulasi persentase kehadiran tepat waktu dan rasio keberhasilan penyelesaian tugas bulanan.")

    add_heading_2(doc, "2.3 Tabel Matriks Relasi Database Utama")
    add_body(doc, "Tabel berikut merangkum hubungan kunci antar-tabel dalam database sistem:")
    rel_headers = ["Tabel Induk", "Tabel Relasi (Anak)", "Foreign Key (FK)", "Hubungan", "Kegunaan & Aturan Bisnis"]
    rel_data = [
        ["users", "donations", "donations.user_id", "1 to Many", "Satu donatur dapat memiliki banyak transaksi donasi lintas kampanye."],
        ["campaigns", "donations", "donations.campaign_id", "1 to Many", "Satu kampanye dapat menerima banyak transaksi donasi dari berbagai donatur."],
        ["campaigns", "distributions", "distributions.campaign_id", "1 to Many", "Satu program kampanye dapat disalurkan dalam beberapa tahapan kegiatan bantuan."],
        ["program_groups", "campaigns", "campaigns.program_group_id", "1 to Many", "Mengelompokkan program kampanye ke rumpun utama (Pendidikan, Kesehatan, dll)."],
        ["prospects", "prospect_follow_ups", "prospect_follow_ups.prospect_id", "1 to Many", "Satu calon donatur memiliki rekam jejak interaksi berkala dari staf CRM."],
        ["prospects", "users", "prospects.converted_user_id", "1 to 1 (Opsional)", "Menghubungkan data calon donatur dengan akun donatur resmi setelah closing."],
        ["karyawan", "users", "users.karyawan_id", "1 to 1", "Menghubungkan data profil kepegawaian dengan kredensial hak akses login sistem."],
        ["users", "attendances", "attendances.user_id", "1 to Many", "Merekam presensi harian staf (waktu, koordinat GPS, foto selfie)."],
        ["users", "todo_lists", "todo_lists.assigned_to", "1 to Many", "Penugasan aktivitas kerja harian staf beserta batas waktu dan bukti hasil."],
        ["master_targets", "donations", "Berdasarkan Periode & Program", "Agregasi", "Membandingkan target nominal bulanan dengan total donasi berstatus 'Success'."]
    ]
    add_styled_table(doc, rel_headers, rel_data, col_widths=[1.1, 1.2, 1.4, 0.9, 2.1])

    # -------------------------------------------------------------
    # BAB 3: MATRIKS HAK AKSES & PEMBAGIAN 7 PERAN PENGGUNA (RBAC)
    # -------------------------------------------------------------
    add_heading_1(doc, "BAB 3: MATRIKS HAK AKSES & PEMBAGIAN 7 PERAN PENGGUNA (RBAC)")
    
    add_heading_2(doc, "3.1 Definisi 7 Peran Pengguna (User Roles)")
    add_body(doc, 
        "Sistem Donation-Web menerapkan pembagian wewenang ketat berbasis Role-Based Access Control (RBAC). Terdapat 7 peran terdefinisi untuk memastikan keamanan data finansial dan efisiensi operasional:")
    
    roles_def = [
        ("1. Admin (Super Administrator)", "Pemegang kendali tertinggi seluruh sistem. Berwenang mengelola pengguna (`users`), data master kepegawaian (`karyawan`), konfigurasi landing page (`settings`), evaluasi kinerja staf, serta memiliki hak akses penuh (CRUD) ke seluruh modul tanpa batasan."),
        ("2. Operator (Staff Administrasi Data)", "Bertanggung jawab atas kelancaran entri data harian: menginput donasi offline/tunai, memperbarui data donatur, mencatat data prospek baru, mengelola absensi staf, dan mendelegasikan tugas harian."),
        ("3. Finance (Staff Verifikasi & Keuangan)", "Fokus pada arus kas masuk dan keluar. Berwenang memverifikasi transfer donasi (mengubah status Pending menjadi Success/Failed), mencetak kuitansi resmi, memantau dashboard transaksi, mengaudit pengeluaran penyaluran bantuan, dan menganalisis laporan realisasi target."),
        ("4. Program Manager (Manager Penyaluran Program)", "Penanggung jawab program dan penyaluran bantuan. Berwenang menyetujui/mengedit kampanye program, merencanakan dan menginput penyaluran bantuan, memvalidasi bukti dokumentasi penyaluran, serta memantau Dashboard Penyaluran per asnaf."),
        ("5. CRM Staff (Staff Kemitraan & Donatur)", "Berfokus pada pemeliharaan relasi donatur dan akuisisi donatur baru. Mengelola pipeline calon donatur (Cold/Warm/Hot), mencatat riwayat follow-up WhatsApp/telepon, melakukan konversi calon donatur menjadi donatur tetap, dan menganalisis segmentasi donatur (RFM)."),
        ("6. Field Worker (Petugas Lapangan / Relawan)", "Petugas di lapangan yang menggunakan antarmuka mobile portal (`/field/dashboard`). Berwenang melakukan presensi masuk/pulang dengan GPS & kamera ponsel, melihat daftar tugas lapangan, melaporkan calon donatur yang ditemui di lapangan, serta mengunggah bukti foto penyaluran bantuan."),
        ("7. User (Donatur Publik / Masyarakat)", "Donatur eksternal yang mendaftar melalui situs web. Memiliki akses ke portal donatur pribadi (`/dashboard`) untuk melihat riwayat donasi mereka, mengunduh kuitansi digital, menggunakan kalkulator zakat terpadu, menuliskan doa di Dinding Doa, serta mengelola profil pribadi.")
    ]
    for r_title, r_desc in roles_def:
        add_bullet(doc, r_desc, bold_prefix=f"{r_title}: ")

    add_heading_2(doc, "3.2 Tabel Matriks Izin Akses Fitur (Fitur vs 7 Role)")
    add_body(doc, "Tabel berikut menunjukkan hak akses spesifik untuk setiap modul antarmuka berdasarkan peran pengguna:")
    
    matrix_headers = ["Modul / Fitur Menu", "Admin", "Operator", "Finance", "Prog. Mgr", "CRM", "Field", "Donatur"]
    matrix_data = [
        ["Dashboard Ringkasan (/admin/dashboard)", "✓", "✓", "✓", "✓", "✓", "✗", "✗"],
        ["Verifikasi Donasi Masuk (Pending->Success)", "✓", "✗", "✓", "✗", "✗", "✗", "✗"],
        ["Input Donasi Manual (Kasir/Offline)", "✓", "✓", "✓", "✗", "✗", "✗", "✗"],
        ["Daftar Transaksi & Export Laporan", "✓", "✓", "✓", "✓ (Lihat)", "✓ (Lihat)", "✗", "✗"],
        ["Cetak Kuitansi Resmi Donatur", "✓", "✓", "✓", "✓ (Lihat)", "✓ (Lihat)", "✗", "✗"],
        ["Database Donatur & Detail Profil", "✓", "✓", "✓ (Lihat)", "✓ (Lihat)", "✓", "✗", "✗"],
        ["Input Penyaluran Bantuan & Bukti Foto", "✓", "✓", "✓ (Audit)", "✓", "✗", "✓ (Portal)", "✗"],
        ["Dashboard Penyaluran & Filter Asnaf", "✓", "✓", "✓", "✓", "✓ (Lihat)", "✗", "✗"],
        ["Kelola Kampanye Program (CRUD)", "✓", "✓", "✓ (Lihat)", "✓", "✓ (Lihat)", "✗", "✗"],
        ["Pipeline Calon Donatur (Prospek CRM)", "✓", "✓", "✗", "✗", "✓", "✓ (Input)", "✗"],
        ["Log Follow-up & Interaksi Donatur", "✓", "✓", "✗", "✗", "✓", "✗", "✗"],
        ["Konversi Calon Donatur ke User Donatur", "✓", "✓", "✗", "✗", "✓", "✗", "✗"],
        ["Penetapan Target Donasi Bulanan", "✓", "✗", "✓", "✓", "✓", "✗", "✗"],
        ["Dashboard Target vs Capaian", "✓", "✓", "✓", "✓", "✓", "✗", "✗"],
        ["Absensi Karyawan (Rekapitulasi GPS)", "✓", "✓", "✓ (Lihat)", "✓ (Lihat)", "✓ (Lihat)", "✗", "✗"],
        ["Delegasi & Manajemen Tugas Staf", "✓", "✓", "✗", "✓", "✗", "✓ (Mengerjakan)", "✗"],
        ["Evaluasi Kinerja Staf (Performance)", "✓", "✗", "✗", "✗", "✗", "✗", "✗"],
        ["Master Data (10 Parameter Konfigurasi)", "✓", "✓", "✗", "✗", "✗", "✗", "✗"],
        ["Master Karyawan (SDM Yayasan)", "✓", "✗", "✗", "✗", "✗", "✗", "✗"],
        ["User Management & Perubahan Role", "✓", "✗", "✗", "✗", "✗", "✗", "✗"],
        ["Pengaturan Web & Rekening (Settings)", "✓", "✗", "✗", "✗", "✗", "✗", "✗"],
        ["Mobile Portal Khusus Lapangan (/field)", "✓", "✗", "✗", "✗", "✗", "✓", "✗"],
        ["Portal Donatur Pribadi (/dashboard)", "✗", "✗", "✗", "✗", "✗", "✗", "✓"],
        ["Kalkulator Zakat & Bayar Online", "✓ (Web)", "✓ (Web)", "✓ (Web)", "✓ (Web)", "✓ (Web)", "✓ (Web)", "✓"],
        ["Dinding Doa Publik & Kirim Doa", "✓", "✓", "✓", "✓", "✓", "✓", "✓"]
    ]
    add_styled_table(doc, matrix_headers, matrix_data, col_widths=[2.1, 0.65, 0.65, 0.65, 0.75, 0.65, 0.65, 0.6])
    
    # -------------------------------------------------------------
    # BAB 4: PANDUAN OPERASIONAL FITUR PER MODUL (MENU-BY-MENU)
    # -------------------------------------------------------------
    add_heading_1(doc, "BAB 4: PANDUAN OPERASIONAL FITUR PER MODUL (USER MANUAL)")
    
    # 4.1 Dashboard Utama
    add_heading_2(doc, "4.1 Modul Dashboard Utama & Statistik Cepat")
    add_body(doc, "Lokasi Menu: Menu Utama -> Dashboard (`/admin/dashboard`)")
    add_body(doc, "Tujuan: Memberikan gambaran sekilas (helicopter view) mengenai kesehatan finansial dan operasional lembaga secara langsung.")
    add_bullet(doc, "Menampilkan Donasi Terkumpul Hari Ini, Donasi Bulan Ini, Donasi Terverifikasi, dan Akumulasi Donasi Sepanjang Masa.", bold_prefix="Metrik Kartu Ringkasan (Cards): ")
    add_bullet(doc, "Visualisasi tren donasi 12 bulan terakhir untuk melihat pola kenaikan penghimpunan (misal pada bulan Ramadhan atau program tanggap darurat).", bold_prefix="Grafik Tren Penghimpunan: ")
    add_bullet(doc, "Daftar transaksi donasi yang baru masuk yang memerlukan tindakan cepat verifikasi oleh bagian keuangan.", bold_prefix="Tabel Donasi Terbaru: ")
    add_bullet(doc, "Menampilkan status pekerjaan staf hari ini (Pending, Sedang Dikerjakan, Selesai).", bold_prefix="Widget Tugas Prioritas: ")

    # 4.2 Penyaluran & Distribusi
    add_heading_2(doc, "4.2 Modul Penyaluran & Distribusi Bantuan (Distribution Management)")
    add_body(doc, "Lokasi Menu: Distribusi Management -> Penyaluran Bantuan (`/admin/distributions`) & Dashboard Penyaluran (`/admin/distributions/dashboard`)")
    add_body(doc, "Tujuan: Mengelola realisasi alokasi dana bantuan kepada penerima manfaat serta menyediakan pelaporan yang akuntabel dan transparan.")
    
    add_heading_3(doc, "A. Cara Menginput Data Penyaluran Baru (`/admin/distributions`)")
    add_bullet(doc, "Buka menu 'Penyaluran Bantuan', kemudian klik tombol hijau '+ Tambah Penyaluran'.", bold_prefix="Langkah 1: ")
    add_bullet(doc, "Pilih 'Program Kampanye' yang mendanai penyaluran ini. Sistem akan menautkan pengeluaran ini dengan saldo kampanye terkait.", bold_prefix="Langkah 2: ")
    add_bullet(doc, "Pilih 'Golongan Asnaf' penerima bantuan (Fakir, Miskin, Amil, Muallaf, Riqab, Gharim, Fisabilillah, Ibnu Sabil).", bold_prefix="Langkah 3: ")
    add_bullet(doc, "Pilih 'Sumber Dana' (Zakat, Infak Terikat, Sedekah Subuh, CSR Perusahaan, Hibah).", bold_prefix="Langkah 4: ")
    add_bullet(doc, "Isi 'Jumlah Penerima Manfaat (Jiwa/Kepala Keluarga)' dan masukkan 'Nominal Dana yang Disalurkan (Rp)'.", bold_prefix="Langkah 5: ")
    add_bullet(doc, "Pilih 'Tanggal Penyaluran' serta masukkan rincian wilayah (Kecamatan, Kelurahan, Alamat Lokasi).", bold_prefix="Langkah 6: ")
    add_bullet(doc, "Unggah 'Foto Dokumentasi Penyaluran' (format JPG/PNG maksimal 2MB) sebagai bukti sahih kegiatan di lapangan.", bold_prefix="Langkah 7: ")
    add_bullet(doc, "Klik 'Simpan Penyaluran'. Data akan langsung tercatat dan memperbarui statistik di Dashboard Penyaluran.", bold_prefix="Langkah 8: ")

    add_heading_3(doc, "B. Menggunakan Dashboard Penyaluran (`/admin/distributions/dashboard`)")
    add_bullet(doc, "Pilih 'Bulan' dan 'Tahun' pada bilah filter bagian atas, kemudian klik 'Filter'. Grafik dan tabel akan otomatis menyaring data sesuai rentang yang dipilih.", bold_prefix="Filter Dinamis Periode: ")
    add_bullet(doc, "Menampilkan total dana yang terserap dan total jiwa penerima manfaat pada periode terpilih.", bold_prefix="Kartu Statistik Penyaluran: ")
    add_bullet(doc, "Menampilkan diagram lingkaran porsi penyaluran untuk memastikan distribusi merata sesuai syariat.", bold_prefix="Grafik Proporsi Asnaf: ")
    add_bullet(doc, "Melihat rekam jejak penyaluran lengkap beserta thumbnail foto dokumentasi kegiatan.", bold_prefix="Tabel Riwayat Penyaluran: ")

    # 4.3 Transaksi & Donatur
    add_heading_2(doc, "4.3 Modul Transaksi & Donatur (Transaction Management)")
    add_body(doc, "Lokasi Menu: Transaction Management -> Daftar Donasi (`/admin/transactions`), Dashboard Transaksi, Daftar Donatur (`/admin/donors`), dan Kuitansi (`/admin/donor-receipt/{id}`)")
    
    add_heading_3(doc, "A. Prosedur Verifikasi Donasi Masuk")
    add_body(doc, 
        "Donasi yang disetor donatur via transfer manual bank yayasan akan berstatus 'Pending'. Petugas Finance wajib memvalidasi mutasi rekening koran yayasan sebelum mengubah status:")
    add_bullet(doc, "Buka menu 'Daftar Donasi', cari donasi dengan status 'Pending'.", bold_prefix="Langkah 1: ")
    add_bullet(doc, "Klik tombol 'Detail / Bukti Transfer' untuk melihat struk setoran yang diunggah donatur.", bold_prefix="Langkah 2: ")
    add_bullet(doc, "Cek mutasi bank yayasan apakah dana dengan nominal dan kode unik tersebut sudah efektif masuk rekening.", bold_prefix="Langkah 3: ")
    add_bullet(doc, "Ubah status menjadi 'Success' (Terverifikasi). Seketika dana akan ditambahkan ke saldo kampanye dan kuitansi donasi resmi dapat dicetak.", bold_prefix="Langkah 4: ")
    add_bullet(doc, "Jika bukti transfer terbukti palsu atau mutasi tidak ditemukan, ubah status menjadi 'Failed' disertai catatan alasan pembatalan.", bold_prefix="Catatan Penolakan: ")

    add_heading_3(doc, "B. Pencetakan & Penerbitan Kuitansi Resmi Donatur (E-Receipt)")
    add_body(doc, 
        "Untuk setiap donasi yang berstatus 'Success', sistem secara otomatis menerbitkan kuitansi elektronik resmi lengkap dengan No. Kuitansi Unik, Tanggal Pengesahan, Nama Donatur, Program Pilihan, Terbilang Rupiah, dan Tanda Tangan Digital Pengurus Yayasan. Kuitansi dapat diunduh dalam format PDF atau dicetak langsung untuk diserahkan kepada donatur.")

    add_heading_3(doc, "C. Manajemen Database Donatur (`/admin/donors`)")
    add_body(doc, 
        "Menu ini menyimpan rekam jejak seluruh donatur terdaftar. Fitur meliputi: pencarian nama/telepon/email, melihat total nominal infak yang pernah disalurkan donatur, riwayat program yang didukung, status aktif/inaktif, serta pengelompokan segmentasi.")

    # 4.4 CRM & Prospek
    add_heading_2(doc, "4.4 Modul CRM & Prospek Donatur (Prospect & CRM Management)")
    add_body(doc, "Lokasi Menu: CRM & Prospek -> Calon Donatur (`/admin/potential-donors`), Prospek Donatur (`/admin/prospects`), Target Donasi (`/admin/targets`), dan Target vs Capaian (`/admin/target-vs-achievement`)")
    
    add_heading_3(doc, "A. Pipeline Pengelolaan Calon Donatur")
    add_body(doc, "Mengadopsi tahapan corong konversi (conversion funnel) penjualan modern untuk fundraising filantropi:")
    add_bullet(doc, "Calon donatur baru yang baru diperoleh dari event, web form, atau kontak masuk.", bold_prefix="Tahap 1: Cold (Dingin) - ")
    add_bullet(doc, "Calon donatur telah dihubungi dan menunjukkan respon positif terhadap program lembaga.", bold_prefix="Tahap 2: Warm (Hangat) - ")
    add_bullet(doc, "Calon donatur meminta proposal program atau nomor rekening yayasan untuk transfer donasi.", bold_prefix="Tahap 3: Hot (Panas) - ")
    add_bullet(doc, "Calon donatur telah menyetorkan donasi pertamanya dan siap dikonversi menjadi donatur resmi.", bold_prefix="Tahap 4: Closing (Berhasil) - ")
    add_bullet(doc, "Calon donatur menolak atau belum bersedia berdonasi setelah beberapa kali dihubungi.", bold_prefix="Tahap 5: Lost (Gagal) - ")

    add_heading_3(doc, "B. Fitur Konversi Prospek ke Donatur Resmi")
    add_body(doc, 
        "Ketika calon donatur berstatus 'Closing', staf cukup mengklik tombol 'Konversi ke Donatur'. Sistem secara otomatis membuat akun donatur di database pengguna (`users`), menautkan histori interaksi prospek, dan mengaktifkan akses donatur tersebut ke portal publik.")

    add_heading_3(doc, "C. Analisis Target vs Capaian (`/admin/target-vs-achievement`)")
    add_body(doc, 
        "Halaman ini digunakan oleh direksi dan pimpinan fundraising untuk mengevaluasi kinerja perolehan dana bulanan. Dilengkapi indikator persentase ketercapaian, warna status (Hijau = Mencapai Target, Kuning = Mendekati, Merah = Di bawah Target), serta rincian per program/fundraiser.")

    # 4.5 Monitoring Staff
    add_heading_2(doc, "4.5 Modul Monitoring Staf & Manajemen SDM (Staff Monitoring)")
    add_body(doc, "Lokasi Menu: Monitoring Staff -> Rekap Absensi (`/admin/monitoring/attendance`), Manajemen Tugas (`/admin/monitoring/tasks`), dan Evaluasi Kinerja (`/admin/performance`)")
    
    add_heading_3(doc, "A. Absensi Staf Berbasis Geotagging GPS & Kamera")
    add_body(doc, 
        "Mencegah kecurangan presensi staf kantor maupun relawan lapangan. Ketika staf menekan tombol 'Absen Masuk' atau 'Absen Pulang', peramban web meminta izin lokasi GPS untuk merekam titik latitude/longitude dan mengaktifkan kamera depan untuk mengambil swafoto (selfie) staf. Data ini tersimpan di rekap absensi harian.")

    add_heading_3(doc, "B. Delegasi Tugas & Manajemen Pekerjaan (Task Management)")
    add_body(doc, 
        "Pimpinan dapat membuat penugasan kerja harian, menentukan tenggat waktu (deadline), menetapkan tingkat prioritas (Low, Medium, High), dan memilih staf yang ditugaskan. Staf akan menerima tugas di dasbor mereka, memperbarui progres pekerjaan, dan mengunggah foto bukti penyelesaian tugas sebelum menutup status tugas.")

    add_heading_3(doc, "C. Evaluasi Kinerja (Staff Performance Appraisal)")
    add_body(doc, 
        "Secara otomatis menghitung skor kinerja staf berdasarkan: persentase kehadiran tepat waktu, jumlah tugas yang diselesaikan tepat waktu, dan penilaian kualitatif atasan langsung.")

    # 4.6 Master Data
    add_heading_2(doc, "4.6 Modul Master Data (10 Parameter Konfigurasi Sistem)")
    add_body(doc, "Lokasi Menu: Master Data -> Kelola Parameter Konfigurasi")
    add_body(doc, "Master data berfungsi sebagai referensi standar agar data yang diinput oleh seluruh staf tetap konsisten, rapi, dan mudah dianalisis:")
    
    m_headers = ["No", "Entitas Master Data", "Fungsi & Peranan dalam Sistem"]
    m_data = [
        ["1", "Fundraisers", "Mendaftar nama-nama staf penghimpun dana untuk atribusi perolehan donasi."],
        ["2", "Payment Via (Metode Pembayaran)", "Mendaftar opsi pembayaran (Transfer BCA, Mandiri, BSI, QRIS, Tunai Kasir)."],
        ["3", "Program Groups (Rumpun Program)", "Kategori induk kampanye (Pendidikan, Kesehatan, Sosial Dakwah, Bencana Alam)."],
        ["4", "Programs (Sub-Program)", "Sub-kategori teknis di bawah rumpun program."],
        ["5", "Prospect Media (Media Komunikasi)", "Saluran kontak yang digunakan saat follow-up (WhatsApp, Telepon, Kunjungan Langsung)."],
        ["6", "Prospect Methods (Metode Pendekatan)", "Cara pendekatan ke calon donatur (Presentasi Corporate, Direct Selling, Digital Ad)."],
        ["7", "Prospect Responses (Respon Interaksi)", "Standar klasifikasi respon calon donatur (Tertarik, Minta Proposal, Tidak Tertarik)."],
        ["8", "Prospect Types (Tipe Prospek)", "Kategori donatur sasaran (Individu Retail, Komunitas, Instansi Pemerintah, Korporasi)."],
        ["9", "Donor Channels (Saluran Donatur)", "Asal mula perkenalan donatur (Media Sosial Instagram, Website, Brosur, Event)."],
        ["10", "Master Karyawan", "Database seluruh staf yayasan (NIK, Nama, Divisi, Jabatan, Nomor Kontak)."]
    ]
    add_styled_table(doc, m_headers, m_data, col_widths=[0.5, 2.3, 3.9])

    # 4.7 Portal Lapangan
    add_heading_2(doc, "4.7 Modul Khusus Petugas Lapangan (Field Worker Mobile Portal)")
    add_body(doc, "Lokasi Akses: `/field/dashboard`, `/field/prospects`, `/field/tasks`, `/field/distributions`")
    add_body(doc, 
        "Dirancang khusus dengan antarmuka mobile-first ringan dan cepat untuk petugas yang aktif di lapangan menggunakan smartphone:")
    add_bullet(doc, "Tombol presensi masuk dan pulang langsung dengan akses kamera selfie dan GPS ponsel.", bold_prefix="Quick Clock-in/Clock-out: ")
    add_bullet(doc, "Melihat daftar tugas harian yang didelegasikan oleh manajer kantor beserta lokasi tugas.", bold_prefix="Tugas Lapangan Harian: ")
    add_bullet(doc, "Menginput calon donatur baru yang ditemui langsung di lapangan saat roadshow atau sosialisasi.", bold_prefix="Entri Prospek Seketika: ")
    add_bullet(doc, "Melaporkan penyaluran bantuan langsung dari tempat kejadian beserta upload foto dokumentasi penerima manfaat.", bold_prefix="Laporan Penyaluran Instan: ")

    # 4.8 Portal Donatur
    add_heading_2(doc, "4.8 Modul Khusus Donatur Publik (Public Donor Portal)")
    add_body(doc, "Lokasi Akses: `/dashboard`, `/zakat`, `/prayers`, `/profile/custom`")
    add_body(doc, 
        "Disediakan khusus untuk memanjakan donatur publik demi meningkatkan loyalitas dan transparansi:")
    add_bullet(doc, "Melihat rekam jejak seluruh donasi yang pernah disalurkan, lengkap dengan nomor kuitansi digital.", bold_prefix="Riwayat Donasi Pribadi: ")
    add_bullet(doc, "Fitur penghitungan otomatis nisab dan haul zakat (Zakat Penghasilan, Zakat Maal, Zakat Emas) dan langsung membayar via gateway.", bold_prefix="Kalkulator Zakat Interaktif: ")
    add_bullet(doc, "Menuliskan permohonan doa kebaikan dan saling mengaminkan doa sesama donatur secara interaktif.", bold_prefix="Dinding Doa (Prayer Wall): ")
    add_bullet(doc, "Memperbarui foto profil, nomor telepon WhatsApp, dan kata sandi akun.", bold_prefix="Pengaturan Akun & Profil: ")

    # 4.9 Pengaturan Landing Page
    add_heading_2(doc, "4.9 Modul Pengaturan Web & Konten Landing Page (`/admin/settings`)")
    add_body(doc, "Lokasi Menu: Admin Management -> Pengaturan Web (`/admin/settings`) - Khusus Super Admin")
    add_body(doc, 
        "Memungkinkan pengurus yayasan mengubah identitas tampilan website publik tanpa perlu bantuan programmer:")
    add_bullet(doc, "Mengubah nama yayasan, tagline, logo resmi, dan favicon browser.", bold_prefix="Identitas Lembaga: ")
    add_bullet(doc, "Mengatur nomor call center WhatsApp yayasan, alamat email resmi, jam operasional, dan peta lokasi kantor.", bold_prefix="Kontak & Alamat: ")
    add_bullet(doc, "Mengatur nama bank, nomor rekening, dan nama pemilik rekening resmi penampungan donasi.", bold_prefix="Rekening Bank Yayasan: ")
    add_bullet(doc, "Memperbarui gambar slider dan kalimat ajakan (call-to-action) di halaman depan website.", bold_prefix="Banner Slider Beranda: ")

    # -------------------------------------------------------------
    # BAB 5: STANDAR OPERASIONAL PROSEDUR (SOP) ALUR KERJA
    # -------------------------------------------------------------
    add_heading_1(doc, "BAB 5: STANDAR OPERASIONAL PROSEDUR (SOP) ALUR KERJA UTAMA")
    
    sop_list = [
        ("SOP 1: Penerimaan & Verifikasi Donasi Masuk",
         "1. Donatur melakukan transaksi transfer bank via web publik.\n"
         "2. Donasi masuk ke tabel dengan status 'Pending'.\n"
         "3. Staff Finance memeriksa mutasi rekening koran harian bank yayasan.\n"
         "4. Jika dana cocok, Finance mengubah status transaksi menjadi 'Success'.\n"
         "5. Saldo program kampanye bertambah otomatis dan E-Receipt terbit.\n"
         "6. Notifikasi kuitansi dapat dikirimkan ke kontak WhatsApp donatur."),
        
        ("SOP 2: Realisasi & Dokumentasi Penyaluran Bantuan",
         "1. Manager Program mengajukan rencana penyaluran berdasarkan saldo kampanye.\n"
         "2. Petugas lapangan mengeksekusi penyaluran bantuan kepada penerima manfaat.\n"
         "3. Petugas mengambil dokumentasi foto serah terima bantuan di lokasi.\n"
         "4. Staf menginput data penyaluran di sistem (/admin/distributions): rincian asnaf, nominal, jumlah jiwa, dan upload foto.\n"
         "5. Finance memverifikasi pengeluaran kas dengan kwitansi penyaluran.\n"
         "6. Data otomatis tersaji di Dashboard Penyaluran Publik & Laporan Audit."),
         
        ("SOP 3: Pendekatan Prospek Donatur Hingga Closing",
         "1. Tim CRM menginput data calon donatur di menu Calon Donatur (Status: Cold).\n"
         "2. Staf melakukan follow-up (WhatsApp/Telp) dan mencatat hasil respon di sistem.\n"
         "3. Status dinaikkan ke 'Warm' lalu 'Hot' sesuai tingkat kesiapan calon donatur.\n"
         "4. Setelah calon donatur menyatakan komitmen dan transfer donasi, status diubah menjadi 'Closing'.\n"
         "5. Staf menekan tombol 'Konversi ke Donatur' untuk mendaftarkan akun donatur tetap resmi."),
         
        ("SOP 4: Pembukaan & Pengelolaan Program Kampanye Baru",
         "1. Manager Program mengidentifikasi kebutuhan program darurat atau reguler.\n"
         "2. Menentukan Rumpun Program induk (Pendidikan, Kesehatan, Dakwah, dll).\n"
         "3. Menginput detail kampanye: judul menarik, target nominal dana, batas waktu, deskripsi program, dan foto cover berkualitas tinggi.\n"
         "4. Mempublikasikan kampanye agar muncul di halaman depan website.\n"
         "5. Memantau progres penghimpunan secara berkala."),
         
        ("SOP 5: Presensi & Pelaporan Tugas Petugas Lapangan",
         "1. Petugas membuka link /field/dashboard melalui browser smartphone.\n"
         "2. Mengaktifkan izin lokasi (GPS) dan kamera selfie.\n"
         "3. Melakukan 'Absen Masuk' sebelum memulai aktivitas lapangan.\n"
         "4. Membuka menu 'Tugas Lapangan' dan menjalankan tugas sesuai prioritas.\n"
         "5. Mengunggah foto hasil pekerjaan dan menandai tugas selesai ('Completed').\n"
         "6. Melakukan 'Absen Pulang' di akhir jam kerja.")
    ]
    for sop_title, sop_steps in sop_list:
        add_heading_2(doc, sop_title)
        add_callout(doc, sop_steps, title="LANGKAH KERJA STANDAR", callout_type="success")

    # -------------------------------------------------------------
    # BAB 6: FAQ & PANDUAN TROUBLESHOOTING
    # -------------------------------------------------------------
    add_heading_1(doc, "BAB 6: FAQ & PANDUAN PEMECAHAN MASALAH (TROUBLESHOOTING)")
    
    faqs = [
        ("T: Mengapa data di Dashboard Penyaluran tidak muncul setelah filter diubah?",
         "J: Pastikan rentang Bulan dan Tahun yang dipilih memiliki transaksi penyaluran yang telah diinput. Jika pada bulan tersebut belum ada data penyaluran yang dicatat, grafik akan bernilai nol. Periksa kembali daftar penyaluran di menu 'Daftar Penyaluran'."),
         
        ("T: Bagaimana cara mendaftarkan staf baru dengan hak akses role tertentu?",
         "J: Login sebagai Super Admin. Buka menu 'Master Karyawan' dan buat profil karyawan baru. Selanjutnya, buka menu 'User Management', klik '+ Tambah User', tautkan dengan data karyawan yang baru dibuat, lalu tentukan 'Role' yang sesuai (misal: Finance, CRM, atau Operator)."),
         
        ("T: Donatur transfer dengan nominal berbeda dengan yang diinput di web, bagaimana solusinya?",
         "J: Staf Finance dapat membuka detail donasi terkait di menu 'Daftar Donasi', mengedit nominal agar sesuai dengan bukti mutasi bank riil, lalu melakukan verifikasi status menjadi 'Success'."),
         
        ("T: Berapa batas ukuran foto dokumentasi dan bukti transfer yang didukung?",
         "J: Sistem mendukung format gambar JPG, JPEG, dan PNG dengan batas ukuran maksimal 2MB per gambar. Pastikan gambar jelas dan tidak buram demi kemudahan verifikasi audit."),
         
        ("T: Bagaimana cara mengamankan akun administrator dan staf?",
         "J: Gunakan kata sandi yang kuat (kombinasi huruf besar, huruf kecil, angka, dan simbol). Jangan membagikan akun login antar staf, dan pastikan selalu menekan tombol 'Logout' setelah selesai menggunakan perangkat publik/bersama.")
    ]
    for q, a in faqs:
        add_heading_3(doc, q)
        add_body(doc, a)

    # -------------------------------------------------------------
    # LEMBAR PENGESAHAN DOKUMEN
    # -------------------------------------------------------------
    doc.add_page_break()
    add_heading_1(doc, "LEMBAR PENGESAHAN DOKUMEN SISTEM")
    add_body(doc, "Dokumen Spesifikasi Kebutuhan Produk (PRD) dan Panduan Penggunaan Sistem ini telah ditinjau dan disetujui bersama:")
    
    app_headers = ["Pihak Peninjau", "Nama / Jabatan", "Status Persetujuan", "Tanda Tangan & Tanggal"]
    app_data = [
        ["Tim Pengembang Sistem (Developer)", "Lead Software Engineer", "Disetujui (Approved)", "___________________"],
        ["Manajemen Lembaga (Client PIC)", "Direktur Operasional / Program", "Disetujui (Approved)", "___________________"],
        ["Bagian Keuangan & Akuntansi", "Manager Keuangan Yayasan", "Disetujui (Approved)", "___________________"],
        ["Penanggung Jawab Kemitraan", "Head of CRM & Fundraising", "Disetujui (Approved)", "___________________"]
    ]
    add_styled_table(doc, app_headers, app_data, col_widths=[1.8, 1.8, 1.4, 1.7])
    
    add_callout(doc, 
        "Dokumen ini berlaku sejak tanggal ditetapkan dan menjadi acuan operasional resmi seluruh pengguna aplikasi Donation-Web.",
        title="PERNYATAAN RESMI", callout_type="info")
    
    # Save Document
    doc.save(output_path)
    print(f"Dokumen Word berhasil dibuat: {output_path}")

if __name__ == "__main__":
    output_docx = r"c:\xampp\htdocs\donation-web\DOKUMENTASI_PRD_DAN_PANDUAN_SISTEM.docx"
    build_prd_document(output_docx)
