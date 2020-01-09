<?php
include "simple_html_dom.php";
include "db.php";
header('Content-Type: text/html; charset=ISO-8859-1');


$ch = curl_init();
// set url
curl_setopt($ch, CURLOPT_URL, "https://www.olx.ro/imobiliare/apartamente-garsoniere-de-vanzare/q-ultracentral/");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);

// close curl resource to free up system resources
curl_close($ch);

//echo $output;
$html = new simple_html_dom();
$html->load($output);
global $conn;
        foreach ($html->find('.space') as $div) {
            foreach ($div->find('.lheight22') as $title) {
                $final_title = strip_tags($title->plaintext);
                $final_title = str_replace(" ", "", $final_title);
                $final_title = mb_convert_encoding($final_title, 'ISO-8859-1', 'utf-8');
            }

            foreach ($div->find('.price') as $price) {
                $final_price = strip_tags($price->plaintext);
                $final_price = str_replace(" ", "", $final_price);
            }

            foreach ($div->find('a') as $link) {
                $final_link = $link->getAttribute("href");
                $links = strip_tags($final_link);
            }

            $query = "INSERT INTO entries (title,link,price) ";
            $query .= "VALUES ('$final_title', '$links', '$links') ";

            if(!$query){
            die("QUERY FAILED " . mysqli_error($conn));
                }
        mysqli_query($conn,$query);
        }
