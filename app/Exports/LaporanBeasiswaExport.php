<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanBeasiswaExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                'Nama Mahasiswa' => $item->nama_mahasiswa,
                'NPM' => $item->npm,
                'No HP' => $item->no_hp,
                'Angkatan' => $item->angkatan,
                'Beasiswa' => $item->beasiswa->nama_beasiswa ?? '-',
                'Jenis Beasiswa' => $item->beasiswa->jenis_beasiswa ?? '-',
                'Periode' => $item->periode->periode . ' - ' . $item->periode->tahun_mulai,
                'Penerimaan Beasiswa' => $item->penerimaan_beasiswa,
                'Selesai Beasiswa' => $item->selesai_beasiswa,
                'Status Validasi' => $item->status_validasi,
                'Tanggal Verifikasi' => $item->verified_at ?? '-',
                'Diverifikasi Oleh' => $item->verifier->name ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Mahasiswa',
            'NPM',
            'No HP',
            'Angkatan',
            'Beasiswa',
            'Jenis Beasiswa',
            'Periode',
            'Penerimaan Beasiswa',
            'Selesai Beasiswa',
            'Status Validasi',
            'Tanggal Verifikasi',
            'Diverifikasi Oleh',
        ];
    }
}
