<?php

namespace App\ExcelExports;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionExports implements FromCollection, Responsable, ShouldAutoSize
{
    use Exportable;

    protected $collection;

    private $fileName = "commissions.xlsx";

    function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function collection()
    {
        return $this->collection;
    }	
}
