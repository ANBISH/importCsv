<?php

namespace App\Imports;

use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, ShouldQueue
{
    public $tries = 10;
    public $timeout = 3600;

    protected $rows = [];

    public function model(array $row)
    {
        if ($this->isInvalidRow($row)) {
            return null;
        }

        $this->rows[] = [
            'email'     => $row['email'],
            'firstname' => $row['firstname'],
            'lastname'  => $row['lastname'],
            'age'       => (int) $row['age'],
            'country'   => $row['country'],
            'city'      => $row['city'],
            'date'      => $row['date'],
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (count($this->rows) >= 100) {
            $this->insertRows();
        }

        return null;
    }

    private function insertRows()
    {
        try {
            DB::table('users')->insert($this->rows);
            Log::info('Inserted ' . count($this->rows) . ' rows into users table');
        } catch (\Exception $e) {
            Log::error('Error inserting rows: ' . $e->getMessage());
        }

        $this->rows = [];
    }

    public function chunkSize(): int
    {
        return 10000;
    }

    public function batchSize(): int
    {
        return 10000;
    }

    private function isValidEmail($email)
    {
        return !preg_match('/@(mail\.ru|ya\.ru)$/i', $email);
    }

    private function isValidCountry($country)
    {
        return in_array(strtolower($country), ['ua', 'uk', 'us']);
    }

    private function isInvalidRow($row)
    {
        $validator = Validator::make($row, [
            'email'     => 'required|email',
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'age'       => 'nullable|integer|min:0|max:120',
            'country'   => 'required|string|size:2',
            'city'      => 'nullable|string|max:255',
            'date'      => 'nullable|date',
        ]);

        return $validator->fails() || !$this->isValidEmail($row['email']) || !$this->isValidCountry($row['country']);
    }
}
