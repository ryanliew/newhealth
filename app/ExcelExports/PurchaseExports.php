<?php

namespace App\ExcelExports;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PurchaseExports implements FromCollection, Responsable, ShouldAutoSize
{
    use Exportable;

    protected $collection;

    private $fileName = "purchases.xlsx";

    function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function collection()
    {
        return $this->collection;
    }	
}
