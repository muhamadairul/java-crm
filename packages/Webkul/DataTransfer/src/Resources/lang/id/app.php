<?php

return [
    'importers' => [
        'persons' => [
            'title' => 'Orang',

            'validation' => [
                'errors' => [
                    'duplicate-email' => 'Email: \'%s\' ditemukan lebih dari sekali dalam berkas impor.',
                    'duplicate-phone' => 'Telepon: \'%s\' ditemukan lebih dari sekali dalam berkas impor.',
                    'email-not-found' => 'Email: \'%s\' tidak ditemukan di sistem.',
                ],
            ],
        ],

        'products' => [
            'title' => 'Produk',

            'validation' => [
                'errors' => [
                    'sku-not-found' => 'Produk dengan SKU yang ditentukan tidak ditemukan',
                ],
            ],
        ],

        'leads' => [
            'title' => 'Prospek',

            'validation' => [
                'errors' => [
                    'id-not-found' => 'ID: \'%s\' tidak ditemukan di sistem.',
                ],
            ],
        ],
    ],

    'validation' => [
        'errors' => [
            'column-empty-headers' => 'Kolom nomor "%s" memiliki header kosong.',
            'column-name-invalid'  => 'Nama kolom tidak valid: "%s".',
            'column-not-found'     => 'Kolom yang diperlukan tidak ditemukan: %s.',
            'column-numbers'       => 'Jumlah kolom tidak sesuai dengan jumlah baris pada header.',
            'invalid-attribute'    => 'Header mengandung atribut yang tidak valid: "%s".',
            'system'               => 'Terjadi kesalahan sistem yang tidak terduga.',
            'wrong-quotes'         => 'Tanda kutip kurung digunakan sebagai pengganti tanda kutip lurus.',
            'already-exists'       => ':attribute sudah ada.',
        ],
    ],
];
