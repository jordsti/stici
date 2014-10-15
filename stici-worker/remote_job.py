__author__ = 'JordSti'

class remote_job:

    (Pending, Running, Ended) = (0, 1, 2)

    def __init__(self, line=None):
        self.job_id = 0
        self.current_id = 0
        self.status = self.Pending

        if line is not None:
            self.parseLine(line)

    def parseLine(self, line):

        line = line.rstrip('\n');

        vars = line.split(',')

        for v in vars:
            data = v.split(':')

            if data[0] == 'JOB_ID':
                self.job_id = int(data[1])
            elif data[0] == 'CURRENT_ID':
                self.current_id = int(data[1])