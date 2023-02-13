<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Office;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements
    ToModel,
    WithHeadingRow,
    SkipsOnError,
    WithValidation,
    SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    private $department;
    private $office;

    public function __construct()
    {
        $this->department = Department::select('id', 'name')->get();
        $this->office = Office::select('id', 'name')->get();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $department = $this->department->where('name', $row['department'])->first();
        $office = $this->office->where('name', $row['office'])->first();

        return new User([
            'name'          => $row['nama_lengkap'],
            'username'      => $row['nik'],
            'email'         => $row['email'],
            'password'      => Hash::make($row['nik']),
            'office_id'     => $office->id,
            'department_id' => $department->id,
            'role'          => 'User',
            'isaktif'       => 1
        ]);
    }

    public function rules(): array
    {
        return [
            '*.email' => ['email', 'unique:users,email'],
            '*.username' => ['nik', 'unique:users,username']
        ];
    }

    public function onFailure(Failure ...$failures)
    {
    }
}
