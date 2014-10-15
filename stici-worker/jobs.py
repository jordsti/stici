__author__ = 'JordSti'
import subprocess
import os

class job:

    def __init__(self):
        self.__job_id = 0

    def run(self):
        pass

class pyci_job(job):

    def __init__(self):
        job.__init__(self)
        self.__env_dict = {}
        self.__commands = []

    def set_env(self, name, value):
        self.__env_dict[name] = value

    def push_command(self, cmd):
        self.__commands.append(cmd)

    def run(self):
        print "Running PyCI Job"
        print "Running commands"

        for c in self.__commands:
            args = []
            args.append(c.executable)
            for a in c.args:
                args.append(a)

            if c.executable == 'cd':
                print "[%s] : %s" % (c.executable, c.args[0])
                os.chdir(c.args[0])
            else:
                _process = subprocess.Popen(args, stdout=subprocess.PIPE, stderr=subprocess.PIPE, shell=True, env=self.__env_dict, cwd=os.getcwd())
                line = _process.stdout.readline()
                print args

                while len(line) > 0:
                    print line
                    #print "[%s] : %s" (c.executable, line)
                    line = _process.stdout.readline()

                errtxt = ""
                line = _process.stderr.readline()
                while len(line) > 0:
                    errtxt += line
                    line = _process.stderr.readline()

                print "[StdErr %s] : %s" % (c.executable, errtxt)

if __name__ == '__main__':
    job = pyci_job()

    job.set_env("PATH", "C:\\Qt\\Qt5.3.1\\Tools\\mingw482_32\\bin;C:\\Program Files (x86)\\CMake\\bin;")
    job.set_env("LIBPATH", "C:\\Qt\\Qt5.3.1\\Tools\\mingw482_32\\lib")
    job.set_env("INCLUDE", "C:\\Qt\\Qt5.3.1\\Tools\\mingw482_32\\include")
    job.set_env("CXX", "C:\\Qt\\Qt5.3.1\\Tools\\mingw482_32\\bin\\i686-w64-mingw32-g++")
    job.set_env("CC", "C:\\Qt\\Qt5.3.1\\Tools\\mingw482_32\\bin\\i686-w64-mingw32-gcc")
    job.set_env("RC", "C:\\Qt\\Qt5.3.1\\Tools\\mingw482_32\\bin\\windres")

    from command import command

    job.push_command(command('mkdir', ['stigame-build']))
    job.push_command(command('cd', ['stigame-build']))
    job.push_command(command('cmake', ['../stigame', '-G', 'MinGW Makefiles', '-DCMAKE_BUILD_TYPE:STRING=Debug']))
    job.push_command(command('mingw32-make'))
    job.push_command(command('mingw32-make', 'install'))

    job.run()