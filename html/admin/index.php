<?php
  ini_set('display_errors', 'On');
  error_reporting(E_ALL);
  if (isset($_POST) && !empty($_POST)) {
    // echo("Post Variable dump begin: <br>");
    // var_dump($_POST);
    // echo("<br>Post Variable dump end.<br>");
    if ( $_POST["submit"] == "Submit") {
      $dhcp = ( isset($_POST['dhcp']) ? "YES" : "NO" );
      $host_name = $_POST['host_name'];
      $ip_address = $_POST['ip_address'];
      $subnet_mask = $_POST['subnet_mask'];
      $gateway = $_POST['gateway'];
      $error_count = 0;
      $file_hostname = "";
      $file_ip = "";
      $file_subnet = "";
      $file_gateway = "";
      $file_dhcp = "";

      // See if we can open the config file
      $myfile = fopen("/var/www/admin/configpi.config", "r") or die("Unable to open file!");
      while(!feof($myfile)) {
        $this_line = fgets($myfile);
        // Ignore lines that start with a "#" comment
        if ( substr($this_line, 0, 1) != "#" ){
          $line_array = explode("=", $this_line);
          // content can either be HOSTNAME, IPADDRESS, SUBNETMASK, GATEWAY, OR DHCP
          switch ($line_array[0]) {
            case "HOSTNAME":
              $file_hostname = trim($line_array[1]);
              break;
            case "IPADDRESS":
              $file_ip = trim($line_array[1]);
              break;
            case "SUBNETMASK":
              $file_subnet = trim($line_array[1]);
              break;
            case "GATEWAY":
              $file_gateway = trim($line_array[1]);
              break;
            case "DHCP":
              $file_dhcp = trim($line_array[1]);
              break;
          }
        }
      }
      fclose($myfile);

      // See if we need to run the validations or not (is dhcp checked?)
      if( $dhcp == 'NO')
      {
          // Check if host name has been entered
        if (!$_POST['host_name'] || !filter_var($_POST['host_name'], FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^([a-z0-9](?:[a-z0-9-]*[a-z0-9]))$/i")))) {
            $errHostname = 'Please enter a valid host name';
            $error_count += 1;
        }
        // Check if IP Address has been entered and is valid
        if (!$_POST['ip_address'] || !filter_var($_POST['ip_address'], FILTER_VALIDATE_IP)) {
            $errIPAddress = 'Please enter a valid IP Address';
            $error_count += 1;
        }
        // Check if Subnet Mask has been entered and is valid
        if (!$_POST['subnet_mask'] || !isValidIPv4Mask($_POST['subnet_mask'])) {
            $errSubnet_mask = 'Please enter a valid Subnet Mask';
            $error_count += 1;
        }
        //Check if valid gateway has been entered
        if (!$_POST['gateway'] || !filter_var($_POST['gateway'], FILTER_VALIDATE_IP)) {
            $errGateway = 'Please enter a valid Gateway';
            $error_count += 1;
        }
      }
      // Then write all of these settings to the config file.
      $myfile = fopen("/var/www/admin/configpi.config", "w") or die("Unable to open file!");
      fwrite($myfile, "HOSTNAME=".$host_name."\n");
      // See if we have a DHCP situation or not...
      if ( $dhcp == "YES" ){
        fwrite($myfile, "DHCP=YES\n");
      } else {
        fwrite($myfile, "DHCP=NO\n");
        fwrite($myfile, "IPADDRESS=".$ip_address."\n");
        fwrite($myfile, "SUBNETMASK=".$subnet_mask."\n");
        fwrite($myfile, "GATEWAY=".$gateway."\n");
      }
      fclose($myfile);
      // TODO: Create a backup file to go back to should things go wrong.
    } elseif (isset($_POST) && $_POST["submit"] == "Apply Settings") {
      // Settings are being applied... reboot with the values in the config file.
      $error_count = 0;
      echo "I am submitting the settings.... Please wait.";
      // Let's redirect the user to a waiting page...
      header("Location: applying_settings.php");
      exit;

    } else {
      // I don't think we will ever get here.
      echo "We actually got here!";
      
    }
  } else {
    // This is where we end up on a new visit.
      $file_hostname = "";
      $file_ip = "";
      $file_subnet = "";
      $file_gateway = "";
      $file_dhcp = "";

      // See if we can open the config file
      $myfile = fopen("/var/www/admin/configpi.config", "r") or die("Unable to open file!");
      while(!feof($myfile)) {
        $this_line = fgets($myfile);
        // Ignore lines that start with a "#" comment
        if ( substr($this_line, 0, 1) != "#" ){
          $line_array = explode("=", $this_line);
          // content can either be HOSTNAME, IPADDRESS, SUBNETMASK, GATEWAY, OR DHCP
          switch ($line_array[0]) {
            case "HOSTNAME":
              $file_hostname = trim($line_array[1]);
              break;
            case "IPADDRESS":
              $file_ip = trim($line_array[1]);
              break;
            case "SUBNETMASK":
              $file_subnet = trim($line_array[1]);
              break;
            case "GATEWAY":
              $file_gateway = trim($line_array[1]);
              break;
            case "DHCP":
              $file_dhcp = trim($line_array[1]);
              break;
          }
        }
      }
  }

  function isValidIPv4Mask($mask)
  {
    $good_masks = array("255.255.240.0","255.255.248.0","255.255.252.0","255.255.254.0","255.255.255.0","255.255.255.128","255.255.255.192","255.255.255.224","255.255.255.240","255.255.255.248","255.255.255.252");
    $valid_mask = False;
    foreach ($good_masks as $test_mask){
      if ( $mask == $test_mask ){
        $valid_mask = True;
      }
    }
    return $valid_mask;
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Config Pi Admin</title>

    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <?php
      if (isset($_POST) && !empty($_POST)) {
        if ( $_POST["submit"] && $error_count == 0 ) {
          // then we might need to show a button that will allow us to apply the network changes.
          echo('<form class="form-horizontal" role="form" method="post" action="index.php">');
          echo('  <h2>Apply Settings</h1>');
          echo('  <div class="form-group">');
          echo('    <div class="col-sm-offset-2 col-sm-10">');
          echo('      <input id="submit" name="submit" type="submit" value="Apply Settings" class="btn btn-primary">');
          echo('    </div>');
          echo('  </div>');
          echo('</form>');
        }
      }
    ?>
    <h1>Pi Settings</h1>
    <form class="form-horizontal" role="form" method="post" action="index.php">
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <div class="checkbox">
            <label><input type="checkbox" name="dhcp" id="dhcp" value="Yes">DHCP</label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="host_name">Host Name:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="host_name" name="host_name" placeholder="configPi" value="<?php 
            if (isset($file_hostname) && !empty($file_hostname)) {
              echo htmlspecialchars($file_hostname);
            } elseif (isset($_POST) && !empty($_POST['host_name'])) {
              echo htmlspecialchars($_POST['host_name']);
            } 
            ?>">
          <?php 
            if (isset($errHostname)) {
              echo "<p class='text-danger'>$errHostname</p>";
            }
          ?>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="ip_address">IP Address:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="ip_address" name="ip_address" placeholder="192.168.1.20" value="<?php 
            if (isset($file_ip) && !empty($file_ip)) {
              echo htmlspecialchars($file_ip);
            } elseif (isset($_POST) && !empty($_POST)) {
              echo htmlspecialchars($_POST['ip_address']);
            } 
            ?>">
          <?php 
            if (isset($errIPAddress)) {
              echo "<p class='text-danger'>$errIPAddress</p>";
            }
          ?>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="subnet_mask">Subnet Mask:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="subnet_mask" name="subnet_mask" placeholder="255.255.255.0" value="<?php
            if (isset($file_subnet) && !empty($file_subnet)) {
              echo htmlspecialchars($file_subnet);
            } elseif (isset($_POST) && !empty($_POST)) {
              echo htmlspecialchars($_POST['subnet_mask']);
            } 
            ?>">
          <?php 
            if (isset($errSubnet_mask)) {
              echo "<p class='text-danger'>$errSubnet_mask</p>";
            }
          ?>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="gateway">Gateway:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="gateway" name="gateway" placeholder="192.168.1.1" value="<?php 
            if (isset($file_gateway) && !empty($file_gateway)) {
              echo htmlspecialchars($file_gateway);
            } elseif (isset($_POST) && !empty($_POST)) {
              echo htmlspecialchars($_POST['gateway']);
            } 
            ?>">
          <?php 
            if (isset($errGateway)) {
              echo "<p class='text-danger'>$errGateway</p>";
            }
          ?>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <input id="submit" name="submit" type="submit" value="Submit" class="btn btn-primary">
        </div>
      </div>
    </form>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../jquery/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
    <script>
      $(document).ready(function(){
        $("input#dhcp").click(function () {
          if ($("input#dhcp").prop('checked')) {
            // Now I need to hide the unnecessary elements
            $("input#ip_address").prop('disabled', true);
            $("input#subnet_mask").prop('disabled', true);
            $("input#gateway").prop('disabled', true);
          }
          else {
            // Now I need to unhide the necessary elements
            $("input#ip_address").prop('disabled', false);
            $("input#subnet_mask").prop('disabled', false);
            $("input#gateway").prop('disabled', false);
          }
        });
        var isDHCP = "<?php echo $dhcp; ?>";
        if (isDHCP == "YES") {
          $("input#dhcp").prop('checked', true);
          // Now I need to disable the corresponding elements
          $("input#ip_address").prop('disabled', true);
          $("input#subnet_mask").prop('disabled', true);
          $("input#gateway").prop('disabled', true);
        }
      });
    </script>
  </body>
</html>
