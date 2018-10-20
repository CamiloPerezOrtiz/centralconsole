#creating directories;
cd /;
mkdir centralConsole
#Terminal utilities 
yum install python -y;
yum install sshpass -y;
curl -O https://bootstrap.pypa.io/get-pip.py
python get-pip.py
rm -f get-pip.py
mkdir keys
#Python modules
pip install daemon-runner;
pip install lxml;
pip install selenium;
## create key
