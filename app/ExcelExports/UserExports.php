<?php

namespace App\ExcelExports;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class UserExports implements FromCollection, Responsable, ShouldAutoSize, WithColumnFormatting
{
    use Exportable;

    protected $collection;

    private $fileName = "users_data.xlsx";

    function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function collection()
    {
        return $this->collection;
    }	

    public function columnFormats(): array
    {
        return [
            'C' => "0",
            'F' => "@",
            'J' => "0",
            'M' => "0",
            'O' => "0",
            'T' => "0.00"
        ];
    }
}
