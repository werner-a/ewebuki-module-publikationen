<?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// "$Id: publikationen-list.inc.php 2035 2015-03-09 16:29:52Z werner.ammon@gmail.com $";
// "publikationen - list funktion";
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

        // funktions bereich
        // ***

        ### put your code here ###

        /* z.B. db query */

        $sql = "SELECT *
                  FROM ".$cfg["publikationen"]["db"]["leer"]["entries"];
        if ( $debugging["sql_enable"] ) $debugging["ausgabe"] .= "sql: ".$sql.$debugging["char"];

        // seiten umschalter
        if ( !isset($environment["parameter"][1]) ) $environment["parameter"][1] = null;
        $parameter = null; $getvalues = null;
        $inhalt_selector = inhalt_selector( $sql, $environment["parameter"][1], $cfg["publikationen"]["db"]["leer"]["rows"], $parameter, 1, 3, $getvalues );
        $ausgaben["inhalt_selector"] = $inhalt_selector[0]."<br />";
        $sql = $inhalt_selector[1];
        $ausgaben["anzahl"] = $inhalt_selector[2];

        $result = $db -> query($sql);
        while ( $data = $db -> fetch_array($result,1) ) {

            // platz fuer vorbereitungen hier z.B.tabellen farben wechseln
            if ( !isset($cfg["publikationen"]["color"]["set"]) ) $cfg["publikationen"]["color"]["set"] = null;
            if ( $cfg["publikationen"]["color"]["set"] == $cfg["publikationen"]["color"]["a"]) {
                $cfg["publikationen"]["color"]["set"] = $cfg["publikationen"]["color"]["b"];
            } else {
                $cfg["publikationen"]["color"]["set"] = $cfg["publikationen"]["color"]["a"];
            }

            // wie im einfachen modul k�nnten nur die marken !{0}, !{1} befuellt werden
            #$dataloop["list"][$data["id"]][0] = $data["field1"];
            #$dataloop["list"][$data["id"]][1] = $data["field2"];

            // der uebersicht halber fuellt das erweiterte modul aber einzeln benannte marken
            $dataloop["list"][$data["id"]] = array(
                                   "color" => $cfg["publikationen"]["color"]["set"],
                                  "field1" => $data["field1"],
                                    "edit" => $cfg["publikationen"]["basis"]."/edit,".$data["id"].".html",
                                  "delete" => $cfg["publikationen"]["basis"]."/delete,".$data["id"].".html",
                                 "details" => $cfg["publikationen"]["basis"]."/details,".$data["id"].".html",
            );
        }
        // +++
        // funktions bereich


        // page basics
        // ***

        // fehlermeldungen
        if ( isset($_GET["error"]) ) {
            if ( $_GET["error"] == 1 ) {
                $ausgaben["form_error"] = "#(error1)";
            }
        } else {
            $ausgaben["form_error"] = null;
        }

        // navigation erstellen
        $ausgaben["link_new"] = $cfg["publikationen"]["basis"]."/add.html";

        // hidden values
        #$ausgaben["form_hidden"] = null;

        // was anzeigen
        #$cfg["publikationen"]["path"] = str_replace($pathvars["virtual"],null,$cfg["publikationen"]["basis"]);
        #$mapping["main"] = eCRC($cfg["publikationen"]["path"]).".list";
        $mapping["main"] = $cfg["publikationen"]["name"].".list";
        
        #$mapping["navi"] = "publikationen";

        // unzugaengliche #(marken) sichtbar machen
        if ( isset($_GET["edit"]) ) {
            $ausgaben["inaccessible"] = "inaccessible values:<br />";
            $ausgaben["inaccessible"] .= "# (error1) #(error1)<br />";
            $ausgaben["inaccessible"] .= "# (edittitel) #(edittitel)<br />";
            $ausgaben["inaccessible"] .= "# (deletetitel) #(deletetitel)<br />";
        } else {
            $ausgaben["inaccessible"] = null;
        }

        // wohin schicken
        #n/a

        // +++
        // page basics

    } else {
        header("Location: ".$pathvars["virtual"]."/");
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>