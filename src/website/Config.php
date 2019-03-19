<?php
error_reporting(0);

$loginCredentials = parse_ini_file('config.ini');
if (!empty($loginCredentials['host']) || !empty($loginCredentials['database']) ||
    !empty($loginCredentials['username']) || !empty($loginCredentials['password']) ||
    !empty($loginCredentials['city']) || !empty($loginCredentials['state'])) 
{
    header('Location: index.php');  
}

require ('Db.php');

function write_ini_file($file, $array = [])
{
    if (!is_string($file))
    {
        throw new InvalidArgumentException('Argument must be a string.');
    }
    if (!is_array($array))
    {
        throw new InvalidArgumentException('Argument must be a string.');
    }
    $data = array();
    foreach($array as $key => $val)
    {
        if (is_array($val))
        {
            $data[] = "[$key]";
            foreach($val as $skey => $sval)
            {
                if (is_array($sval))
                {
                    foreach($sval as $_skey => $_sval)
                    {
                        if (is_numeric($_skey))
                        {
                            $data[] = $skey . '[] = ' . (is_numeric($_sval) ? $_sval : (ctype_upper($_sval) ? $_sval : '"' . $_sval . '"'));
                        }
                        else
                        {
                            $data[] = $skey . '[' . $_skey . '] = ' . (is_numeric($_sval) ? $_sval : (ctype_upper($_sval) ? $_sval : '"' . $_sval . '"'));
                        }
                    }
                }
                else
                {
                    $data[] = $skey . ' = ' . (is_numeric($sval) ? $sval : (ctype_upper($sval) ? $sval : '"' . $sval . '"'));
                }
            }
        }
        else
        {
            $data[] = $key . ' = ' . (is_numeric($val) ? $val : (ctype_upper($val) ? $val : '"' . $val . '"'));
        }
    }

    $fp = fopen($file, 'w');
    $retries = 0;
    $max_retries = 100;
    if (!$fp)
    {
        return false;
    }

    do
    {
        if ($retries > 0)
        {
            usleep(rand(1, 5000));
        }

        $retries+= 1;
    }

    while (!flock($fp, LOCK_EX) && $retries <= $max_retries);
    if ($retries == $max_retries)
    {
        return false;
    }
    fwrite($fp, implode(PHP_EOL, $data) . PHP_EOL);
    flock($fp, LOCK_UN);
    fclose($fp);
    return true;
}

if ($_POST)
{
    if (isset($_POST['inputHost']) && $_POST['inputHost'] && 
        isset($_POST['inputDatabase']) && $_POST['inputDatabase'] && 
        isset($_POST['inputUsername']) && $_POST['inputUsername'] && 
        isset($_POST['inputPassword']) && $_POST['inputPassword'] && 
        isset($_POST['inputKey']) && $_POST['inputKey'] &&
        isset($_POST['inputCity']) && $_POST['inputCity'] &&
        isset($_POST['inputState']) && $_POST['inputState'])
    {
        $config['host'] = $_POST['inputHost'];
        $config['database'] = $_POST['inputDatabase'];
        $config['username'] = $_POST['inputUsername'];
        $config['password'] = $_POST['inputPassword'];
        $config['key'] = $_POST['inputKey'];
        $config['city'] = $_POST['inputCity'];
        $config['state'] = $_POST['inputState'];
        write_ini_file('config.ini', $config);

        $loginCredentials = parse_ini_file('config.ini');
        Db::connect($loginCredentials['host'], $loginCredentials['database'], $loginCredentials['username'], $loginCredentials['password']);
        Db::query('CREATE TABLE IF NOT EXISTS `meteodb` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `date` date NOT NULL,
                    `time` time NOT NULL,
                    `temperature` float NOT NULL,
                    `humidity` float NOT NULL,
                    `pressure` float NOT NULL,
                    `uv` float NOT NULL,
                    `ir` float NOT NULL,
                    `altitude` float NOT NULL,
                    `airquality` float NOT NULL,
                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1');
        
        header('Location: index.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="author" content="Daniel Zábojník" />
        <meta name="description" content="Hi, I am Mia, the weather station." />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Mia - Settings</title>
        <link rel="shortcut icon" href="favicon.ico" />

        <!-- External styles -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <!-- Local styles -->
        <link rel="stylesheet" href="css/config.css" />
    </head>

    <body>
      <form class="form-signin" method="POST">
        <div class="text-center mb-4">
          <h1 class="h3 mb-3 font-weight-normal">Settings</h1>
          <p>The last thing to do is set up the connection to the database, enter the authorization key and your location.</p>
        </div>

        <div class="form-label-group">
          <input type="text" name="inputHost" class="form-control" placeholder="Database server" required autofocus>
          <label for="inputHost">Database server</label>
        </div>

        <div class="form-label-group">
          <input type="text" name="inputDatabase" class="form-control" placeholder="Database name" required>
          <label for="inputDatabase">Database name</label>
        </div>
      
        <div class="form-label-group">
          <input type="text" name="inputUsername" class="form-control" placeholder="Username" required>
          <label for="inputUsername">Username</label>
        </div>

        <div class="form-label-group">
          <input type="password" name="inputPassword" class="form-control" placeholder="Password" required>
          <label for="inputPassword">Password</label>
        </div>
      
        <div class="form-label-group">
         <input type="text" name="inputKey" class="form-control" placeholder="Verification key" required>
          <label for="inputKey">Verification key</label>
        </div>

        <div class="form-label-group">
         <input type="text" name="inputCity" class="form-control" placeholder="City / Town / Village" required>
          <label for="inputCity">City / Town / Village</label>
        </div>

        <div class="form-label-group">
         <input type="text" name="inputState" class="form-control" placeholder="State" required>
          <label for="inputState">State</label>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Set up</button>
      </form>
    </body>
</html>