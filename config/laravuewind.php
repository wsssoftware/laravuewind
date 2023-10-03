<?php

return [
    'filepond' => [
        /**
         * The disk where to store the files. Use null to use the default disk.
         */
        'disk' => env('LVW_FILEPOND_DISK'),

        'gc' => [
            /**
             * The garbage collector run probability. Use 0 to disable the garbage collector and 100 to run it on every request.
             */
            'run_probability' => env('LVW_FILEPOND_GC_RUN_PROBABILITY', 15),

            /**
             * Amount of interactions (uploads deleted) before stop the garbage collector.
             */
            'interactions_limit' => env('LVW_FILEPOND_GC_INTERACTIONS_LIMIT', 5),

            /**
             * Validity of the upload in seconds. Before this time, garbage collector will delete the file.
             */
            'upload_validity' => env('LVW_FILEPOND_UPLOAD_VALIDITY', 3600),
        ],

        /**
         * The maximum file size in bytes. Use null for use the ini amount.
         */
        'memory_limit' => env('LVW_FILEPOND_MEMORY_LIMIT'),

        /**
         * Temporary path where to store the files.
         */
        'temporary_path' => env('LVW_FILEPOND_TEMP_PATH', 'filepond'),
    ],

    'storage_cleaner' => [
        /**
         * The path where to store the trashed files in disk.
         */
        'trash_bin_path' => env('LVW_STORAGE_CLEANER_RECYCLE_BIN_PATH', 'RECYCLE_BIN'),

        /**
         * The time to live for the trashed files in seconds. Before this time, garbage collector will delete the file.
         */
        'trashed_ttl' => env('LVW_STORAGE_CLEANER_TRAHSED_TTL', 60 * 60 * 24 * 15),
    ],
];
