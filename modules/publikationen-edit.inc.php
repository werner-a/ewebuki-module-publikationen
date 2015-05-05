<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// "$Id: publikationen-edit.inc.php 2035 2015-03-09 16:29:52Z werner.ammon@gmail.com $";
// "publikationen - edit funktion";
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
    eWeBuKi - a easy website building kit
    Copyright (C)2001-2015 Werner Ammon ( wa<at>chaos.de )

    This script is a part of eWeBuKi

    eWeBuKi is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    eWeBuKi is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with eWeBuKi; If you did not, you may download a copy at:

    URL:  http://www.gnu.org/licenses/gpl.txt

    You may also request a copy from:

    Free Software Foundation, Inc.
    59 Temple Place, Suite 330
    Boston, MA 02111-1307
    USA

    You may contact the author/development team at:

    Chaos Networks
    c/o Werner Ammon
    Lerchenstr. 11c

    86343 Koenigsbrunn

    URL: http://www.chaos.de
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    if ( $cfg["publikationen"]["right"] == "" || $rechte[$cfg["publikationen"]["right"]] == -1 ) {

        // page basics
        // ***

        if ( count($_POST) == 0 ) {
            $sql = "SELECT *
                      FROM ".$cfg["publikationen"]["db"]["main"]["entries"]."
                     WHERE ".$cfg["publikationen"]["db"]["main"]["key"]."='".$environment["parameter"][1]."'";
            if ( $debugging["sql_enable"] ) $debugging["ausgabe"] .= "sql: ".$sql.$debugging["char"];
            $result = $db -> query($sql);
            $form_values = $db -> fetch_array($result,1);
        } else {
            $form_values = $_POST;
        }

        //$debugging["ausgabe"] .= "<pre>Session: ".print_r($_SESSION, True)."</pre>";
        $referer = @$_SERVER['HTTP_REFERER'];
        if ( strstr($referer, "fileed/list") ) {          
            if ( count(@$_SESSION["file_memo"]) == 1 ) {
                $debugging["ausgabe"] .= "<pre>File ID: ".print_r($_SESSION["file_memo"], True)."</pre>";
                $bild_id = array_merge($_SESSION["file_memo"]);
                $debugging["ausgabe"] .= "<pre>New File ID: ".print_r($bild_id, True)."</pre>";
                $form_values["bild_id"] = $bild_id[0];
                unset($_SESSION["file_memo"]);
            } else {
                $header = $pathvars["virtual"]."/admin/fileed/list.html";
                $ausgaben["form_error"] = "Warnung: Es darf nur ein Bild ausgewählt werden.";
                #header("Location: ".$header); // hier würde ich am liebsten in den fileed zurück und dort nen fehler anzeigen
            }
        }

        // form options holen
        $form_options = form_options(eCRC($environment["ebene"]).".".$environment["kategorie"]);

        // form elememte bauen
        $element = form_elements( $cfg["publikationen"]["db"]["main"]["entries"], $form_values );

        // form elemente erweitern
        $element["extension1"] = "<input name=\"extension1\" type=\"text\" maxlength=\"5\" size=\"5\">";
        $element["extension2"] = "<input name=\"extension2\" type=\"text\" maxlength=\"5\" size=\"5\">";

        $sql = "SELECT *
                FROM site_file
                WHERE fid = ".$form_values["bild_id"];
        $result = $db -> query($sql);
        $data = $db -> fetch_array($result, 1);
        $ausgaben["img"] = $cfg["file"]["base"]["webdir"].
                          $data["ffart"]."/".
                          $data["fid"]."/tn/";             
        //$debugging["ausgabe"] .= "<pre>Picture Data: ".print_r($data, True)."</pre>";
        
        // +++
        // page basics


        // funktions bereich fuer erweiterungen
        // ***

        ### put your code here ###
        
        // +++
        // funktions bereich fuer erweiterungen


        // page basics
        // ***

        // fehlermeldungen
        if ( empty($ausgaben["form_error"]) ) $ausgaben["form_error"] = null;

        // navigation erstellen
        $ausgaben["form_aktion"] = $cfg["publikationen"]["basis"]."/edit,".$environment["parameter"][1].",verify.html";
        $ausgaben["form_break"] = $cfg["publikationen"]["basis"]."/list.html";

        // hidden values
        $ausgaben["form_hidden"] = null;

        // was anzeigen
        #$mapping["main"] = eCRC($environment["ebene"]).".modify";
        $mapping["main"] = $cfg["publikationen"]["name"].".modify";
        #$mapping["navi"] = "publikationen";

        // unzugaengliche #(marken) sichtbar machen
        if ( isset($_GET["edit"]) ) {
            $ausgaben["inaccessible"] = "inaccessible values:<br />";
            $ausgaben["inaccessible"] .= "# (error_result) #(error_result)<br />";
            $ausgaben["inaccessible"] .= "# (error_dupe) #(error_dupe)<br />";
        } else {
            $ausgaben["inaccessible"] = null;
        }

        // wohin schicken
        #n/a

        // +++
        // page basics

        if ( !isset($environment["parameter"][2]) ) $environment["parameter"][2] = null;
        if ( $environment["parameter"][2] == "verify"
            &&  ( isset($_POST["send"])
                || isset($_POST["change"])
                || isset($_POST["extension2"]) ) ) {

            // form eingaben prüfen
            form_errors( $form_options, $_POST );

            // evtl. zusaetzliche datensatz aendern
            if ( !isset($ausgaben["form_error"]) ) {

                // funktions bereich fuer erweiterungen
                // ***

                ### put your code here ###

                if ( isset($error) ) $ausgaben["form_error"] .= $db -> error("#(error_result)<br />");
                // +++
                // funktions bereich fuer erweiterungen
            }

            // datensatz aendern
            if ( !isset($ausgaben["form_error"]) ) {

                $kick = array( "PHPSESSID", "form_referer", "send", "change" );
                $sqla = null;
                foreach($_POST as $name => $value) {
                    if ( !in_array($name,$kick) && !strstr($name, ")" ) ) {
                        if ( isset($sqla) ) $sqla .= ", ";
                        $sqla .= $name."='".$value."'";
                    }
                }

                // Sql um spezielle Felder erweitern
                #$ldate = $_POST["ldate"];
                #$ldate = substr($ldate,6,4)."-".substr($ldate,3,2)."-".substr($ldate,0,2)." ".substr($ldate,11,9);
                #$sqla .= ", ldate='".$ldate."'";

                $sql = "update ".$cfg["publikationen"]["db"]["main"]["entries"]." SET ".$sqla." WHERE ".$cfg["publikationen"]["db"]["main"]["key"]."='".$environment["parameter"][1]."'";
                if ( $debugging["sql_enable"] ) $debugging["ausgabe"] .= "sql: ".$sql.$debugging["char"];
                $result  = $db -> query($sql);
                if ( !$result ) $ausgaben["form_error"] .= $db -> error("#(error_result)<br />");
                if ( !isset($header) ) $header = $cfg["publikationen"]["basis"]."/list.html";
            }

            // wenn es keine fehlermeldungen gab, die uri $header laden
            if ( !isset($ausgaben["form_error"]) ) {
                if ( isset($_POST["change"]) ) {
                    unset($_SESSION["file_memo"]);
                    $_SESSION["cms_last_edit"] = str_replace(",verify", "", $pathvars["requested"]);
                    $_SESSION["cms_last_referer"] = $ausgaben["form_referer"];
                    $_SESSION["cms_last_ebene"] = $_SESSION["ebene"];
                    $_SESSION["cms_last_kategorie"] = $_SESSION["kategorie"];
                    $header = $pathvars["virtual"]."/admin/fileed/list.html";
                }
                header("Location: ".$header);
            }
        }
    } else {
        header("Location: ".$pathvars["virtual"]."/");
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>