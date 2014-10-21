__author__ = 'jordsti'
import os

class settings:

    def __init__(self, filepath='.worker'):
        self.url = ""
        self.key = ""
        self.git_path = None
        self.filepath = filepath

        if os.path.exists(self.filepath):
            self.__read()

    def __read(self):

        fp = open(self.filepath, 'r')
        lines = fp.readlines()
        for l in lines:
            if not l.startswith("#"):
                if l.startswith('url:'):
                    self.url = l[4:].rstrip('\n').rstrip('\r')
                    print self.url
                elif l.startswith('key:'):
                    self.key = l[4:].rstrip('\n').rstrip('\r')
                elif l.startswith('git:'):
                    self.git_path = l[4:].rstrip('\n').rstrip('\r')