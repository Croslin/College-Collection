#!/usr/bin/python3

import sys

import socket

import threading

import requests

boundPort = sys.argv[1]

print(boundPort)

from socket import *

serverPort =int(boundPort)
sock = socket(AF_INET,SOCK_STREAM)
sock.bind(('',serverPort))
sock.listen(1)
print("Server is listening at Port{0}".format(serverPort))
connections = []
    
def handler(c,a):
    global connections
    while True:
        data = c.recv(1024)
        for connection in connections:
            connection.send(bytes(data))
            getrequest = requests.get(data.decode)
            connection.send(bytes(getrequest))
        if not data:
            connections.remove(c)
            c.close()
            break
        
while True:
    c, a = sock.accept()
    cThread = threading.Thread(target = handler, args=(c,a))
    cThread.daemon = True
    cThread.start()
    connections.append(c)
    print(connections)

