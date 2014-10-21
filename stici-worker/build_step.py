__author__ = 'JordSti'
import subprocess
import stici_exception
import os
import time
import shutil
import step
import file_upload



class build_step(step.step):
    (IgnoreReturn) = (1)

    def __init__(self, executable, args=[], envs={}, flags=0, timeout=300):
        step.step.__init__(self)
        self.executable = executable
        self.__env_dict = envs
        self.args = args
        self.flags = flags
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

        #self.print_cmd()
        if 'cd' == self.executable:
            os.chdir(self.args[-1])
        elif 'mkdir' == self.executable:
            try:
                os.mkdir(self.args[-1])
            except Exception:
                pass
        elif 'rm' == self.executable:
            shutil.rmtree(self.args[-1], True)
        elif 'zipdir' == self.executable:
            zip_path = self.args[-1]
            for f in os.listdir(zip_path):
                if os.path.isdir(os.path.join(zip_path, f)):
                    archive_name = "%s-%s" % (stici_job.name, f)
                    bname = os.path.join(os.getcwd(), archive_name)
                    print archive_name
                    root_dir = os.path.join(zip_path, f)

                    shutil.make_archive(bname, "zip", root_dir)
                    stici_job.archives.append(bname+".zip")

        elif 'upload-archives' == self.executable:
            for a in stici_job.archives:
                print "Uploading : %s" % os.path.basename(a)
                upload_thread = file_upload.file_uploader(stici_job.worker, a)
                upload_thread.start()
                while upload_thread.isAlive():
                    print "Uploading..."
                    time.sleep(1)

                self.stdout = upload_thread.stdout
                self.stderr = upload_thread.stderr
                self.return_code = upload_thread.return_code
        else:
            if len(self.__env_dict) == 0:
                #wmpty env, getting OS env
                self.__env_dict = os.environ

            _process = subprocess.Popen(self.args, stdout=subprocess.PIPE, stderr=subprocess.PIPE, shell=self.shell, env=self.__env_dict)


            started = time.time()
            self.wait_process(_process)

            self.return_code = _process.returncode
            time_elapsed = time.time() - started
            print "%d seconds" % time_elapsed

        if self.return_code == 0:
            pass
        elif self.test_flags(self.IgnoreReturn):
            pass
        else:
            raise stici_exception.step_failed(self)

