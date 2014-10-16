__author__ = 'JordSti'

class stici_exception(Exception):
    pass

class step_failed(stici_exception):

    def __init__(self, build_step):
        stici_exception.__init__(self)
        self.build_step = build_step
