# mabl

#mikrotikasteriskblacklist
#mikrostikautoblacklist
#frod #subnets #frod.subnets

1) 
<pre>
  cd /opt/
  git clone https://github.com/NortH21/mabl.git
</pre>
2) Edit /opt/mabl/address.php
3) /etc/crontab
<pre>
  10 1    * * *   root    php /opt/mabl/address.php
</pre>
4) mikrotik
<pre>
/ip service
set api address=10.10.1.0/24
</pre>
<pre>
/user group
add name=api-group policy=read,write,api,!local,!telnet,!ssh,!ftp,!reboot,!policy,!test,!winbox,!password,!web,!sniff,!sensitive,!romon,!dude,!tikapp
</pre>
<pre>
/user
add address=10.10.1.0/24 group=api-group name=api
</pre>
<pre>
/ip firewall filter
add action=drop chain=input in-interface=ether1 src-address-list=blacklist comment=Blacklist
add action=drop chain=forward in-interface=ether1 src-address-list=blacklist
</pre>
