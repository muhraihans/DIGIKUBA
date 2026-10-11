<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
	public function run(): void
	{
		$jenisSurat = [
			[
				'kode' => 'SKTM',
				'nama' => 'Surat Keterangan Tidak Mampu',
				'template' => 'sktm',
				'deskripsi' => 'Surat keterangan tidak mampu untuk keperluan administrasi.',
				'status' => true,
			],
			[
				'kode' => 'SPPT-PBB',
				'nama' => 'Surat Keterangan PBB',
				'template' => 'sppt-pbb',
				'deskripsi' => 'Surat pengantar penerbitan SPPT-PBB.',
				'status' => true,
			],
			[
				'kode' => 'DOMISILI',
				'nama' => 'Surat Keterangan Domisili',
				'template' => 'domisili',
				'deskripsi' => 'Surat keterangan domisili warga Kelurahan Kutabaru.',
				'status' => true,
			],
		];

		foreach ($jenisSurat as $data) {
			JenisSurat::updateOrCreate(
				['kode' => $data['kode']],
				$data
			);
		}
	}
}
