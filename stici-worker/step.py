__author__ = 'jordsti'
import time
import Queue
import threading

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



class step:

    def __init__(self):
        self.stdout = ""
        self.stderr = ""

    def wait_process(self, _process):
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