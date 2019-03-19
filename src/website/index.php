<?php
error_reporting(0); 

$loginCredentials = parse_ini_file('config.ini');
if (empty($loginCredentials['host']) || empty($loginCredentials['database']) ||
    empty($loginCredentials['username']) || empty($loginCredentials['password']) ||
    empty($loginCredentials['city']) || empty($loginCredentials['state'])) 
{
    header('Location: Config.php');  
}

require("Data.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="author" content="Daniel Zábojník" />
        <meta name="description" content="Hi, I am Mia, the weather station." />
        <meta name="viewport" content="user-scalable=no" />
        <title>Mia</title>
        <link rel="shortcut icon" href="favicon.ico" />

        <!-- External scripts -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        
        <!-- Local scripts -->
        <script src="js/Chart.min.js"></script>
        <script src="js/Chart.js"></script>

        <!-- Local styles -->
        <link rel="stylesheet" href="css/main.css" />
    </head>

    <body>
        <main>
            <article>
                <header>
                    <h1 id="header-text">
                        Hi, I am <strong>Mia</strong>, the weather station.
                    </h1>

                    <div id="data">
                        <div id="data-location">
                            <div id="data-location-city">
                                <h2><?php echo(htmlspecialchars($loginCredentials['city']) . ', ' . htmlspecialchars($loginCredentials['state'])); ?></h2>
                            </div>

                            <div id="data-location-other">
                                <div><img src="images/icons/altitude.svg" class="icon" /><?php echo(htmlspecialchars($altitude)); ?> meters</div>
                                <div><?php echo(htmlspecialchars($date)); ?></div>
                            </div>
                        </div>

                        <div id="data-weather">
                            <div>
                                <img src="images/icons/thermometer.svg" class="icon" /> <strong><?php echo(htmlspecialchars($temperature)); ?></strong> °C
                            </div>
                            <div>
                                <img src="images/icons/humidity.svg" class="icon" /> <strong><?php echo(htmlspecialchars($humidity)); ?></strong> %
                            </div>
                            <div>
                                <img src="images/icons/barometer.svg" class="icon" /> <strong><?php echo(htmlspecialchars($pressure)); ?></strong> hPa
                            </div>
                            <div>
                                <img src="images/icons/day-sunny.svg" class="icon" /> <strong><?php echo(htmlspecialchars($uv)); ?></strong>
                            </div>
                        </div>
                    </div>
                </header>

                <section>
                    <h2>Data from last 24 hours</h2>

                    <div class="charts">
                        <div class="chart">
                            <h3>Temperature (°C)</h3>
                            <canvas  id="temperature"></canvas>
                        </div>
                        <div class="chart">
                            <h3>Humidity (%)</h3>
                            <canvas id="humidity"></canvas>
                        </div>
                        <div class="chart">
                            <h3>Pressure (hPa)</h3>
                            <canvas id="pressure"></canvas>
                        </div>
                        <div class="chart">
                            <h3>UV Index</h3>
                            <canvas id="uv"></canvas>
                        </div>
                        <div class="chart">
                            <h3>Air quality (ppm)</h3>
                            <canvas id="airquality"></canvas>
                        </div>                        
                    </div>                    
                </section>

                <footer>
                    Copyright © 2018 Mia. All rights reserved. Created by <a href="http://www.danielzabojnik.cz" target="_blank">Daniel Zábojník.</a>
                </footer>
            </article>
        </main>
    </body>
</html>