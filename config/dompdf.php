<?php

return [

    'show_warnings' => false,

    'public_path' => null,

    'convert_entities' => true,

    'options' => [
        'font_dir' => storage_path('fonts/'),
        'font_cache' => storage_path('fonts/'),
        'temp_dir' => sys_get_temp_dir(),
        'chroot' => realpath(base_path()),
        'allowed_protocols' => [
            'data://' => ['rules' => []],
            'file://' => ['rules' => []],
            'http://' => ['rules' => []],
            'https://' => ['rules' => []],
        ],
        'artifactPathValidation' => null,
        'log_output_file' => null,
        'enable_font_subsetting' => true,
        'pdf_backend' => 'CPDF',
        'default_media_type' => 'screen',
        'default_paper_size' => 'a4',
        'default_paper_orientation' => 'portrait',
        'default_font' => 'times', // mặc định Times New Roman
        'dpi' => 96,
        'enable_php' => false,
        'enable_javascript' => true,
        'enable_remote' => false,
        'allowed_remote_hosts' => null,
        'font_height_ratio' => 1.1,
        'enable_html5_parser' => true,

        // ✅ Thêm font custom
        'font_family' => [
            'arial' => [
                'normal' => storage_path('fonts/arial/arial.ttf'),
                'bold' => storage_path('fonts/arial/arialbd.ttf'),
                'italic' => storage_path('fonts/arial/ariali.ttf'),
                'bold_italic' => storage_path('fonts/arial/arialbi.ttf'),
            ],
            'timesnewroman' => [
                'normal' => storage_path('fonts/timesnewroman/times.ttf'),
                'bold' => storage_path('fonts/timesnewroman/timesbd.ttf'),
                'italic' => storage_path('fonts/timesnewroman/timesi.ttf'),
                'bold_italic' => storage_path('fonts/timesnewroman/timesbi.ttf'),
            ],
            'segoe-ui-symbol' => [
                'normal' => storage_path('fonts/segoe-ui-symbol/segoe-ui-symbol.ttf'),
                'bold' => storage_path('fonts/segoe-ui-symbol/segoe-ui-symbol.ttf'),
                'italic' => storage_path('fonts/segoe-ui-symbol/segoe-ui-symbol.ttf'),
                'bold_italic' => storage_path('fonts/segoe-ui-symbol/segoe-ui-symbol.ttf'),
            ],
        ],
    ],

];
