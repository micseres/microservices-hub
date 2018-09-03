import json
import socket
import threading
from random import randint
import time
import sys
import datetime

print('Start')

def connect(sleep):
    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    sock.connect(("127.0.0.1", 9501))
    packet = {"route": "sleep", "payload": {"sleep": sleep}}
    sock.send(json.dumps(packet))
    cresponse = sock.recv(1024)
    cresponse = json.loads(cresponse)
    if cresponse['status'] == "error":
        print('\x1b[0;31;38m' + "Status {} '{}'".format(cresponse['status'], cresponse['message']) + '\x1b[0m')
        return
    else:
        print('\x1b[0;37;38m' + "Status {} '{}' at {} and give task id {} for {}".format(cresponse['status'], cresponse['message'], cresponse['start_at'], cresponse['task_id'], sleep) + '\x1b[0m')
    task_id = cresponse['task_id']
    while True:
      response = sock.recv(1024)
      response = json.loads(response)
      if response['task_id'] == task_id:
        print('\x1b[0;33;38m' + "*************" + '\x1b[0m')
        print('\x1b[0;38;38m' + "Now is {}".format(datetime.datetime.now()) + '\x1b[0m')
        print('\x1b[0;32;38m' + "Protocol sent at {} and received {}".format(response['start_at'], response['done_at']) + '\x1b[0m')
        print('\x1b[0;32;38m' + "Status {} for task id {} with interval {} start work at {} and end work ad {}".format(response['status'], response['task_id'], sleep, response['payload'][0]['start_at'], response['payload'][0]['done_at']) + '\x1b[0m')
        print('\x1b[0;38;38m' + "{}".format(response['payload'][0]['message']) + '\x1b[0m')
        print('\x1b[0;33;38m' + "*************" + '\x1b[0m')
        return
    sock.close()

threads = {}

def start():
    for thread in range(0, 16):
        sleep = randint(0, 15)
        threads[thread] = threading.Thread(name=thread, target=connect, args=([sleep]))
        #threads[thread].setDaemon(True)
        threads[thread].start()

start()