<?php
/**
 * This file is part of
 * Kimai - Open Source Time Tracking // http://www.kimai.org
 * (c) 2006-2009 Kimai-Development-Team
 *
 * Kimai is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; Version 3, 29 June 2007
 *
 * Kimai is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Kimai; If not, see <http://www.gnu.org/licenses/>.
 */

// ===================
// = DEBUG PROCESSOR =
// ===================

// insert KSPI
$isCoreProcessor = 0;
$dir_templates = "templates/";
require("../../includes/kspi.php");



switch ($axAction) {
    
    /**
     * Return the logfile in reverse order, so the last entries are shown first.
     */
    case "reloadLogfile":    
        $logdatei = WEBROOT . "temporary/logfile.txt";
        $fh = fopen($logdatei, 'r');
        
        $theData = "";
        $i = 0;
        
        $lines = $kga['logfile_lines'];
        $filearray = "";
        
        while (!feof($fh)) {
            $filearray[$i] = fgets($fh);
            $i++;
        }

        fclose($fh);

        if ($kga['logfile_lines'] != "@") {
            $start = count($filearray);
            $goal = $start - $lines;
            for ($line = $start - 1; ($line > $goal && $line > 0); $line--) {
                if ($filearray[$line] != "") $theData .= $filearray[$line] . "<br/>";
            }
        } else {
            foreach ($filearray as $line) {
                if ($line != "") $theData .= $line . "<br/>";
            }
        }
        
        echo $theData;
    break;
    
    /**
     * Empty the logfile.
     */
    case "clearLogfile":
        if ($kga['delete_logfile']) {
            $logdatei = fopen(WEBROOT . "temporary/logfile.txt", "w");
            fwrite($logdatei, "");
            fclose($logdatei);
            echo $kga['lang']['log_delete'];
        } else {
            die();
        }
    break;

    /**
     * Write some message to the logfile.
     */
    case "shoutbox":
        Kimai_Logger::logfile("text: " . $axValue);
    break;

    /**
     * Return the $kga variable (Kimai Global Array). Strip out some sensitive information.
     */
    case "reloadKGA":    
        $output = $kga;
        // clean out some data that is way too private to be shown in the frontend
        unset($output['server_hostname']);
        unset($output['server_database']);
        unset($output['server_username']);
        unset($output['server_password']);
        unset($output['password_salt']);
        unset($output['user']['secure']);
        unset($output['user']['userID']);
        unset($output['user']['pw']);
        unset($output['user']['password']);
        unset($output['user']['apikey']);
        
        echo '<pre>' . print_r($output) . '</pre>';
    break;
}
