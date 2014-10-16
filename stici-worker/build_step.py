__author__ = 'JordSti'
import subprocess
import stici_exception
import os
import time
import io
import Queue
from threading import Thread

class build_step:
    (IgnoreReturn) = (1)

    def __init__(self, executable, args=[], envs={}, flags=0, timeout=300):
        self.executable = executable
        self.__env_dict = envs
        self.args = args
        self.flags = flags
        self.stdout = ""
        self.stderr = ""
        self.return_code = 0
        self.timeout = timeout

    def test_flags(self, flags):
        return self.flags & flags

    def print_cmd(self):
        args = " "
        for a in self.args:
            args += " %s" % a
        self.stdout += "%s%s\n" % (self.executable, args)
        print self.executable + args

    def do(self):

        args = []
        args.append(self.executable)
        for a in self.args:
            if len(a.rstrip(' ')) > 0:
                args.append(a)

        self.print_cmd()
        if 'cd' in self.executable:
            os.chdir(self.args[0])
        elif 'mkdir' in self.executable:
            try:
                os.mkdir(self.args[0])
            except Exception:
                pass
        else:
            self.__env_dict["PYTHONBUFFERED"] = "1"
            _process = subprocess.Popen(args, bufsize=1, stdout=subprocess.PIPE, stderr=subprocess.PIPE, shell=True, env=self.__env_dict)
            started = time.time()

            inp = Queue.Queue()

            sout = io.open(_process.stdout.fileno(), 'rb', closefd=False)
            serr = io.open(_process.stderr.fileno(), 'rb', closefd=False)

            def Pump(stream, category):
                queue = Queue.Queue()
                def rdr():
                    while True:
                        buf = stream.read1(1024)
                        if len(buf)>0:
                            queue.put(buf)
                        else:
                            queue.put(None)
                            return
                def clct():
                    active = True
                    while active:
                        r = queue.get()
                        try:
                            while True:
                                r1 = queue.get(timeout=0.005)
                                if r1 is None:
                                    active = False
                                    break
                                else:
                                    r += r1
                        except Queue.Empty:
                            pass
                        inp.put( (category, r) )
                for tgt in [rdr, clct]:
                    th = Thread(target=tgt)
                    th.setDaemon(True)
                    th.start()
            Pump(sout, 'stdout')
            Pump(serr, 'stderr')

            while _process.poll() is None:
                # App still working
                time.sleep(0.1)
                try:
                    chan, line = inp.get_nowait()
                    if chan=='stdout' and line is not None:
                        print "STDOUT>>", line
                    elif chan=='stderr' and line is not None:
                        print " STDERR>>", line
                except Queue.Empty:
                    pass

            self.return_code = _process.returncode


            """ while _process.returncode is None:
                _process.poll()
                err_line = _process.stderr.readline()
                out_line = _process.stdout.readline()
                #_process.stdin.write("")

                if len(err_line.rstrip('\n')) > 0:
                    print err_line.rstrip('\n')
                self.stderr += err_line

                if len(out_line.rstrip('\n')) > 0:
                    print out_line.rstrip('\n')
                self.stdout += out_line

                runtime = time.time() - started
                time.sleep(0.1)

                if runtime > self.timeout:
                    print "TIMEOUT"  """

            self.return_code = _process.returncode

            print self.stdout
            if self.return_code >= 0:
                pass
            elif self.test_flags(self.IgnoreReturn):
                pass
            else:
                raise stici_exception.step_failed(self)

