<?php
require_once'auth.php';
if($Logged_In===7) {
   header("Location: wallet.php");
}
$myusername = $_POST['username'];
$mypassword = $_POST['password'];
$myrepeat = $_POST['repeat'];
$form_action = $_POST['action'];
if($form_action=="login") {
   if($myusername) {
      if($mypassword) {
         $myusername2 = addslashes(strip_tags($myusername));
         $mypassword2 = md5(addslashes(strip_tags($mypassword)));
         $sql = "SELECT * FROM users WHERE username='$myusername2'";
         $result = mysql_query($sql);
         $count = mysql_num_rows($result);
         if($count==1) {
            $Pass_Query = mysql_query("SELECT * FROM users WHERE username='$myusername2'");
            while($Pass_Row = mysql_fetch_assoc($Pass_Query)) {
               $db_Login_Pass_Check = $Pass_Row['password'];
               if($mypassword2==$db_Login_Pass_Check) {
                  $return_error = "Logged in.";
                  $_SESSION['user_session'] = $myusername;
                  header("location: index.php");
               } else {
                  $return_error = "Invalid Password.";
               }
            }
         } else {
            $return_error = "User does not esist.";
         }
      }
   }
}
if($form_action=="register") {
   if($myusername) {
      if($mypassword) {
         if($mypassword==$myrepeat) {
            $uLength = strlen($myusername);
            $pLength = strlen($mypassword);
            if($uLength >= 3 && $uLength <= 30) {
               $return_error = "";
            } else {
               $return_error = $return_error . "Username must be between 3 and 30 characters" . "<BR>";
            }
            if($pLength >= 3 && $pLength <= 30) {
               $return_error = "";
            } else {
               $return_error = $return_error . "Password must be between 3 and 30 characters" . "<BR>";
            }
            if($return_error == "") {
               if($db_found) {
                  $myusername = addslashes(strip_tags($myusername));
                  $mypassword = md5(addslashes(strip_tags($mypassword)));
                  $SQL = "SELECT * FROM users WHERE username='$myusername'";
                  $result = mysql_query($SQL);
                  $num_rows = mysql_num_rows($result);
                  if($num_rows==1) {
                     $return_error = "Username already taken.";
                  } else {
                     if(!mysql_query("INSERT INTO users (id,date,ip,username,password) VALUES ('','$date','$ip','$myusername','$mypassword')")){
                        $return_error = "System error.";
                     } else {
                        $return_error = "Logged in.";
                        $_SESSION['user_session'] = $myusername;
                        header ("Location: index.php");
                     }
                  }
               }
            }
         } else {
            $return_error = $return_error . "Passwords did not match" . "<BR>";
         }
      }
   }
}
if($return_error) { $return_error = '<center><p><b style="color: #FF0000;">'.$return_error.'</b></p></center>'; }
?>
<html>
<head>
   <title>Bytecoin Wallet</title>
   <style>
      body { background: #04B431; color: #000000; font-family: times; font-size: 14px; margin: 0px; padding: 0px; }
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
         <td align="right" nowrap>
            <form action="index.php" method="POST">
            <input type="hidden" name="action" value="login">
            <table>
               <tr>
                  <td align="right"><input type="text" name="username" placeholder="Username" value="<?php echo $myusername; ?>" style="width: 110px;" required autofocus></td>
                  <td align="right"><input type="password" name="password" placeholder="Password" style="width: 110px;" required></td>
                  <td colspan="2" align="right"><input type="submit" name="submit" class="button" value="Login"></td>
               </tr>
            </table>
            </form>
         </td>
      </tr>
   </table>
   </div>
   <p></p>
   <div align="center" style="width: 700px; background: #FFFFFF; font-weight: bold; border: 4px solid #0B6121; padding:10px; border-radius: 15px; -moz-border-radius: 15px;">
   <table style="width: 100%; height: 50px;">
      <tr>
         <td align="left" valign="top" style="padding-left: 15px;" nowrap>
            <b>Welcome</b><br>
            This is a Bytecoin wallet made for quick and easy access to your Bytecoins.<br>
            Upon withdrawing from the wallet you would be required to pay the netwrok<br>
            fee that Bytecoin requires. No fee is charged by this service, only by the<br>
            Bytecoin network.
         </td>
         <td align="right" valign="top" style="padding-right: 15px;" nowrap>
   <form action="index.php" method="POST">
   <input type="hidden" name="action" value="register">
   <table>
      <tr>
         <td align="left"><b>Register a new account:</b></td>
      </tr><tr>
         <td align="center"><?php echo $return_error; ?></td>
      </tr><tr>
         <td align="right"><input type="text" name="username" placeholder="Username" value"<?php echo $myusername; ?>" style="width: 180px;" required></td>
      </tr><tr>
         <td align="right"><input type="password" name="password" placeholder="Password" style="width: 180px;" required></td>
      </tr><tr>
         <td align="right"><input type="password" name="repeat" placeholder="Repeat Password" style="width: 180px;" required></td>
      </tr><tr>
         <td align="right"><input type="submit" name="submit" class="button" value="Register"></td>
      </tr>
   </table>
         </td>
      </tr>
   </table>
   </div>
   <p></p>
   </center>
</body>
</html>
<?php require'footer.php'; ?>
