# -*- coding: utf-8 -*-

# Form implementation generated from reading ui file 'WorkerMainWindow.ui'
#
# Created: Tue Oct 28 17:49:27 2014
#      by: PyQt4 UI code generator 4.10.4
#
# WARNING! All changes made in this file will be lost!

from PyQt4 import QtCore, QtGui

try:
    _fromUtf8 = QtCore.QString.fromUtf8
except AttributeError:
    def _fromUtf8(s):
        return s

try:
    _encoding = QtGui.QApplication.UnicodeUTF8
    def _translate(context, text, disambig):
        return QtGui.QApplication.translate(context, text, disambig, _encoding)
except AttributeError:
    def _translate(context, text, disambig):
        return QtGui.QApplication.translate(context, text, disambig)

class Ui_WorkerMainWindow(object):
    def setupUi(self, WorkerMainWindow):
        WorkerMainWindow.setObjectName(_fromUtf8("WorkerMainWindow"))
        WorkerMainWindow.resize(800, 600)
        self.centralwidget = QtGui.QWidget(WorkerMainWindow)
        self.centralwidget.setObjectName(_fromUtf8("centralwidget"))
        self.verticalLayoutWidget = QtGui.QWidget(self.centralwidget)
        self.verticalLayoutWidget.setGeometry(QtCore.QRect(10, 50, 681, 431))
        self.verticalLayoutWidget.setObjectName(_fromUtf8("verticalLayoutWidget"))
        self.mainLayout = QtGui.QVBoxLayout(self.verticalLayoutWidget)
        self.mainLayout.setSpacing(5)
        self.mainLayout.setMargin(5)
        self.mainLayout.setObjectName(_fromUtf8("mainLayout"))
        self.formLayout = QtGui.QFormLayout()
        self.formLayout.setObjectName(_fromUtf8("formLayout"))
        self.lblName = QtGui.QLabel(self.verticalLayoutWidget)
        self.lblName.setObjectName(_fromUtf8("lblName"))
        self.formLayout.setWidget(0, QtGui.QFormLayout.LabelRole, self.lblName)
        self.leName = QtGui.QLineEdit(self.verticalLayoutWidget)
        self.leName.setMaximumSize(QtCore.QSize(200, 16777215))
        self.leName.setObjectName(_fromUtf8("leName"))
        self.formLayout.setWidget(0, QtGui.QFormLayout.FieldRole, self.leName)
        self.lblBuildNumber = QtGui.QLabel(self.verticalLayoutWidget)
        self.lblBuildNumber.setObjectName(_fromUtf8("lblBuildNumber"))
        self.formLayout.setWidget(1, QtGui.QFormLayout.LabelRole, self.lblBuildNumber)
        self.leBuildNumber = QtGui.QLineEdit(self.verticalLayoutWidget)
        self.leBuildNumber.setMaximumSize(QtCore.QSize(200, 16777215))
        self.leBuildNumber.setObjectName(_fromUtf8("leBuildNumber"))
        self.formLayout.setWidget(1, QtGui.QFormLayout.FieldRole, self.leBuildNumber)
        self.lblRemoteGit = QtGui.QLabel(self.verticalLayoutWidget)
        self.lblRemoteGit.setObjectName(_fromUtf8("lblRemoteGit"))
        self.formLayout.setWidget(2, QtGui.QFormLayout.LabelRole, self.lblRemoteGit)
        self.leRemoteGit = QtGui.QLineEdit(self.verticalLayoutWidget)
        self.leRemoteGit.setMaximumSize(QtCore.QSize(200, 16777215))
        self.leRemoteGit.setObjectName(_fromUtf8("leRemoteGit"))
        self.formLayout.setWidget(2, QtGui.QFormLayout.FieldRole, self.leRemoteGit)
        self.lblTarget = QtGui.QLabel(self.verticalLayoutWidget)
        self.lblTarget.setObjectName(_fromUtf8("lblTarget"))
        self.formLayout.setWidget(3, QtGui.QFormLayout.LabelRole, self.lblTarget)
        self.cbTarget = QtGui.QComboBox(self.verticalLayoutWidget)
        self.cbTarget.setMaximumSize(QtCore.QSize(200, 16777215))
        self.cbTarget.setObjectName(_fromUtf8("cbTarget"))
        self.formLayout.setWidget(3, QtGui.QFormLayout.FieldRole, self.cbTarget)
        self.mainLayout.addLayout(self.formLayout)
        self.lblEnvs = QtGui.QLabel(self.verticalLayoutWidget)
        self.lblEnvs.setObjectName(_fromUtf8("lblEnvs"))
        self.mainLayout.addWidget(self.lblEnvs)
        self.tableWidget = QtGui.QTableWidget(self.verticalLayoutWidget)
        self.tableWidget.setObjectName(_fromUtf8("tableWidget"))
        self.tableWidget.setColumnCount(0)
        self.tableWidget.setRowCount(0)
        self.mainLayout.addWidget(self.tableWidget)
        self.lblSteps = QtGui.QLabel(self.verticalLayoutWidget)
        self.lblSteps.setObjectName(_fromUtf8("lblSteps"))
        self.mainLayout.addWidget(self.lblSteps)
        self.tableWidget_2 = QtGui.QTableWidget(self.verticalLayoutWidget)
        self.tableWidget_2.setObjectName(_fromUtf8("tableWidget_2"))
        self.tableWidget_2.setColumnCount(0)
        self.tableWidget_2.setRowCount(0)
        self.mainLayout.addWidget(self.tableWidget_2)
        WorkerMainWindow.setCentralWidget(self.centralwidget)
        self.menubar = QtGui.QMenuBar(WorkerMainWindow)
        self.menubar.setGeometry(QtCore.QRect(0, 0, 800, 21))
        self.menubar.setObjectName(_fromUtf8("menubar"))
        self.menu_File = QtGui.QMenu(self.menubar)
        self.menu_File.setObjectName(_fromUtf8("menu_File"))
        WorkerMainWindow.setMenuBar(self.menubar)
        self.statusbar = QtGui.QStatusBar(WorkerMainWindow)
        self.statusbar.setObjectName(_fromUtf8("statusbar"))
        WorkerMainWindow.setStatusBar(self.statusbar)
        self.actionOpen_job = QtGui.QAction(WorkerMainWindow)
        self.actionOpen_job.setObjectName(_fromUtf8("actionOpen_job"))
        self.actionQuit = QtGui.QAction(WorkerMainWindow)
        self.actionQuit.setObjectName(_fromUtf8("actionQuit"))
        self.menu_File.addAction(self.actionOpen_job)
        self.menu_File.addSeparator()
        self.menu_File.addAction(self.actionQuit)
        self.menubar.addAction(self.menu_File.menuAction())

        self.retranslateUi(WorkerMainWindow)
        QtCore.QMetaObject.connectSlotsByName(WorkerMainWindow)

    def retranslateUi(self, WorkerMainWindow):
        WorkerMainWindow.setWindowTitle(_translate("WorkerMainWindow", "Sti::CI Local Worker", None))
        self.lblName.setText(_translate("WorkerMainWindow", "Job Name", None))
        self.lblBuildNumber.setText(_translate("WorkerMainWindow", "Build Number", None))
        self.lblRemoteGit.setText(_translate("WorkerMainWindow", "Git Url", None))
        self.lblTarget.setText(_translate("WorkerMainWindow", "Target", None))
        self.lblEnvs.setText(_translate("WorkerMainWindow", "Environment Variables", None))
        self.lblSteps.setText(_translate("WorkerMainWindow", "Steps", None))
        self.menu_File.setTitle(_translate("WorkerMainWindow", "&File", None))
        self.actionOpen_job.setText(_translate("WorkerMainWindow", "Open job...", None))
        self.actionQuit.setText(_translate("WorkerMainWindow", "Quit", None))

