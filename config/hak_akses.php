<?php

return [
    'manager' => [
        'lihat'  => ['dir_utama', 'dir_bagian', 'manager', 'ass_manager', 'staff', 'master_data'],
        'kelola' => ['dir_utama', 'dir_bagian', 'manager', 'master_data'],
    ],
    'asmen' => [
        'lihat'  => ['ass_manager', 'staff'],
        'kelola' => ['ass_manager'],
    ],
    'staff' => [
        'lihat'  => ['staff'],
        'kelola' => ['staff'],
    ],
];