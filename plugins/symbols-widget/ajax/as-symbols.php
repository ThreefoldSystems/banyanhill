<?php

require $_SERVER['DOCUMENT_ROOT'].'/wp-blog-header.php';

date_default_timezone_set('America/Los_Angeles');
function removeBOM($str="") {
    if(substr($str, 0, 3) == pack('CCC', 0xef, 0xbb, 0xbf)) {
        $str = substr($str, 3);
    }
     return $str;
}

//$base_url = (($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http")."://".$_SERVER['HTTP_HOST'];
$base_url = 'http://'.$_SERVER['HTTP_HOST'];
$base_url = removeBOM($base_url);

if (isset($_GET['term'])) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://api.rightwaytrader.com/v.0.39.1/symbols.asmx/GetSymbolsPaged?availableCategoryIds=&text=".$_GET['term']."&isSymbol=true&comparisonType=Start&firstRecord=1&lastRecord=20&fieldToSort=&sortType=ASC&isDelisted=false");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $result = curl_exec($ch);
    curl_close($ch);

    $xml = new SimpleXMLElement($result);

    $result = array();
    foreach ($xml->UpdatedSymbol as $updatedSymbol) {
        $result[] = array('name' => $updatedSymbol->Symbol.'', 'symbol' => $updatedSymbol->Symbol.'', 'symbol_id' => $updatedSymbol->SymbolId.'');
    }

    unset($result[count($result) - 1]);

    echo json_encode($result);
} else if (isset($_GET['term1'])) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://api.rightwaytrader.com/v.0.39.1/symbols.asmx/GetSymbolsPaged?availableCategoryIds=&text=".$_GET['term1']."&isSymbol=true&comparisonType=Start&firstRecord=1&lastRecord=20&fieldToSort=&sortType=ASC&isDelisted=false");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $result = curl_exec($ch);
    curl_close($ch);

    $xml = new SimpleXMLElement($result);

    $result = array();
    foreach ($xml->UpdatedSymbol as $updatedSymbol) {
        $result[] = array('name' => $updatedSymbol->Name.' ('.$updatedSymbol->Symbol.')', 'symbol' => $updatedSymbol->Symbol.'', 'symbol_id' => $updatedSymbol->SymbolId.'');
    }

    unset($result[count($result) - 1]);

    echo json_encode($result);
} else if (isset($_GET['symbol'])) {
    $uid = md5(serialize($_GET));
    if (!is_dir(dirname(__FILE__).'/../temp/'.$uid)) {
        mkdir(dirname(__FILE__).'/../temp/'.$uid, 0755);
    }

    $chart_file = null;
    $d = dir(dirname(__FILE__).'/../temp/'.$uid);
    while (false !== ($entry = $d->read())) {
        if ($entry[0] != '.') {
            $chart_file = $entry;
        }
    }
    $d->close();
    
    if (!is_null($chart_file)) {
        $timestamp = substr($chart_file, 0, strpos($chart_file, '.'));
        if (time() - $timestamp < 5 * 60) {
            echo $base_url.'/wp-content/plugins/symbols-widget/temp/'.$uid.'/'.$chart_file; exit;
        }
    }
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://api.rightwaytrader.com/v.0.39.1/chartupdate.asmx/CheckSymbolsList?symbols=".$_GET['symbol']);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $result = curl_exec($ch);
    curl_close($ch);

    $xml = new SimpleXMLElement($result);
    $date_mark = strtotime($xml);

    if ($result && !is_file(dirname(__FILE__).'/../temp/'.$uid.'/'.$date_mark.'.png')) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://charts.rightwaytrader.com/v.0.1.1/mscharts/marketdatainfochart.asmx/GetWidgetChart?symbol=".$_GET['symbol']."&daysCount=30&caption=&width=".(isset($_GET['width']) ? $_GET['width'] : 253)."&height=".(isset($_GET['width']) ? round($_GET['width'] * 25 / 47) : 100));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        curl_close($ch);

        if ($result === false) {
            echo plugins_url( 'temp/'.$uid.'/'.$chart_file , dirname(__FILE__) );
            exit;
        } else {
	    $xml = new SimpleXMLElement($result);

	    $fp = fopen(dirname(__FILE__).'/../temp/'.$uid.'/'.$date_mark.'.png', 'w');
	    fwrite($fp, base64_decode(str_replace(' ', '+', $xml)));
	    fclose($fp);

	    if ($date_mark.'.png' != $chart_file) {
		if (!is_null($chart_file)) {
		    unlink(dirname(__FILE__).'/../temp/'.$uid.'/'.$chart_file);
		}
	    }
        }
    } else {
        if ($date_mark.'.png' != $chart_file) {
            if (!is_null($chart_file)) {
                unlink(dirname(__FILE__).'/../temp/'.$uid.'/'.$chart_file);
            }
        }
    }

    $chart_file = $date_mark.'.png';
    echo plugins_url( 'temp/'.$uid.'/'.$chart_file , dirname(__FILE__) );
} else if (isset($_GET['symbolA'])) {
    $uid = md5(serialize($_GET));
    if (!is_dir(dirname(__FILE__).'/../temp/'.$uid)) {
        mkdir(dirname(__FILE__).'/../temp/'.$uid, 0755);
    }

    $chart_file = null;
    $d = dir(dirname(__FILE__).'/../temp/'.$uid);
    while (false !== ($entry = $d->read())) {
        if ($entry[0] != '.') {
            $chart_file = $entry;
        }
    }
    $d->close();

    if (!is_null($chart_file)) {
        $timestamp = substr($chart_file, 0, strpos($chart_file, '.'));
        if (time() - $timestamp < 5 * 60) {
            echo $base_url.'/wp-content/plugins/symbols-widget/temp/'.$uid.'/'.$chart_file; exit;
        }
    }


    $period = '1d';
    if (in_array($_GET['period'], array('1d', '5d', '1m', '3m', '1y', '5y', '10y'))) {
        $period = $_GET['period'];
    }

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://api.rightwaytrader.com/v.0.39.1/chartupdate.asmx/CheckSymbolsList?symbols=".$_GET['symbolA']);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $result = curl_exec($ch);
    curl_close($ch);
    
    $xml = new SimpleXMLElement($result);
    $date_mark = strtotime($xml);

    if ($result && !is_file(dirname(__FILE__).'/../temp/'.$uid.'/'.$date_mark.'.png') || in_array($period, array('1d', '5d'))) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://charts.rightwaytrader.com/v.0.1.1/mscharts/marketdatainfochart.asmx/GetDailyChart?symbol=".$_GET['symbolA']."&periodType=".$period."&caption=&width=".(isset($_GET['width']) ? $_GET['width'] : 253)."&height=".(isset($_GET['width']) ? round($_GET['width'] * 25 / 47) : 100));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        curl_close($ch);

        if ($result === false) {
            echo plugins_url( 'temp/'.$uid.'/'.$chart_file , dirname(__FILE__) );
            exit;
        } else {
	    $xml = new SimpleXMLElement($result);

	    $fp = fopen(dirname(__FILE__).'/../temp/'.$uid.'/'.$date_mark.'.png', 'w');
	    fwrite($fp, base64_decode(str_replace(' ', '+', $xml)));
	    fclose($fp);

	    if ($date_mark.'.png' != $chart_file) {
		if (!is_null($chart_file)) {
		    unlink(dirname(__FILE__).'/../temp/'.$uid.'/'.$chart_file);
		}
	    }
	}
    } else {
        if ($date_mark.'.png' != $chart_file) {
            if (!is_null($chart_file)) {
                unlink(dirname(__FILE__).'/../temp/'.$uid.'/'.$chart_file);
            }
        }
    }

    $chart_file = $date_mark.'.png';

    echo plugins_url( 'temp/'.$uid.'/'.$chart_file , dirname(__FILE__) );
} else if (isset($_GET['action'])) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://charts.rightwaytrader.com/v.0.1.1/mscharts/marketdatainfochart.asmx/GetRatioChart?firstSymbolId=".$_GET['symbol_1_id']."&secondSymbolId=".$_GET['symbol_2_id']."&daysCount=100&caption=&width=".(isset($_GET['width']) ? 388 : 188)."&height=".(isset($_GET['width']) ? 230 : 100));
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
    $result = curl_exec($ch);
    curl_close($ch);

    $xml = new SimpleXMLElement($result);

    echo 'data: image/png;base64, '.$xml;
}