<?php

namespace App\Imports;

use App\Models\SinhVien;
use Maatwebsite\Excel\Concerns\ToModel;

class SinhVienImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SinhVien([
            //
        ]);
    }
}
