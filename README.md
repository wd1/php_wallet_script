WalletScript
============

A php wallet script compatible with digital currencies like Bitcoin, Litecoin, and countless more.


Basic WalletScript setup:

 1. Set up the bitcoin.conf(or alt-coin conf) and make sure it has:
       server=1
       rpcuser=yourusername
       rpcpassword=youpassword
       rpctimeout=30
       rpcallowip=127.0.0.1
       rpcport=8888
 2. Create a database for WalletScript.
 3. Import db.sql into the database.
 4. Enter database credentials in auth.php
 5. Enter Bitcoin, or alt-coin, RPC credentials in auth.php
 6. Upload everything except db.sql and this read me.
 7. As long as you have Bitcoin(alt-coin) client running and RPC set up correctly then
    you should be ready to create an account and use the wallet.

I suggest putting the auth.php file out of the public scope and fixing 
the require lines accordingliy in index.php and wallet.php

Example placement:
 root/public_html/index.php
 root/public_html/wallet.php
 root/private/auth.php

And in the 2 files you would change
 'auth.php' to '../private/auth.php'
