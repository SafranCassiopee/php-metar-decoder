<?php

// This is a page to try the decoder through a web browser

require_once dirname(__FILE__) . '/../src/MetarDecoder.inc.php';
include('util.php');
use MetarDecoder\MetarDecoder;
use utilphp\util;


$raw_metar = htmlspecialchars(trim($_GET['metar']));
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

            <? if(strlen($raw_metar) > 0) { ?>
                <br>
                <div style="text-align:center">
                    <span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span>
                </div>
                <br>
                <? if ($d->isValid()){ ?>
                     <div class="alert alert-success">
                        <b>Valid format</b>
                    </div>
                <? }else{ ?>
                   <div class="alert alert-danger">
                        <b>Invalid format:</b><br>
                        <ul>
                        <?
                           foreach($d->getDecodingExceptions() as $e){
                               echo('<li>'.$e->getMessage());
                               echo(', on chunk "'.$e->getChunk().'"</li>');
                           }
                           $d->resetDecodingExceptions();
                        ?>
                        </ul>
                    </div>
                <? } ?>
                <div><?
                    $raw_dump = util::var_dump($d,true,2);

                    $to_delete=array(
                        'private:MetarDecoder\\Entity\\DecodedMetar:',
                        'private:MetarDecoder\\Entity\\',
                        'MetarDecoder\\Entity\\',
                        'Value:'
                    );
                    $clean_dump = str_replace($to_delete,'',$raw_dump);
                    echo $clean_dump;
                ?></div>
            </div>
            <? } else { ?>
            </div>
                <br>
                <div class="alert alert-info">
                Need inspiration ? What about:
                <ul>
                    <li><a href="./index.php?metar=CYFB+271515Z+32017KT+3SM+DRSN+BKN040+M29%2FM34+A2957+RMK+SC7+SLP019">CYFB 271515Z 32017KT 3SM DRSN BKN040 M29/M34 A2957 RMK SC7 SLP019</a></li>
                    <li><a href="./index.php?metar=EETU+271450Z+05005KT+9000+OVC006+01%2FM00+Q1019">EETU 271450Z 05005KT 9000 OVC006 01/M00 Q1019</a></li>
                    <li><a href="./index.php?metar=SBGU+271400Z+27006KT+8000+TS+VCSH+BKN020+FEW030CB+BKN080+25%2F20+Q1017">SBGU 271400Z 27006KT 8000 TS VCSH BKN020 FEW030CB BKN080 25/20 Q1017</a></li>

                </ul>
                </div>
            <? } ?>
        </div>

    
    </body>
</html>


