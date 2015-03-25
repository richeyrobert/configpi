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
    <h1>Pi Settings</h1>
    <form class="form-horizontal" role="form" method="post" action="index.php">
      <div class="form-group">
        <label class="control-label col-sm-2" for="host_name">Host Name:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="host_name" name="host_name" placeholder="configPi" value="<?php echo htmlspecialchars($_POST['host_name']); ?>">
          <?php echo "<p class='text-danger'>$errHostname</p>";?>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="ip_address">IP Address:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="ip_address" name="ip_address" placeholder="192.168.1.20" value="<?php echo htmlspecialchars($_POST['ip_address']); ?>">
          <?php echo "<p class='text-danger'>$errIPAddress</p>";?>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="subnet_mask">Subnet Mask:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="subnet_mask" name="subnet_mask" placeholder="255.255.255.0" value="<?php echo htmlspecialchars($_POST['subnet_mask']); ?>">
          <?php echo "<p class='text-danger'>$errSubnet_mask</p>";?>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2" for="gateway">Gateway:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="gateway" name="gateway" placeholder="192.168.0.1" value="<?php echo htmlspecialchars($_POST['gateway']); ?>">
          <?php echo "<p class='text-danger'>$errGateway</p>";?>
        </div>
      </div>
      <div class="form-group"> 
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default">Submit</button>
        </div>
      </div>
    </form>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../jquery/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>
