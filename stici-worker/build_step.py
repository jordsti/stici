__author__ = 'JordSti'
import subprocess
import stici_exception
import os
import time
import sys
import threading
import Queue
from threading import Thread

class AsynchronousFileReader(threading.Thread):
    '''
    Helper class to implement asynchronous reading of a file
    in a separate thread. Pushes read lines on a queue to
    be consumed in another thread.
    '''

    def __init__(self, fd, queue):
        assert isinstance(queue, Queue.Queue)
        assert callable(fd.readline)
        threading.Thread.__init__(self)
        self._fd = fd
        self._queue = queue

    def run(self):
        '''The body of the tread: read lines and put them on the queue.'''
        for line in iter(self._fd.readline, ''):
            self._queue.put(line)

    def eof(self):
        '''Check whether there is no more content to expect.'''
        return not self.is_alive() and self._queue.empty()

class build_step:
    (IgnoreReturn) = (1)

    def __init__(self, executable, args=[], envs={}, flags=0, timeout=300):
        self.executable = executable
        self.__env_dict = envs
        self.args = args
        self.flags = flags
        self.stdout = ""
        self.stderr = ""
        self.step_id = 0
        self.return_code = 0
        self.timeout = timeout
        self.shell = True

    def test_flags(self, flags):
        return self.flags & flags

    def print_cmd(self):
        args = " "
        for a in self.args:
            args += " %s" % a
        self.stdout += "%s%s\n" % (self.executable, args)
        print self.executable + args

    def do(self, stici_job):

        args = []
        args.append(self.executable)

        for a in self.args:
            if len(a.rstrip(' ')) > 0:
                na = a.replace('$BUILDNUMBER$', str(stici_job.build_number))
                na = na.replace('$NAME$', stici_job.name)
                args.append(na)

        self.args = args

        self.print_cmd()
        if 'cd' in self.executable:
            os.chdir(self.args[-1])
        elif 'mkdir' in self.executable:
            try:
                os.mkdir(self.args[-1])
            except Exception:
                pass
        else:
            if len(self.__env_dict) == 0:
                #wmpty env, getting OS env
                self.__env_dict = os.environ

            _process = subprocess.Popen(self.args, stdout=subprocess.PIPE, stderr=subprocess.PIPE, shell=self.shell, env=self.__env_dict)


            started = time.time()

            stdout_queue = Queue.Queue()
            stdout_reader = AsynchronousFileReader(_process.stdout, stdout_queue)
            stdout_reader.start()
            stderr_queue = Queue.Queue()
            stderr_reader = AsynchronousFileReader(_process.stderr, stderr_queue)
            stderr_reader.start()

            while not stdout_reader.eof() or not stderr_reader.eof() or _process.poll() is None:
                while not stdout_queue.empty():
                    line = stdout_queue.get()
                    print line.rstrip('\n')
                    self.stdout += line


                while not stderr_queue.empty():
                    line = stderr_queue.get()
                    print line.rstrip('\n')
                    self.stderr += line

                time.sleep(.1)

            stdout_reader.join()
            stderr_reader.join()

            # Close subprocess' file descriptors.
            _process.stdout.close()
            _process.stderr.close()

            self.return_code = _process.returncode
            time_elapsed = time.time() - started
            print "%d seconds" % time_elapsed

            if self.return_code == 0:
                pass
            elif self.test_flags(self.IgnoreReturn):
                pass
            else:
                raise stici_exception.step_failed(self)

