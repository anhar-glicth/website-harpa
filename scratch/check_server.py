import paramiko

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

try:
    print("Connecting to 153.92.10.174:65002...")
    client.connect(
        hostname='153.92.10.174',
        port=65002,
        username='u290212134',
        password='f1D022013.',
        timeout=15
    )
    print("Connected successfully!")

    cmd = "cd /home/u290212134/domains/harapanpalestina.com/public_html/laravel && git status && git log -n 3 --oneline"
    stdin, stdout, stderr = client.exec_command(cmd)
    print("STDOUT:\n" + stdout.read().decode('utf-8', errors='ignore'))
    print("STDERR:\n" + stderr.read().decode('utf-8', errors='ignore'))
finally:
    client.close()

