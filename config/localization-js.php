<?php

return [

    /*
     * Set the names of files you want to add to generated javascript.
     * Otherwise all the files will be included.
     *
     * 'messages' => [
     *     'validation',
     *     'forum/thread',
     * ],
     */
    'messages' => [
        'auth',
        'pagination',
        'passwords',
        'validation',
        'nav',
        'table',
        'input',
        'company',
        'user',
        'purchase',
        'payment',
        'tree'
    ],

    /*
     * The default path to use for the generated javascript.
     */
    'path' => resource_path('assets/js/vue-translations.js'),
];
