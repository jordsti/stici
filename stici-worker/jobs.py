__author__ = 'JordSti'
import subprocess
import os
from step import step
import worker
import stici_exception
import shutil

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

class git_fetch_job(job, step):
    def __init__(self, name, remote_git):
        step.__init__(self)
        self.name = name
        self.remote_git = remote_git
        self.__env_dict = {}
        self.clone = True
        self.git_path = None
        self.shell = True

    def set_env(self, name, value):
        self.__env_dict[name] =  value

    def run(self):

        if len(self.__env_dict) == 0:
            self.__env_dict = os.environ

        args = []
        if self.git_path is not None:
            args.append(os.path.join(self.git_path, 'git'))
        else:
            args.append('git')

        if self.clone:
            args.append('clone')
            args.append("--depth")
            args.append("1")
            args.append(self.remote_git)
            #abs path for cloning
            args.append(self.name)
        else:
            os.chdir(self.name)
            args.append('pull')

        self.show_cmd(args)
        #print args
        #if self.shell:
        _process = subprocess.Popen(args, stdout=subprocess.PIPE, stderr=subprocess.PIPE, shell=self.shell)
        #else:
            #_process = subprocess.Popen(args, executable='git' ,stdout=subprocess.PIPE, stderr=subprocess.PIPE, shell=self.shell)

        self.wait_process(_process)

        if self.clone:
            os.chdir(self.name)

class stici_job(job):


    def __init__(self, worker, name, build_id, build_number=0):
        job.__init__(self)
        self.worker = worker
        self.name = name
        self.build_id = build_id
        self.build_number = build_number
        self.__env_dict = {}
        self.__steps = []
        self.flags = 0
        self.archives = []

    def set_env(self, name, value):
        self.__env_dict[name] = value

    def get_env(self):
        return self.__env_dict

    def push_step(self, cmd):
        self.__steps.append(cmd)

    def run(self):
        for s in self.__steps:
            _step_failed = False
            try:
                s.do(self)
            except Exception as e:
                print e.message
                _step_failed = True

            slt = worker.send_log_thread(self.worker, self.build_id, s.step_id, s.stdout, s.stderr, s.return_code)
            slt.start()

            if _step_failed:
                raise stici_exception.step_failed(s)
