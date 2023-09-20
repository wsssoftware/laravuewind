<?php

return [
    'filepond' => [
        /**
         * The disk where to store the files. Use null to use the default disk.
         */
        'disk' => env('LVW_FILEPOND_DISK', null),
        'temporary_path' => env('LVW_FILEPOND_TEMP_PATH', 'filepond'),
    ],
];
