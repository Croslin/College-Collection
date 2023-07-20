#!/usr/bin/python3

from socket import *

import threading



serverName = 'localhost'
serverPort = 3030

sock = socket(AF_INET,SOCK_STREAM)
sock.bind(('',serverPort))
sock.listen(1)
print("Controller is listening for bots at port{0}".format(serverPort))
connectionsList = []

def handler(connectionSocket,addr):
    global connectionsList
    print("New connection established")
    while True:
        data = connectionSocket.recv(1024)
        print(data.decode())
        if not data:
            connectionsList.remove(connectionSocket)
            connectionSocket.close()
            break
        

def commandF():
    runner = ""
    space = ""
    bot = ""
    script = ""
    args = ""
    allTime = 0
    runner = input("Awaiting command\nCommands: EXECUTION STOP REPORT\n")
    print(connectionsList)
    space = input("Type which bot to send to or type  ALL\n")
    #bot storage
    if(space == "ALL"):
       print("ALL is understood")
       allTime = 1
    elif(int(space) > len(connectionsList)):
       print("That space is not present")
       commandF() 
    else:
        bot = connectionsList[int(space)]
    script = input("What script to use\n")
    args = input("What agruments to use\n")
    if(runner == "EXECUTION"):
       if(allTime == 0):
           print("Running EXECUTION with bot and script ",bot," and ",script, " and args ", args)
           EXECUTION(bot,script,args)
       else:
           print("Executing ",script," on them all with args ", args)
           #exing all
           for x in connectionsList:
               message = "EXECUTE "+script+" "+args+"\n"
               #bot = connectionsList[int(x)]
               x.send(message.encode())
           commandF()
    elif(runner == "STOP"):
       if(allTime == 0):
           print("Running STOP with bot and script ",bot," and ",script," and args ", args)
           STOP(bot,script,args)
       else:
           print("Stopping all runs of ", script)
           #stoping all
           for x in connectionsList:
               message = "STOP "+script+"\n"
               #bot = connectionsList[int(x)]
               x.send(message.encode())
           commandF()
    elif(runner == "REPORT"):
       if(allTime == 0):
           print("Running REPORT with bot and script ",bot," and ",script," and args ", args)
           REPORT(bot,script,args)
       else:
           print("Reporting all runs of ", script)
           #reporting all
           for x in connectionsList:
               message = "REPORT "+script+"\n"
               #bot = connectionsList[int(x)]
               x.send(message.encode())
           commandF()
    else:
       print("No available command found")
    
    

def EXECUTION(bot,script,args):
    message = "EXECUTE "  + script + " " + args + "\n"
    bot.send(message.encode())
    commandF()
def STOP(bot,script,args):
    message = "STOP "+ script +"\n"
    bot.send(message.encode())
    commandF()
def REPORT(bot,script,args):
    message = "REPORT "+ script+"\n"
    bot.send(message.encode())
    commandF()
def listenThread():
    while True:
        connectionSocket, addr = sock.accept()
        cThread = threading.Thread(target = handler, args=(connectionSocket,addr))
        cThread.daemon = True
        cThread.start()
        connectionsList.append(connectionSocket)
        print(connectionsList)
    
def main():
    lThread = threading.Thread(target = listenThread)
    lThread.daemon = True
    lThread.start()
    commandF()

main()
