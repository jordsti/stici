__author__ = 'JordSti'
import gui
from PyQt4 import QtCore, QtGui
import sys


class local_worker_main_win(QtGui.QMainWindow, gui.Ui_WorkerMainWindow):

    def __init__(self, parent=None):
        QtGui.QMainWindow.__init__(self, parent)
        self.setupUi(self)
        self.centralwidget.setLayout(self.mainLayout)



app = QtGui.QApplication(sys.argv)
win = local_worker_main_win()
win.show()
app.exec_()
