__author__ = 'JordSti'
import urllib2
import threading

class file_uploader(threading.Thread):

    def __init__(self, build_id, filepath):
        self.build_id = build_id
        threading.Thread.__init__(self)
        self.filepath = filepath

    def run(self):

        fields = { 'build_id' : "%d" % build_id
        files = {'file': {''}}}

