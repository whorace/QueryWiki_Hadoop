#! /usr/bin/env python

#import secure
import pexpect
import sys


# The login creds
#user = secure.USER
#password = secure.PASSWORD
user = "hadoop04"
password = "div2Q*8jzVpv"

def ssh_command(user, host, password, command):
    """This runs a command on the remote host."""
    print " I am logging into", host

    ssh_newkey = 'Are you sure you want to continue connecting'
    child = pexpect.spawn('ssh -l %s %s %s' % (user, host, command))

    i = child.expect([pexpect.TIMEOUT, ssh_newkey, 'password: '])
    if i == 0:  # Timeout
        print('ERROR!')
        print('SSH could not login. Here is what SSH said:')
        print(child.before, child.after)
        return None
    if i == 1:  # SSH does not have the public key. Just accept it.
        child.sendline('yes')
        child.expect('password: ')
        i = child.expect([pexpect.TIMEOUT, 'password: '])
        if i == 0:  # Timeout
            print('9ERROR!')
            print('SSH could not login. Here is what SSH said:')
            print(child.before, child.after)
            return None
    child.sendline(password)
    return child


def main():
    server = "beret.cs.brandeis.edu"
    len_query=len(sys.argv)
    query_words=sys.argv[1]
    for i in range(2,len_query):
        query_words+=" "+sys.argv[i];
    print query_words;
    command = 'cd ./hadoop-dist/ && pwd && ../run_all3.sh ' + '\'"' + query_words + '"\''
    #command = 'cd ./hadoop-dist/ && pwd && ../run_all.sh ' + "'" + query_words + "'"
    print command
    child = ssh_command(user, server, password, command)
    child.expect(pexpect.EOF, timeout=600)
    output = child.before
    print output
    f=open('test_output.txt','w') 
    f.write(output)
    f.close()

if __name__ == "__main__":
    main()