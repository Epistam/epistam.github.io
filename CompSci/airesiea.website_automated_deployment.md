# Air ESIEA - Automated Hugo deployment ft. GitHub
###### 02/02/2023

## Context
website asso, deploy modifications to the website without logging in through ssh

## Setting it up
- GitHub Webhooks querying a REST API hosted on the server (GH documentation
  gives Ruby / Sinatra as an example, I just used Flask cause why the F not)
- a Flask REST API on the server, built in two parts : 
	- a Python / Flask service : 

```python
	import os
	import time as t
	from datetime import datetime, date, timedelta, time
	import flask
	from flask import request, jsonify, g
	from flask_cors import CORS

	from hmac import HMAC, compare_digest
	from hashlib import sha256

	app = flask.Flask(__name__)
	app.config["DEBUG"] = True
	cors = CORS(app)

	webserver_root = '/var/www/[DOMAIN]/'

	#################
	# Initial setup #
	#################
	@app.before_first_request

	################
	# 4xx HANDLERS #
	################

	@app.errorhandler(400)
	def bad_request():
		return "<h1>Error 400 : Bad Request.<h1> <h3><p>Unknown or malformed API call. RTFM.</p></h3>", 400

	@app.errorhandler(401)
	def unauthorized():
		return "<h1>Error 401 : Unauthorized.<h1>", 401

	#############
	# API calls #
	#############

	def verify_signature(req):
		 received_sign = req.headers.get('X-Hub-Signature-256').split('sha256=')[-1].strip()
		 secret = '[REPLACE WITH SECRET]'.encode()
		 expected_sign = HMAC(key=secret, msg=req.data, digestmod=sha256).hexdigest()
		 return compare_digest(received_sign, expected_sign)

	def deploy(dest) :
		os.system('sh ' + webserver_root + '/deploy.sh ' + dest+ ' ' + webserver_root)

	@app.route('/testdeploy', methods=['POST'])
	def test_deploy_hook(): 
		print('Received GH API call')
		if verify_signature(request):
			print('API call authenticated, deploying')
			deploy('test')
			print('Successfully deployed to TESTING.')
			return "", 200
		return "Forbidden", 403

	@app.route('/proddeploy', methods=['POST'])
	def prod_deploy_hook(): 
		print('Received GH API call')
		if verify_signature(request):
			print('API call authenticated, deploying')
			deploy('prod')
			print('Successfully deployed to PRODUCTION.')
			return "", 200
		return "Forbidden", 403

	###########
	# Runtime #
	###########

	app.run(host="[DOMAIN]", port=[PORT])
```

	- a Bash script :

```bash
	#!/bin/bash

	WEBSITE_HOME=$2/$1

	if [ "$1" = "test" ]; then
			echo ">>>(TEST) Starting TESTING deployment"
			cd $WEBSITE_HOME

			echo ">>>(TEST) Resetting config.toml"
			git checkout config.toml

			echo ">>>(TEST) Pulling repo..."
			git pull

			echo ">>>(TEST) Switching to test branch"
			git switch test

			echo ">>>(TEST) Replacing [DOMAIN] w/ [TESTDOMAIN] in config.toml"
			sed -i 's/[DOMAIN]/[TESTDOMAIN]/' config.toml

			echo ">>>(TEST) Compiling website"
			hugo -Dv

			echo ">>>(TEST) Deployment done."

			exit 0
	fi

	if [ "$1" = "prod" ]; then
			echo ">>> Starting PRODUCTION deployment"
			cd $WEBSITE_HOME

			echo ">>>(PROD) Pulling repo..."
			git pull

			echo ">>>(PROD) Switching to test branch"
			git switch master

			echo ">>>(PROD) Compiling website"
			hugo -Dv

			echo ">>>(PROD) Deployment done."

			exit 0
	fi
	echo "Usage : deploy.sh [prod/test] [webserver root]"
```
	- a small Systemd unit (`/etc/systemd/system/restapi.service`) to start up the
	  API at boot time 

```
	[Unit]
	Description=REST API for airesiea.org deployment
	After=multi-user.target
	[Service]
	Type=simple
	Restart=always
	ExecStart=/usr/bin/python3 /var/www/airesiea.org/deploy.py
	[Install]
	WantedBy=multi-user.target
```

## How to use it

#### Repository structure
There repository has two **branches** : 
- test : every modification has to be submitted here first, published and
  checked on test website as described below
- master : after verification are made, modifications are committed to the
  production branch through the **merging** (again, see below) to the master
  branch. When pushed, it will result in the automated update and rebuild of
  the website.

Do **not** revert to a previous commit. Make a new one correcting the wrong
changes, even if it's just removing it altogether. Reverting to previous
commits **will** break the deployment scripts.

#### Git 101
- `git clone https://github.com/REUZIA/airesiea.org` : creates a local copy of the repository
- `git switch [branch]` : switch to a different branch (test or master)
- `git add [file]` : add the current changes to the next commit. `git add .`
  may be used to add all the edited files in the repo. In this case, use `git
  status` before committing to check exactly which files will be included.
- `git commit -m "[message]"`: creates a commit (registers all current changes
  in one specific sub-version in time). Use an explicit message to describe the
  changes made in the commit.
- `git push` : push the local changes to the remote (github.com) repository.
  This **will** trigger a rebuild for the test and production websites. Make
  sure you know what you're doing before doing this. Ask for verification at
  this stage is you're not sure.
- `git merge [branch]` : merges `branch` into the current branch. 

#### Editing procedure
- clone the repo. If you already have a local copy, you **need** to make sure
  you have the last version of the repository **before** getting started with
  the edits. If in doubt, re-clone it or run `git pull` successfully. If the
  latter fails, it means you have uncommitted changes you need to get rid of. 
  In any case, it's always easier to clone the repo somewhere else if in doubt.
- switch to the testing branch. Do you edits there, add and commit them. Check
  everything is alright before pushing.
- push to the remote, and examine the changes on the testing website
  (test.usual_domain.org).
- if everything checks out and no regression nor mistake is seen, come back to
  your local Git repo, switch to the master (production) repo using `git
  checkout master`. Merge the testing branch using `git merge test`.
- you may now push the repo again to update the production website
- check one last time that everything is alright on usual_domain.org.

Congrats, you made your first contribution to the website !
