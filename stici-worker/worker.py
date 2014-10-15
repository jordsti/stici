import platform
import time
import random
import hashlib
import urllib2
import remote_job
import jobs
import os
import shutil

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
    (RegisterRequest) = ('worker_register.php')
    (PollJob) = ('worker_pool.php')
    (ClaimJob) = ('worker_claim.php')
    (StartBuild) = ('worker_start.php')

    def __init__(self, master_url, git_path):
        self.host = platform.node()
        self.master_url = master_url
        self.hash = generate_worker_hash()
        self.registered = False
        self.interval = 30  #in seconds..
        self.polls = []
        self.current_job = None
        self.git_path = git_path


    def run(self):

        self.register()

        if not self.registered:
            return

        while True:
            self.poll()

            if len(self.polls) > 0:
                last = self.polls[-1]
                print "Claiming a job..."
                self.claim_job(last)

                if self.current_job is not None:
                    print "Working...."
                    self.start_build()

            time.sleep(self.interval)

    def start_build(self):
        cwd = os.getcwd()
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
                    job = jobs.stici_job(data[1], int(data[2]))
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
                    from command import command
                    job.push_command(command(data[0], data[1].split(';')))


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
                        os.chdir(job.name)
                    else:
                        git_job.clone = True

                    print "Fetching repository..."
                    git_job.set_env('PATH', self.git_path)
                    git_job.run()

                job.run()
                print "Job Terminated"

                time_end = time.time()

                build_time = time_end - time_start

                print "In %d seconds" % build_time

                os.chdir(cwd)


    def register(self):
        if not self.registered:
            url = url_join(self.master_url, self.RegisterRequest)

            param = "?hash=%s&hostname=%s" % (self.hash, self.host)
            u = urllib2.urlopen(url+param)
            rs = u.read()

            if 'OK!' in rs:
                self.registered = True
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
            url = url_join(self.master_url, self.PollJob)
            param = "?hash=%s" % self.hash

            u = urllib2.urlopen(url+param)
            rs = u.read()

            lines = rs.split('\n')

            self.polls = []
            for l in lines:
                if len(l) > 0 and ':' in l:
                    rj = remote_job.remote_job(l)
                    self.polls.append(rj)

            if 'None' in rs:
                print "No job pending..."

        else:
            print "Not registered cannot poll !"



if __name__ == '__main__':

    print "Test Worker"

    worker = stici_worker('http://localhost/stici/stici-master', "C:\\Program Files (x86)\\Git\\bin")

    worker.run()