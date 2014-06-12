PeercoinBlockExplorer - Version 0.1.0
=====================================

Peercoin Block Chain Viewer
Version is running on http://blockexplorer.peercointalk.org/

[![tip for next commit](http://peer4commit.com/projects/4.svg)](http://peer4commit.com/projects/4)

Currently search via txid does not work on this block explorer but this is due to the api call being different from bitcoin for peercoin and should hopefully be fixed soon or feel free to have a look at the code and try yourself. Just send a pull request to this repo or contact Fuzzybear on http://www.peercointalk.org/

Note: Searching via txid works fine with glv2's pull request to Peerunity, which is slated to be merged into Peerunity 0.2. Until that time, an easy fix is to clone and build glv2's own branch of Peerunity where he has merged the rpc changes: https://github.com/glv2/peercoin/

The Peerunity daemon is launched through ./src/peerunityd --daemon, but the daemon uses the same name "ppcoind" so no changes to the PPC_daemon.php are required. (tested on Ubuntu 14.04)




Requirements
------------

Windows
=======
php 5.2

ppcoind 0.3

Tested on Windows 2008 SP2 server with php 5.2

Tested using Wamp Server 2.4 on x64 Win 7 with php 5.4.12


Linux
=====

php 5.5

ppcoind 0.3

Tested on Gentoo with nginx.
