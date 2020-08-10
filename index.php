<?php

    include("phpQuery.php");

    $country = !empty($_GET["country"]) ? $_GET["country"] : "morocco";

    $url = 'https://www.worldometers.info/coronavirus/country/' . $country;
    $document = file_get_contents($url);

    // Init
    $document = phpQuery::newDocument($document);

    // Get picture url from <img> inside the first <h1>
    $country_img = $document->find("h1:first img")->attr("src");

    // Get the first <h1>
    $country_name = $document->find("h1:first")->html();
    // Split content to get country name & remove spaces
    $country_name = trim(preg_replace("/\xc2\xa0/", "", explode("</div>", $country_name)[1]));

    // Get text from the second <h1>
    $country_cases = trim($document->find("h1:eq(1)")->next()->find("span")->text());
    // Apply some changes
    $country_cases = str_replace(",", "", $country_cases);

    // Get text from the fourth <h1>
    $country_recovred = trim($document->find("h1:eq(3)")->next()->find("span")->text());
    $country_recovred = str_replace(",", "", $country_recovred);

    // Get text from the third <h1>
    $country_deaths = trim($document->find("h1:eq(2)")->next()->find("span")->text());
    $country_deaths = str_replace(",", "", $country_deaths); // Apply some changes

    // Get text using id attr
    $last_update = $document->find("#page-top")->next()->text();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PHP Scrape</title>
</head>
<body>
<h1 style="text-align: center;">PHP Scrape Example</h1>
<h3 style="text-align: center;">Using phpQuery Library</h3>
<br>
<table border="2" cellpadding="5" cellspacing="0" style="margin: 0 auto; width: 300px;">
    <tr>
        <td colspan="2" style="text-align: center;">COVID-19 CORONAVIRUS PANDEMIC</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;"><img style="vertical-align: middle; width: 100%; border: 1px solid black;" src="https://www.worldometers.info<?=$country_img?>"></td>
    </tr>
    <tr>
        <td style="width: 100px;">Country</td>
        <td style="width:"><strong><?=$country_name?></strong></td>
    </tr>
    <tr>
        <td>Cases</td>
        <td style="color: blue;"><strong><?=number_format($country_cases,0,'',' ')?></strong></td>
    </tr>
    <tr>
        <td>Recovered</td>
        <td style="color: green;"><strong><?=number_format($country_recovred,0,'',' ')?></strong></td>
    </tr>
    <tr>
        <td>Deaths</td>
        <td style="color: red;"><strong><?=number_format($country_deaths,0,'',' ')?></strong></td>
    </tr>
    <tr>
        <td>In Hospitals</td>
        <td style="color: orange;"><strong><?=number_format($country_cases-$country_recovred-$country_deaths,0,'',' ')?></strong></td>
    </tr>
</table>
<br>
<h5 style="text-align: center; color: gray;"><?=$last_update?></h5>
<h5 style="text-align: center;"><a href="<?=$url?>" target="_blank">:: Source ::</a></h5>
</body>
</html>
