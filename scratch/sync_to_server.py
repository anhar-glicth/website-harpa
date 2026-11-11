import paramiko
import os

files_to_sync = [
    "laravel/app/Http/Controllers/Harpa/HarpaDataController.php",
    "laravel/resources/views/admin/harpa/blast_history.blade.php",
    "laravel/resources/views/admin/harpa/daftar_kirim.blade.php",
    "laravel/resources/views/admin/harpa/import_donasi.blade.php",
    "laravel/resources/views/admin/harpa/wbs_pro.blade.php",
    "laravel/routes/web.php",
]

local_base = "c:/xampp/htdocs/website-harpa"
remote_base = "/home/u290212134/domains/harapanpalestina.com/public_html"

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

try:
    print("Connecting to Hostinger SFTP/SSH...")
    client.connect(
        hostname='153.92.10.174',
        port=65002,
        username='u290212134',
        password='f1D022013.',
        timeout=20
    )
    sftp = client.open_sftp()
    print("SFTP opened. Uploading files...")

    for rel_path in files_to_sync:
        local_path = os.path.join(local_base, rel_path).replace("\\", "/")
        remote_path = f"{remote_base}/{rel_path}"
        print(f"Uploading: {rel_path} -> {remote_path}")
        sftp.put(local_path, remote_path)
        print(f"Uploaded successfully!")

    sftp.close()

    print("\nClearing Laravel view and route caches on server...")
    clear_cmd = (
        "cd /home/u290212134/domains/harapanpalestina.com/public_html/laravel && "
        "/opt/alt/php83/usr/bin/php artisan view:clear && "
        "/opt/alt/php83/usr/bin/php artisan route:clear && "
        "/opt/alt/php83/usr/bin/php artisan view:cache"
    )
    stdin, stdout, stderr = client.exec_command(clear_cmd)
    out = stdout.read().decode('utf-8', errors='ignore')
    err = stderr.read().decode('utf-8', errors='ignore')
    print("STDOUT:\n" + out)
    if err:
        print("STDERR:\n" + err)

    print("\nALL FILES SYNCED & DEPLOYED SUCCESSFULLY TO LIVE SERVER!")
finally:
    client.close()

