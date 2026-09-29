<?php

namespace App\Support;

class SpmbDokumen
{
    public static function program(string $jenjang): string
    {
        return $jenjang === 'SMP'
            ? 'Program Paket B PKBM / Madrasah Salafiyah Wustha (MSW/SMP) Ibnu Abbas'
            : 'Program Paket A PKBM / Madrasah Salafiyah Ula (MSU/SD) Ibnu Abbas';
    }

    public static function pernyataan(string $jenjang): array
    {
        $program = self::program($jenjang);

        return [
            'peraturan' => [
                'judul' => 'Sanggup Mematuhi Peraturan, Ketentuan & Kebijakan Pengurus',
                'paragraf' => [
                    "Selaku orang tua /wali calon peserta didik $program,",
                    "Dengan ini menyatakan bahwa saya sanggup mematuhi peraturan, ketentuan dan kebijakan yang ditetapkan oleh Pengurus $program. Apabila dilain waktu saya melanggar peraturan yang telah ditetapkan, maka saya bersedia menerima sanksi yang berlaku di $program.",
                    'Demikianlah surat pernyataan ini dibuat dengan sebenar-benarnya, dengan kesadaran penuh tanpa paksaan, dan untuk digunakan sebagaimana mestinya.',
                ],
            ],
            'biaya' => [
                'judul' => 'Kesediaan & Kesanggupan Membayar Biaya Pendidikan',
                'paragraf' => [
                    "Saya adalah Orang Tua /Wali dari calon peserta didik $program Tahun Pelajaran 2027/2028.",
                    'Menyatakan hal-hal sebagai berikut:',
                    "Jika anak saya diterima menjadi peserta didik baru di $program Tahun Pelajaran 2027/2028, saya akan bertanggung jawab atas semua pembiayaan selama anak kami tersebut bersekolah di $program dan melakukan pembayaran sesuai dengan tenggang waktu yang ditentukan.",
                    "Pada saat daftar ulang setelah anak saya diterima sebagai peserta didik di $program saya bersedia membayar Biaya Sarana & Prasarana sebesar 50 % dari total keseluruhan serta SPP bulan Juli sesuai dengan pilihan yang tertera di formulir pendaftaran.",
                    'Seluruh biaya yang sudah terbayar tidak akan diambil kembali dengan alasan apapun',
                    'Pernyataan ini dibuat atas kesadaran sendiri tanpa ada tekanan dari pihak manapun dan akan saya penuhi sesuai dengan yang saya nyatakan.',
                    'Demikian pernyataan ini saya buat dengan sebenar-benarnya untuk dipergunakan sebagaimana mestinya.',
                ],
            ],
        ];
    }

    public static function pertanyaan(string $jenjang): array
    {
        if ($jenjang === 'SMP') {
            return [
                'tahfidz' => 'Bagaimana pandangan Bapak/Ibu terhadap program menghafal Al-Qur’an?',
                'diniyah' => 'Bagaimana pandangan Bapak/ Ibu terhadap program sekolah berbasis diniyah yang nantinya ananda akan mendapatkan Ijazah Pondok dan Paket B saja?',
                'manhaj' => 'Apa yang Bapak/Ibu ketahui tentang sekolah yang berbasis manhaj Ahlussunnah? Dan apa itu Ahlussunnah atau yang biasa dikenal dengan salafusholeh',
                'hukuman' => 'Bagaimana menurut Bapak/ Ibu tentang hukuman yang diberikan kepada ananda ketika melanggar!',
                'kajian' => 'Komitmen untuk menghadiri Kajian Orangtua Santri (KOS) setiap 3 bulan sekali',
                'biaya' => 'Kesanggupan membayar SPP',
                'harapan' => 'Apa harapan Bapak/Ibu terhadap Madrasah Salafiyah Wustha Ibnu Abbas!',
                'game' => 'Apakah ananda kecanduan game online ? Apakah masih bisa diarahkan ?',
                'penyakit' => 'Apakah ananda memiliki penyakit yang serius, menular dll?',
            ];
        }

        return [
            'diniyah' => 'Bagaimana pandangan Bapak/ Ibu terhadap program sekolah berbasis diniyah yang nantinya ananda akan mendapatkan Ijazah Pondok dan Paket A saja ?',
            'manhaj' => 'Apa yang Bapak/Ibu ketahui tentang sekolah yang berbasis manhaj Ahlussunnah? Dan apa itu Ahlussunnah atau yang biasa dikenal dengan salafusholeh ?',
            'hukuman' => 'Bagaimana menurut Bapak/ Ibu tentang hukuman yang diberikan kepada ananda ketika melanggar peraturan di sekolah?',
            'kajian' => 'Bagaimana komitmen Bapak/Ibu untuk menghadiri Kajian Orang Tua Santri (3 / 1bulan sekali) ?',
            'biaya' => 'Apakah Bapak/Ibu sanggup membayar biaya pendidikan ananda (SPP, Sarpras dll) ?',
            'game' => 'Apakah ananda di rumah kecanduan game? Apakah bisa diarahkan disiplin waktu?',
            'penyakit' => 'Apakah ananda memiliki penyakit bawaan yang serius? Jika ya, penyakit apa?',
            'kebutuhan' => 'Apakah ananda memiliki kebutuhan khusus? (Speech Delay, arogan, hyper aktif atau yang lainnya)',
            'harapan' => 'Apa yang diharapkan orang tua menyekolahkan anak di sekolah ini?',
            'pengasuh' => 'Siapa pengasuh utama anak di rumah? Apakah ayah dan ibu bekerja?',
            'marah' => 'Bagaimana perilaku anak saat sedang marah? Misalnya apakah anak hanya menangis, suka berguling-guling, atau bahkan melempar benda?',
        ];
    }
}
