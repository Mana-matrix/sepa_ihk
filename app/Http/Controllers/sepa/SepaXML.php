<?php

namespace App\Http\Controllers\sepa;

use App\Http\Controllers\sepa\KtoSepaSimple;
use App\tl_member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ClientController;
use ZipArchive;
use App\Http\Controllers\Client_TableController;
use App\transactions;
use Illuminate\Support\Facades\DB;

class SepaXML extends Controller
{
    public function printSepa(Request $request)
    {
        $this -> DB_clean();
        $myKto = [];
        if ($request -> input('follow_sepa')) $myKto['follow'] = $this -> generateSEPA('follow', $request -> input('first_sepa'));
        echo "<br>----------<br>";
        if ($request -> input('first_sepa')) $myKto['first'] = $this -> generateSEPA('first', $request -> input('first_sepa'));
        dump($myKto);
        $zip = $this -> zipIt($myKto);
        return redirect() -> route('sepa');
    }

    private function zipIt(array $sepa_files)
    {
        $zip = new ZipArchive();
        $DelFilePath = storage_path('app\sepa\\') . date('m-Y') . '_sepa.zip';
        if ($zip -> open($DelFilePath, ZIPARCHIVE::CREATE) != TRUE) {
            die ("Could not open archive");
        }
        $zip -> addFile("file_path", "file_name");
        foreach ($sepa_files as $key => $kto) {
            $file = storage_path('app\sepa\\') . $kto;
            $zip -> addFromString(basename($file), file_get_contents($file));
        }
        $zip -> close();
    }

    private function generateSEPA(string $type, $date)
    {
        if ($type != 'follow')
            $clients = ClientController ::getClients('new', true);
        else $clients = ClientController ::getClients('old', true);
        $empfaenger_F_name = env('SEPA_F_NAME');
        $empfaenger_N_name = env('SEPA_N_NAME');
        $empfaenger_iban = env('SEPA_IBAN');
        $empfaenger_bic = env('SEPA_BIC');
        $empfaenger_id = env('SEPA_ID');// - gläubiger ID
        $month_Date = date('M Y');
        /* $file_Date = date('Y_m');
         $pre_date = date('Y-m-d');
         $date = date('Y-m-d', $date);*/
        $myKto = new KtoSepaSimple();
        $formatter = new SpecialChars_formatter(); // formating names to english e.g. Ä - Ae, é - e
        $files = array();
        if (count($clients))
            foreach ($clients as $client) {
                $transition_Date = date('Y-m-d\TH:i:s');
                $getOwner = function ($client) use ($formatter) {
                    if ($client -> xt_bank_owner != "" && $client -> xt_bank_owner != "-") {
                        $owner = utf8_encode($client -> xt_bank_owner);
                        $owner = trim($formatter -> format_to_english($owner), " ");
                        $pos = strpos($owner, " ");
                        if ($pos)
                            return substr($owner, $pos + 1, strlen($owner)) . ", " . substr($owner, 0, $pos);
                        else
                            return $owner;
                    } else {
                        return $formatter -> format_to_english(utf8_encode($client -> lastname . ", " . $client -> firstname));
                    }
                };
                $bankOwner = $getOwner($client);
                //  echo "$bankOwner<br>";
                $myKto -> Add($date, $client -> xt_memberfee, $bankOwner, $client -> xt_iban, $client -> xt_bic,
                    NULL, NULL, $transition_Date . "/" . $client -> id, 'Mitgliedsbeitrag ' . $month_Date, 'FRST', "M" . $client -> id, date('Y-m-d', $client -> dateAdded));
            }
        $myKto = $myKto -> GetXML('CORE', 'php-xml 1.0', 'Parteibeitrag ' . $month_Date,
            $empfaenger_N_name, $empfaenger_F_name, $empfaenger_iban, $empfaenger_bic, $empfaenger_id);
        file_put_contents(storage_path('app\sepa\\') . date('m-Y') . '_' . $type . '_sepa.xml', $myKto);
        $this -> setDB_entries($clients, $date, $type);
        return date('m-Y') . '_' . $type . '_sepa.xml';
    }

    private function DB_clean()
    {
        $ids = DB ::select('SELECT id FROM transactions where confirmed!=1;');
        $delete = "DELETE FROM tl_member_transactions";
        foreach ($ids as $key => $id)
            $delete .= $key == 0 ? " WHERE transactions_id=$id->id" : " or transactions_id=$id->id";
        DB ::select("$delete;");
        $delete = "DELETE FROM transactions";
        foreach ($ids as $key => $id)
            $delete .= $key == 0 ? " WHERE id=$id->id" : " or id=$id->id";
        DB ::select("$delete;");
    }

    private function setDB_entries($clients, $date, $type)
    {
        $transaction = transactions ::firstOrCreate(['tr_type' => $type, 'tr_date' => $date]);
        foreach ($clients as $client)
            tl_member ::whereId($client['id']) -> first() -> transactions() -> attach([$transaction -> id => ['iban' => $client['xt_iban'], 'fee' => $client['xt_memberfee']]]);
    }
}
