Sti::CI
==========
Sti Continous Integration Server

#### Why another CI ?

I was using Jenkins and TeamCity. 
Jenkins is a Open Source solution written in Java, many plug-ins and all the shizzle. 
I was using Jenkins to build my C++ project.
But, my build time was so long, it was 20 minutes for a little C++.
I tried to optimize Jenkins build step, but nothing was working.
So I tried to compile with my old command line outside the IDE.
It took only 59 seconds versus 20 minutes... without -j for multi-core building.


#### Dependency

	- PHP 5
	- A Web server (Tested with Apache2)
	- MySQL Server
	- Python 2.7 with (Apache Requests)
	- Git

	
#### Features

Sti::CI contains two modules which are needed to build.


#### stici-master

This is the master server, it written in PHP using a MySQL database to store information.
You can view your job, edit and launch your build on this website. We are planning to adding more features.


#### stici-worker

This is the builder, you can run on it on localhost or on a remote computer (for multiplatform build).
This module is written and tested with Python 2.7. You need to specify your master url and you can launch many worker.
The worker stay registered on the master until 5 minutes of no response.


## TO DO

### Master

	-Delete build (logs, files)
	-Settings to remove hard-coded things

### Worker

	-...
	
	