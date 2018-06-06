<?php
/**
 * Created by PhpStorm.
 * User: Stephan Mai
 * Date: 04.12.2017
 * Time: 14:12
 */
/********** this generates the first SEPA and following SEPA transaction ********/
/*Mögliche Ausprägungen:
FRST (SEPA Erstlastschrift)
RCUR (SEPA Folgelastschrift)
OOFF (SEPA Einmal Lastschrift)
FNAL (Letzte SEPA Lastschrift)*/
function generateSepa($clients, SQL_data $SQL, $first_sepa_date = null, $follow_sepa_date = null)
{
    session_start();
    require_once 'KtoSepaSimple.php';
    //require_once 'SQL_data.php';

    require_once 'feirtage.php';
    require_once 'mail_sender.php';
    require_once 'zip_me.php';
    require_once 'SpecialChars_formatter.php';
    $toDay = getDate();
    // $_SESSION['Error']="no date";
    $month_Date        = date('M Y');
    if ($first_sepa_date == null)//||$first_sepa_date=0)
        $date = generateDate($toDay[0], NULL, 5);
    else {
        $date = generateDate($first_sepa_date);
    }
    if ($follow_sepa_date == null)//||$follow_sepa_date=0)
        $dateFollow = generateDate($toDay[0], NULL, 3);
    else{
        $dateFollow = generateDate($follow_sepa_date);
        $month_Date        = date('M Y',$follow_sepa_date);
    }

    $bundesland        = "NW";
    $empfaenger_F_name = "Die Urbane. Eine HipHop Partei";
    $empfaenger_N_name = "Die Urbane. Eine HipHop Partei";
    $empfaenger_iban   = 'DE524306096712163114';
    $empfaenger_bic    = 'GENODEM1GLS';
    $empfaenger_id     = 'DE14ZZZ00002057863';// - gläubiger ID
   //$month_Date        = date('M Y');
    $file_Date         = date('Y_m');
    $pre_date          = date('Y-m-d');
    $date              = date('Y-m-d', $date);
    $dateFollow        = date('Y-m-d', $dateFollow);
    try {
        $myKtoFirst     = new KtoSepaSimple();
        $myKtoFollow    = new KtoSepaSimple();
        $nameFormatter  = new SpecialChars_formatter(); // formating names to english e.g. Ä - Ae, é - e
        $first_Clients  = array();
        $follow_Clients = array();
        $no_Sepa        = array();
        $files          = array();
        foreach ($clients as $client) {
            if ($client['xt_memberfee'] >= 2.5 && $client['xt_iban'] != null && $client['disable'] != 1) {
                $transition_Date = date('Y-m-d\TH:i:s');
                //$firstName       = $nameFormatter->format_to_english(utf8_encode($client['firstname']));
                //$lastName        = $nameFormatter->format_to_english(utf8_encode($client['lastname']));
                //$bankOwner=getOwner($client,$nameFormatter);
                $getOwner  = function ($client, SpecialChars_formatter $formatter) {
                    if ($client['xt_bank_owner'] != "" && $client['xt_bank_owner'] != "-") {
                        $owner = utf8_encode($client['xt_bank_owner']);
                        $owner = $formatter->format_to_english($owner);
                        $pos   = strpos($owner, " ");
                        if ($pos) {
                            return substr($owner, $pos + 1, strlen($owner)) . ", " . substr($owner, 0, $pos);
                        } else {
                            return $owner;
                        }
                    } else {
                        return $formatter->format_to_english(utf8_encode($client['lastname'] . ", " . $client['firstname']));
                    }
                };
                $bankOwner = $getOwner($client, $nameFormatter);
                //($client,$nameFormatter);
                if ($client['last_transaction'] == '' || $client['last_transaction'] == '0000-00-00') {
                    array_push($first_Clients, $client);
                    $myKtoFirst->Add($date, $client['xt_memberfee'], $bankOwner, $client['xt_iban'], $client['xt_bic'],
                        NULL, NULL, $transition_Date . "/" . $client['id'], 'Mitgliedsbeitrag ' . $month_Date, 'FRST', "M" . $client['id'], date('Y-m-d', $client['dateAdded']));

                } else {
                    array_push($follow_Clients, $client);
                    $myKtoFollow->Add($dateFollow, $client['xt_memberfee'], $bankOwner, $client['xt_iban'], $client['xt_bic'],
                        NULL, NULL, $transition_Date . "/" . $client['id'], 'Mitgliedsbeitrag ' . $month_Date, 'RCUR', "M" . $client['id'], date('Y-m-d', $client['dateAdded']));
                    //
                }
            } else array_push($no_Sepa, $client);

        }
        // echo "</table>";
        $last_first_id = "";
        if (sizeof($first_Clients) > 0) {
            $xmlFirst  = $myKtoFirst->GetXML('CORE', 'php-xml 1.0', 'Parteibeitrag ' . $month_Date,
                $empfaenger_N_name, $empfaenger_F_name, $empfaenger_iban, $empfaenger_bic, $empfaenger_id);
            $file_name = 'sepa-First_' . $date . '.xml';
            file_put_contents($file_name, $xmlFirst);
            array_push($files, $file_name);
            $exec = "INSERT INTO transaction(tr_type,tr_date,tr_pre_date) VALUES('first', '" . $date . "', '" . $pre_date . "')";
            $SQL->execute($exec);
            $last_first_id = $SQL->lastInsertID();
            foreach ($first_Clients as $client) {
                $exec = "INSERT INTO members_transaction (id_member,id_transaction,tr_memberfee) VALUES ('" . $client['id'] . "','" . $last_first_id . "','" . $client['xt_memberfee'] . "')";
                $SQL->execute($exec);
            }


        }
        $last_follow_id = "";
        if (sizeof($follow_Clients) > 0) {
            $xmlFollow = $myKtoFollow->GetXML('CORE', 'php-xml 1.0', 'Parteibeitrag ' . $month_Date,
                $empfaenger_N_name, $empfaenger_F_name, $empfaenger_iban, $empfaenger_bic, $empfaenger_id);
            $file_name = 'sepa-Follow_' . $dateFollow . '.xml';
            file_put_contents($file_name, $xmlFollow);
            array_push($files, $file_name);
            $exec = "INSERT INTO transaction(tr_type,tr_date,tr_pre_date) VALUES('follow', '" . $dateFollow . "', '" . $pre_date . "')";
            $SQL->execute($exec);
            $last_follow_id = $SQL->lastInsertID();
            foreach ($follow_Clients as $client) {
                // $SQL->execute("UPDATE tl_member SET last_transaction = '" . $transition_Date . "' WHERE id = " . $client['id']);
                $exec = "INSERT INTO members_transaction(id_member,id_transaction,tr_memberfee) VALUES ('" . $client['id'] . "','" . $last_follow_id . "','" . $client['xt_memberfee'] . "')";
                $SQL->execute($exec);
            }
        }
        $lastFiles = $SQL->getFields('files', ['id', 'file', 'tr1', 'tr2'], 1, 'id', true);
        if (is_array($lastFiles)) {
            foreach ($lastFiles as $lastFile) {
                if ($lastFile['file'] == $file_Date . "_sepa.zip") {
                    $SQL->execute("DELETE FROM transaction WHERE id = " . $lastFile['tr1'] . " OR id= " . $lastFile['tr2']);
                    $SQL->execute("DELETE FROM members_transaction WHERE id_transaction = " . $lastFile['tr1'] . " OR id_transaction= " . $lastFile['tr2']);
                    $SQL->execute("DELETE FROM files WHERE id = '" . $lastFile['id'] . "'");
                }
            }
        }
        $str = "INSERT INTO files (file,pre_date,tr1,tr2) VALUES ('" . $file_Date . "_sepa.zip" . "','" . $pre_date . "','" . $last_first_id . "','" . $last_follow_id . "')";
        $SQL->execute($str);
        Zip::zip_and_download($file_Date . "_sepa.zip", $files);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

}

