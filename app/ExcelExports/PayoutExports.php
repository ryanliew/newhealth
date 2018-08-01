<?php

namespace App\ExcelExports;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class PayoutExports implements FromCollection, Responsable, ShouldAutoSize, WithColumnFormatting
{
    use Exportable;

    protected $collection;

    private $fileName = "payouts.xlsx";

    function __construct($collection, $month)
    {
        $this->fileName = "payouts_" . $month . ".xlsx";
        $this->collection = $collection;
    }

    public function collection()
    {
        return $this->collection;
    }	

    public function columnFormats(): array
    {
        return [
            'E' => "0",
            'F' => "0.00",
            'G' => "0.00"
        ];
    }
}
