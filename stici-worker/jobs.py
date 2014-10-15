__author__ = 'JordSti'
import subprocess
import os
from command import command

class job:

    def __init__(self):
        self.__job_id = 0

    def show_cmd(self, args):

        text = ""
        for a in args:
            text += a
            text += " "
        print text

    def run(self):
        pass

class git_fetch_job(job):
    def __init__(self, name, remote_git):
        self.name = name
        self.remote_git = remote_git
        self.__env_dict = {}
        self.clone = True
        self.git_path = None

    def set_env(self, name, value):
        self.__env_dict[name] =  value

    def run(self):
        args = []
        if self.git_path is not None:
            args.append(os.path.join(self.git_path, 'git'))
        else:
            args.append('git')

        if self.clone:
            args.append('clone')
            args.append("--depth=50")
            args.append(self.remote_git)
            args.append(self.name)
        else:
            args.append('pull')

        self.show_cmd(args)
        _process = subprocess.Popen(args, stdout=subprocess.PIPE, stderr=subprocess.PIPE, shell=True)
        line = _process.stdout.readline()

        while len(line) > 0:
            print line.rstrip('\n')
            #print "[%s] : %s" (c.executable, line)
            line = _process.stdout.readline()

        errtxt = ""
        line = _process.stderr.readline()
        while len(line) > 0:
            errtxt += line
            line = _process.stderr.readline()

        print "[StdErr] : %s" % (errtxt)

        if self.clone:
            os.chdir(self.name)

class stici_job(job):

    def __init__(self, name, build_id, build_number=0):
        job.__init__(self)
        self.name = name
        self.build_id = build_id
        self.build_number = build_number
        self.__env_dict = {}
        self.__commands = []

    def set_env(self, name, value):
        self.__env_dict[name] = value

    def push_command(self, cmd):
        self.__commands.append(cmd)

    def run(self):
        for c in self.__commands:
            args = []
            args.append(c.executable)
            for a in c.args:
                if len(a) > 0:
                    args.append(a)

            if c.executable == 'cd':
                print "[%s] : %s" % (c.executable, c.args[0])
                os.chdir(c.args[0])
            else:
                self.show_cmd(args)
                _process = subprocess.Popen(args, stdout=subprocess.PIPE, stderr=subprocess.PIPE, shell=True, env=self.__env_dict, cwd=os.getcwd())
                line = _process.stdout.readline()

                while len(line) > 0:
                    print line.rstrip('\n')
                    #print "[%s] : %s" (c.executable, line)
                    line = _process.stdout.readline()

                errtxt = ""
                line = _process.stderr.readline()
                while len(line) > 0:
                    errtxt += line
                    line = _process.stderr.readline()

                print "[Error %s] : %s" % (c.executable, errtxt.rstrip('\n'))
                return_code = _process.wait()

                if return_code < 0:
                    print "Job Failed !"
                    break
                    #todo
