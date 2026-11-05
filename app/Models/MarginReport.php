<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarginReport extends Model
{
    use HasFactory;


    public function registerReport($type, $amount, $contactId){
        $report = new MarginReport();
        $report->credit = $type == 1 ? $amount : 0;
        $report->debit = $type == 2 ? $amount : 0;
        $report->trans_type = $type;
        $report->month = date('m');
        $report->year = date('Y');
        $report->month_name = date('M');
        $report->contact_id = $contactId;
        $report->save();
        return $report;
    }
}
