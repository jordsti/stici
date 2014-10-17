import platform
import time
import random
import hashlib
import urllib
import urllib2
import remote_job
import threading
import jobs
import os
import stici_exception

def generate_worker_hash():
    stamp = time.time()
    host = platform.node()
    rand = random.randint(0, 100000)
    rand2 = random.randint(0, 100000)

    m = hashlib.sha256();

    m.update(str(stamp))
    m.update(host)
    m.update(str(rand))
    m.update(str(rand2))

    return m.hexdigest()


def url_join(part1, part2):
    return "%s/%s" % (part1, part2);

class stici_worker:
    #build status
    (Building, Success, Failed) = (0, 1, 2)
    #os enum
    (Linux, Windows) = (1, 2)

    #worker page
    (RegisterRequest) = ('worker_register.php')
    (PollJob) = ('worker_pool.php')
    (ClaimJob) = ('worker_claim.php')
    (StartBuild) = ('worker_start.php')
    (BuildEnded) = ('worker_end.php')
    (BuildStepLog) = ('worker_log.php')

    def __init__(self, master_url, git_path=None, workspace="workspace"):
        self.host = platform.node()
        self.master_url = master_url
        self.hash = generate_worker_hash()
        self.registered = False
        self.interval = 30  #in seconds..
        self.polls = []
        self.current_job = None
        self.git_path = git_path
        self.workspace = workspace
        self.build_id = 0
        self.status = self.Building
        self.os = 0


    def detect_os(self):

        os_name = platform.system()

        os_name = os_name.lower()

        if os_name == 'windows':
            self.os = self.Windows
            print "Worker running on Windows"
        elif os_name == 'linux':
            self.os = self.Linux
            print "Worker running on Linux"
        else:
            print "%s OS not supported at the moment" % os_name
            print "Exiting..."
            sys.exit()

    def run(self):
        self.detect_os()
        self.register()

        if not self.registered:
            return

        while True:
            self.poll()

            if len(self.polls) > 0:
                last = self.polls[0]
                print "Claiming a job..."
                self.claim_job(last)

                if self.current_job is not None:
                    print "Working...."
                    self.start_build()

            print "Sleeping for %d seconds..." % self.interval
            time.sleep(self.interval)

    def build_ended(self):

        url = url_join(self.master_url, self.BuildEnded)
        param = "?build_id=%d&hash=%s&status=%d" % (self.build_id, self.hash, self.status)

        print "Sending build result"
        u = urllib2.urlopen(url+param)
        rs = u.read()

    def start_build(self):

        if not os.path.exists(self.workspace):
            os.makedirs(self.workspace)

        cwd = os.getcwd()

        os.chdir(self.workspace)

        if self.current_job is not None:
            url = url_join(self.master_url, self.StartBuild)
            param = "?current_id=%d&hash=%s" % (self.current_job.current_id, self.hash)

            u = urllib2.urlopen(url+param)
            rs = u.read()
            #splitting lines
            lines = rs.split('\n')
            il = 0

            job = None
            git_job = None

            for l in lines:
                if il == 0:
                    #project name and build_id
                    data = l.split(':')
                    self.build_id = int(data[2])
                    job = jobs.stici_job(self, data[1], int(data[2]))
                    if self.os == self.Windows:
                        job.set_env('SystemRoot', os.environ['SystemRoot'])
                elif l.startswith('BuildNumber:'):
                    job.build_number = int(l[12:])
                elif l.startswith('Git='):
                    git_job = jobs.git_fetch_job(job.name, l[4:])
                    git_job.git_path = self.git_path
                elif l.startswith('ENV+'):
                    envl = l[4:]
                    data = envl.split('=')
                    job.set_env(data[0], data[1])
                elif l.startswith('STEP+'):
                    stepl = l[5:]
                    data = stepl.split('|')
                    from build_step import build_step
                    bs = build_step(data[0], data[1].split(';'), job.get_env(), int(data[2]))
                    bs.step_id = int(data[3])
                    job.push_step(bs)

                il += 1



            if job is not None:
                time_start = time.time()
                print "Job Information :"
                print "\tName : %s" % job.name
                print "\tBuild Id : %d" % job.build_id
                print "\tBuild Number : %d" % job.build_number


                print "Starting this job now !"

                if git_job is not None:
                    if os.path.exists(job.name):
                        git_job.clone = False
                    else:
                        git_job.clone = True

                    print "Fetching repository..."
                    git_job.set_env('PATH', self.git_path)
                    git_job.run()
                try:
                    job.run()

                    self.status = self.Success
                except stici_exception.step_failed as sf:
                    print "Step failed !"
                    print sf.build_step.executable
                    print sf.build_step.stderr
                    self.status = self.Failed
                #except Exception as e:
                    #print "Fatal Error"
                    #print str(e)
                    #self.status = self.Failed
                print "Job Terminated"

                time_end = time.time()

                build_time = time_end - time_start

                print "In %d seconds" % build_time


                #sending build ended
                self.build_ended()
                os.chdir(cwd)


    def register(self):
        if not self.registered:
            url = url_join(self.master_url, self.RegisterRequest)

            param = "?hash=%s&hostname=%s&os=%d" % (self.hash, self.host, self.os)
            u = urllib2.urlopen(url+param)
            rs = u.read()

            if 'OK!' in rs:
                self.registered = True
                print "Worker is registered to %s" % self.master_url
            else:
                print "Can't register to a master!"

        else:
            print "Worker already registered..."

    def claim_job(self, re_job):
        if re_job.status == remote_job.remote_job.Pending and self.registered:
            url = url_join(self.master_url, self.ClaimJob)
            param = "?hash=%s&current_id=%d" % (self.hash, re_job.current_id)
            u = urllib2.urlopen(url+param)
            rs = u.read()

            if 'OK!' in rs:
                print "Job Claimed !"
                self.current_job = re_job
            else:
                print "Unable to claim job"


    def poll(self):
        if self.registered:
            print "Polling %s" % self.master_url
            url = url_join(self.master_url, self.PollJob)
            param = "?hash=%s" % self.hash

            u = urllib2.urlopen(url+param)
            rs = u.read()

            lines = rs.split('\n')

            self.polls = []
            for l in lines:
                if len(l) > 0 and ':' in l:
                    rj = remote_job.remote_job(l)
                    if rj.target == self.os:
                        self.polls.append(rj)


            if 'None' in rs:
                print "No job pending..."

        else:
            print "Not registered cannot poll !"



