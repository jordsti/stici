import platform
import time
import random
import hashlib
import urllib2
import remote_job

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

    def __init__(self, master_url):
        self.host = platform.node()
        self.master_url = master_url
        self.hash = generate_worker_hash()
        self.registered = False
        self.interval = 30  #in seconds..
        self.polls = []
        self.current_job = None


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

            time.sleep(self.interval)

    def start_build(self):
        pass

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

    worker = stici_worker('http://localhost/stici')

    worker.run()