<?php

// This is a page to try the decoder through a web browser

require_once dirname(__FILE__) . '/../src/MetarDecoder.inc.php';
use MetarDecoder\MetarDecoder;

$raw_metar = $_GET['metar'];
$decoder = new MetarDecoder();
$d = $decoder->parse($raw_metar);

?>

<!DOCTYPE html>
<html>
    <head>
        <!-- Bootstrap over CDN -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
          <div class="header">
            <ul class="nav nav-pills pull-right">
                <li class="active">Live demo</li>
            </ul>
            <h3 class="text-muted">php-metar-decoder</h3>
            <br>
          </div>

          <div class="jumbotron">
            <h2>Decode any raw METAR:</h2>
            <br>
            
            <!-- metar form -->
            <form class="form-inline" action="index.php" method="get">
              <div class="form-group" >
                <div class="input-group">
                  <div class="input-group-addon input-lg">METAR</div>
                  <input type="text" name="metar" class="form-control input-lg" style="width:600px" value="<?php echo($raw_metar);?>">
                </div>
                <input type="submit" class="btn btn-primary btn-lg" value="Decode">
              </div>
            </form>
            
            <br>
            <div style="text-align:center">
                <span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span>
            </div>
            <br>
            
            <? if(!$d->isValid()){ ?>
                <div class="alert alert-danger">
                    <b>Invalid format:</b>
                    <? echo($d->getException()->getMessage()); 
                       echo(', on chunk "'.$d->getException()->getChunk().'"');?>
                </div>
            <? } ?>  
            <table class="table table-bordered" style="font-size: 1.3em">
                <tr>
                    <td>Report type</td>
                    <td class="success"><? echo($d->getType()); ?></td>
                </tr>
               <tr>
                    <td>ICAO</td>
                    <td class="success"><? echo($d->getIcao()); ?></td>
                </tr>
                <tr>
                    <td>Day of month</td>
                    <td class="success"><? echo($d->getDay()); ?></td>
                </tr>
                <tr>
                    <td>Time</td>
                    <td class="success"><? echo($d->getTime()->format('H:i').' UTC'); ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <? if($d->getStatus() != null){?>
                        <td class="success"><? echo($d->getStatus()); ?></td>
                    <? }else {?>
                        <td class="active"></td>
                    <? } ?>
                </tr>
                <!-- other tags for td: danger(red) / active (grey) / warning (orange)-->
            </table>
          </div>
        </div>
    
    </body>
</html>


