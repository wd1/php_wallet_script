<?php
require_once'auth.php';
if($Logged_In!==7) {
   header("Location: index.php");
}
$new_address = addslashes(strip_tags($_GET['newaddr']));
if($new_address=="go") {
   $Bytecoind_accountaddress = $Bytecoind->getnewaddress($wallet_id);
   header("Location: index.php");
}
$withdraw_amount = addslashes(strip_tags($_POST['amount']));
$withdraw_address = addslashes(strip_tags($_POST['address']));
if($withdraw_address) {
   if($withdraw_amount) {
      $withdraw_amount = satoshitize($withdraw_amount);
      if($withdraw_amount<$Bytecoind_Balance) {
         $Bytecoind_Withdraw_From = $Bytecoind->sendfrom($wallet_id,$withdraw_address,(float)$withdraw_amount,6);
         $withdraw_message = $Bytecoind_Withdraw_From;
         $Bytecoind_Balance = $Bytecoind->getbalance($wallet_id,6);
      } else {
         $withdraw_message = 'You do not have enough Bytecoins!';
      }
   } else {
      $withdraw_message = 'No amount to withdraw was entered!';
   }
}
?>
<html>
<head>
   <title>Bytecoin Wallet</title>
   <style>
      body { background: #04B431; color: #000000; font-family: times; font-size: 14px; margin: 0px; padding: 0px; }
      hr { height: 1px; background: #04B431; }
      table { font-size: 14px; }
      a { text-decoration: none; color: #04B431; }
      input { height: 22px; border: 1px solid #04B431; border-radius: 6px; -moz-border-radius: 6px; }
      .button { height: 22px; background: #0B6121; border: 1px solid #0B6121; color: #FFFFFF; font-weight: bold; border-radius: 6px; -moz-border-radius: 6px; }
   </style>
</head>
<body>
   <center>
   <div align="center" style="width: 700px; background: #FFFFFF; font-weight: bold; border-left: 4px solid #0B6121; border-right: 4px solid #0B6121; border-bottom: 4px solid #0B6121; border-top: 0px solid #FFFFFF; padding:10px; border-radius: 0px 0px 15px 15px; -moz-border-radius: 0px 0px 15px 15px;">
   <table style="width: 100%; height: 50px;">
      <tr>
         <td align="left" style="width: 30px;" nowrap>
            <a href="http://<?php echo $server_url; ?>"><img src="bytecoin.png" border="0"></a>
         </td>
         <td align="left" style="font-size: 18px; font-weight: bold;" nowrap>
            <a href="http://<?php echo $server_url; ?>" style="color: #04B431;">Bytecoin Wallet</a>
         </td>
         <td align="right" valign="top" nowrap>
            Balance: <?php echo $Bytecoind_Balance; ?> BTE<br>
            <a href="logout.php">Logout</a>
         </td>
      </tr>
   </table>
   </div>
   <p></p>
   <div align="center" style="width: 700px; background: #FFFFFF; font-weight: bold; border: 4px solid #0B6121; padding:10px; border-radius: 15px; -moz-border-radius: 15px;">
   <table style="width: 650px;">
      <tr>
         <td colspan="2" align="left" valign="top" style="padding: 5px;" nowrap>
            <?php if($withdraw_message) { echo '<center><b style="color: #FF0000;">'.$withdraw_message.'</b></center>'; } ?>
            <b>Withdraw:</b>
            <center>
            <form action="wallet.php" method="POST">
            <table>
               <tr>
                  <td align="right" nowrap><b>Amount</b></td>
                  <td align="left" nowrap><input type="text" name="amount" style="width: 100px;"></td>
                  <td align="right" nowrap><b>Address</b></td>
                  <td align="left" nowrap><input type="text" name="address" style="width: 180px;"></td>
                  <td align="left" nowrap><input type="submit" class="button" name="submit" value="Withdraw"></td>
               </tr>
            </table>
            </form>
            </center>
            <hr>
            <b>Deposit: <a href="wallet.php?newaddr=go">(new address)</a></b>
            <center>
            <table>
               <tr>
                  <td align="left" style="padding: 3px;" nowrap>
                     <?php
                     foreach($Bytecoind_accountaddresses as $Bytecoind_accountaddress) {
                        echo $Bytecoind_accountaddress."<br>";
                     }
                     ?>
                  </td>
               </tr>
            </table>
            </center>
            <hr>
            <b>Last 10 Transactions:</b>
            <center>
            <table>
               <tr>
                  <td align="left" style="font-weight: bold; padding: 3px;" nowrap>Date</td>
                  <td align="left" style="font-weight: bold; padding: 3px;" nowrap>Address</td>
                  <td align="right" style="font-weight: bold; padding: 3px;" nowrap>Type</td>
                  <td align="right" style="font-weight: bold; padding: 3px;" nowrap>Amount</td>
                  <td align="right" style="font-weight: bold; padding: 3px;" nowrap>Fee</td>
                  <td align="right" style="font-weight: bold; padding: 3px;" nowrap>Confs</td>
                  <td align="left" style="font-weight: bold; padding: 3px;" nowrap>Info</td>
               </tr>
               <?php
               $bold_txxs = "";
               foreach($Bytecoind_List_Transactions as $Bytecoind_List_Transaction) {
                  if($bold_txxs=="") { $bold_txxs = "color: #666666; "; } else { $bold_txxs = ""; }
                  if($Bytecoind_List_Transaction['category']=="send") { $tx_type = '<b style="color: #FF0000;">Sent</b>'; } else { $tx_type = '<b style="color: #01DF01;">Received</b>'; }
                  echo '<tr>
                           <td align="left" style="'.$bold_txxs.'padding: 3px;" nowrap>'.date('n/j/Y h:i a',$Bytecoind_List_Transaction['time']).'</td>
                           <td align="left" style="'.$bold_txxs.'padding: 3px;" nowrap>'.$Bytecoind_List_Transaction['address'].'</td>
                           <td align="right" style="'.$bold_txxs.'padding: 3px;" nowrap>'.$tx_type.'</td>
                           <td align="right" style="'.$bold_txxs.'padding: 3px;" nowrap>'.abs($Bytecoind_List_Transaction['amount']).'</td>
                           <td align="right" style="'.$bold_txxs.'padding: 3px;" nowrap>'.abs($Bytecoind_List_Transaction['fee']).'</td>
                           <td align="right" style="'.$bold_txxs.'padding: 3px;" nowrap>'.$Bytecoind_List_Transaction['confirmations'].'</td>
                           <td align="left" style="padding: 3px;" nowrap><a href="http://blockexplorer.bytecoin.in/tx/'.$Bytecoind_List_Transaction['txid'].'" target="_blank">Info</a></td>
                        </tr>';
               }
               ?>
            </table>
            </center>
         </td>
      </tr>
   </table>
   </div>
   <p></p>
   </center>
</body>
</html>
<?php require'footer.php'; ?>
