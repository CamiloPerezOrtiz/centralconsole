import commands

txt_ip_change = open("/home/software/scriptsSoporte/centosFiles/CAMILO.txt", "r")


for ip in txt_ip_change:
	

	temp = open("/home/software/scriptsSoporte/centosFiles/ipListNoSSH.txt", "r") # use to count lines
	lines = len(list(temp)) # use to count lines
	temp.close()
	print(lines)
	txt_ip_no_SSH = open("/home/software/scriptsSoporte/centosFiles/ipListNoSSH.txt", "r")
	print("Trying to apply changes to " + ip)

	if lines == 0: #there is no line in file that contains clients without ssh connection
		print("All clients have ssh connection")
		txt = open("/home/software/scriptsSoporte/centosFiles/userClient.txt", "r")
		userClient = txt.read()
		userClient = userClient[:len(userClient)-1]
		ipClient = ip[:len(ip)-1]
		txt.close()
		txt = open("/home/software/scriptsSoporte/centosFiles/portClient.txt", "r")
		portClient = txt.read()
		portClient = portClient[:len(portClient)-1]
		##Changes in each warrior
		f = commands.getoutput("ssh "+userClient+"@"+ipClient+ " ' if [ -d /backupCentralizedConsole ]; \
				then echo \"Enter to backup directory\"; \
				else mkdir /backupCentralizedConsole; echo \"backup directory generated\"; \
				fi; \
				cp /cf/conf/config.xml /backupCentralizedConsole/cfconf.xml ; \
				cp /conf/config.xml /backupCentralizedConsole/conf.xml ;' ")
		print("Generate backup ...... DONE")
		f = commands.getoutput("ssh "+userClient+"@"+ipClient + " 'rm -f /cf/conf/config.xml ;  rm -f /conf/config.xml ;'  " )
		print("Delete file in /cf/conf/config.xml ...... DONE")
		f = commands.getoutput("scp /home/software/scriptsSoporte/centosFiles/pf.xml "+userClient+"@"+ipClient+":/cf/conf/config.xml")
		f = commands.getoutput("scp /home/software/scriptsSoporte/centosFiles/pf.xml "+userClient+"@"+ipClient+":/conf/config.xml")
		print("*********************************")
		print(f)
		print("*********************************")
		print("Copy in /cf/conf/config.xml ...... DONE")
		#f = commands.getoutput("ssh "+userClient+"@"+ipClient+ "  ' rm -f /tmp/config.cache'  " )
		print("Delete cache ...... DONE")
		print("Changes has successfully been applied in: " + ipClient + "\n\n\n")

	counter = 1 # use to know how many IP's are in TXT
	for noSSH in txt_ip_no_SSH:
		print("*************")
		if ip == noSSH:
			print("This ip has no ssh connection. Changes has not been applied")
			break
		elif counter == lines:
			txt = open("/home/software/scriptsSoporte/centosFiles/userClient.txt", "r")
			userClient = txt.read()
			userClient = userClient[:len(userClient)-1]
			ipClient = ip[:len(ip)-1]
			txt.close()
			txt = open("/home/software/scriptsSoporte/centosFiles/portClient.txt", "r")
			portClient = txt.read()
			portClient = portClient[:len(portClient)-1]
			##Changes in each warrior
			f = commands.getoutput("ssh "+userClient+"@"+ipClient+ " ' if [ -d /backupCentralizedConsole ]; \
				then echo \"Enter to backup directory\"; \
				else mkdir /backupCentralizedConsole; echo \"backup directory generated\"; \
				fi; \
				cp /cf/conf/config.xml /backupCentralizedConsole/ ;' ")
			print("Generate backup ...... DONE")
			f = commands.getoutput("ssh "+userClient+"@"+ipClient + " 'rm -f /cf/conf/config.xml' " )
			print("Delete file in /cf/conf/config.xml ...... DONE")
			f = commands.getoutput("scp /home/software/scriptsSoporte/centosFiles/pf.xml "+userClient+"@"+ipClient+":/cf/conf/pf.xml")
			print("Copy in /cf/conf/config.xml ...... DONE")
			f = commands.getoutput("ssh "+userClient+"@"+ipClient+ "  ' rm -f /tmp/config.cache'  " )
			print("Delete cache ...... DONE")


			print(f + "Changes has been applied\n\n\n")
		counter += 1 # use to know how many IP's are in TXT
	txt_ip_no_SSH.close()		
txt_ip_change.close()