class send_log_thread(threading.Thread):

    def __init__(self, _worker, build_id, step_id, stdout, stderr, return_code):
        self.worker = _worker
        self.step_id = step_id
        self.build_id = build_id
        self.stdout = stdout
        self.stderr = stderr
        self.return_code = return_code
        threading.Thread.__init__(self)

    def run(self):
        url = url_join(self.worker.master_url, stici_worker.BuildStepLog)
        data = {'step_id':self.step_id, 'build_id':self.build_id, 'hash':self.worker.hash, 'stdout':self.stdout, 'stderr':self.stderr, 'return_code':self.return_code}
        data = urllib.urlencode(data)
        r = urllib.urlopen(url, data)
        print r.read()


import sys
if __name__ == '__main__':

    print "Sti::CI Worker"
    _workspace = "workspace"
    _master_url = "http://localhost/stici/stici-master"
    _git_path = None
    ia = 0
    ma = len(sys.argv)
    while ia < ma:
        arg = sys.argv[ia]

        if arg == '-w':
            ia += 1
            if ia < ma:
                _workspace = sys.argv[ia]
        elif arg == '-git':
            ia += 1
            if ia < ma:
                _git_path = sys.argv[ia]
        elif arg == '-url':
            ia += 1
            if ia < ma:
                _master_url = sys.argv[ia]

        ia += 1


    worker = stici_worker(_master_url, _git_path, _workspace)
    worker.run()
