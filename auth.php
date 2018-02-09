<?php
session_start();
error_reporting(0);
require_once'jsonRPCClient.php';

// function by zelles to modify the number to bitcoin format ex. 0.00120000
function satoshitize($satoshitize) {
   return sprintf("%.8f", $satoshitize);
}

// function by zelles to trim trailing zeroes and decimal if need
function satoshitrim($satoshitrim) {
   return rtrim(rtrim($satoshitrim, "0"), ".");
}

$server_url = $_SERVER['SERVER_NAME'];  // website url
$ip = $_SERVER['REMOTE_ADDR'];          // get visitors ip address
$date = date("n/j/Y g:i a");;           // get the current date and time

$dbhst = "localhost";       // database host
$dbusr = "Your_Username";   // database username
$dbpwd = "Your_password";   // database password
$dbtbl = "Your_DB_Name";   // database name

// connect to the database
$db_handle = mysql_connect($dbhst, $dbusr, $dbpwd)or die("cannot connect");
$db_found = mysql_select_db($dbtbl)or die("cannot select DB");

$user_session = $_SESSION['user_session'];  // set session and check if logged in
if(!$user_session) {
   $Logged_In = 2;
} else {
   $Logged_In = 7;
   $RPC_Host = "127.0.0.1";         // host for bitcoin rpc
   $RPC_Port = "8333";              // port for bitcoin rpc
   $RPC_User = "Your_Username";     // username for bitcoin rpc
   $RPC_Pass = "Your_Password";     // password for bitcoin rpc
   
   // dont change below here
   $nu92u5p9u2np8uj5wr = "http://".$RPC_User.":".$RPC_Pass."@".$RPC_Host.":".$RPC_Port."/";
   $Bytecoind = new jsonRPCClient($nu92u5p9u2np8uj5wr);
   $wallet_id = "zelles(".$user_session.")";
   $Bytecoind_Balance = $Bytecoind->getbalance($wallet_id,6);
   $Bytecoind_accountaddresses = $Bytecoind->getaddressesbyaccount($wallet_id);
   $Bytecoind_List_Transactions = $Bytecoind->listtransactions($wallet_id,10);
}
?>
