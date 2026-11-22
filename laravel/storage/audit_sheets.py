import urllib.request
import re
import csv
import io

links = {
    "INDUK": ("1xlpBPyAb-i0U8xGTk7OMhcLXs4X6mwsZtBnqag-7DVo", "785093566"),
    "BLAST": ("1kC9bgPGVjm-G3iyicUqC-dOlYSWs4l_tpYn7AajZ_kE", "382460186"),
    "ANALITIK": ("18ZSGQoWFcOW9XeuyMD3i_N2amwx7UrpUwO9FYph1eiM", "1077922313"),
    "TRANSAKSI_DONASI": ("1eBGPt8_K0Xrm-DfpfIo7iNJ3Bzw0DO4F1W2Y86Udwv4", "1036764639"),
}

for label, (sheet_id, default_gid) in links.items():
    print(f"\n==================== {label} ({sheet_id}) ====================")
    url = f"https://docs.google.com/spreadsheets/d/{sheet_id}/edit?gid={default_gid}"
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    try:
        with urllib.request.urlopen(req, timeout=12) as resp:
            html = resp.read().decode('utf-8', errors='ignore')
        
        matches = re.findall(r'\[(\d+),0,\\\"(\d+)\\\",\[\{\\\"1\\\":\[\[0,0,\\\"([^"\\]+)\\\"', html)
        print(f"Sheets found ({len(matches)}):")
        for m in matches:
            gid = m[1]
            sname = m[2]
            
            # Fetch header of each sheet
            csv_url = f"https://docs.google.com/spreadsheets/d/{sheet_id}/export?format=csv&gid={gid}"
            req_c = urllib.request.Request(csv_url, headers={'User-Agent': 'Mozilla/5.0'})
            header_sample = ""
            try:
                with urllib.request.urlopen(req_c, timeout=5) as c_resp:
                    csv_text = c_resp.read().decode('utf-8', errors='ignore')
                    lines = [l for l in csv_text.splitlines() if l.strip()]
                    if lines:
                        # find header line (sometimes row 1 or 2 is title/desc)
                        for line in lines[:3]:
                            if any(k in line.lower() for k in ['id', 'nama', 'tanggal', 'date', 'no_hp', 'phone', 'nominal', 'amount', 'status', 'kategori', 'bulan', 'invoice']):
                                header_sample = line
                                break
                        if not header_sample and len(lines) > 0:
                            header_sample = lines[0]
            except Exception as e:
                header_sample = f"(error fetching csv: {e})"
            
            print(f"  - [{gid}] {sname}: {header_sample[:100]}")
    except Exception as e:
        print(f"Error accessing {label}: {e}")
