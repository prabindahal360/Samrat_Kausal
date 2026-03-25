<?php

return [

    'title' => 'Bulk Email System',
    'title_prefix' => '',
    'title_postfix' => '',

    'use_ico_only' => false,
    'use_full_favicon' => false,

    'logo' => '<b>Bulk Email</b> System',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Bulk Email System',

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    'preloader' => [
        'enabled' => false,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => false,

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    'use_route_url' => true,

    'dashboard_url' => 'dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'forgot-password',
    'password_email_url' => 'forgot-password',
    'profile_url' => false,

    'menu' => [
        [
            'text' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'fas fa-fw fa-tachometer-alt',
        ],
        [
            'text' => 'Customers',
            'icon' => 'fas fa-fw fa-users',
            'submenu' => [
                [
                    'text' => 'All Customers',
                    'route' => 'customers.index',
                    'icon' => 'far fa-fw fa-circle',
                ],
                [
                    'text' => 'Add Customer',
                    'route' => 'customers.create',
                    'icon' => 'far fa-fw fa-circle',
                ],
                [
                    'text' => 'Import Customers',
                    'route' => 'customers.import.form',
                    'icon' => 'far fa-fw fa-circle',
                ],
                [
                    'text' => 'Import Dataset',
                    'route' => 'customers.import.form',
                    'icon' => 'far fa-fw fa-circle',
                ],
                [
                    'text' => 'Invoice Items',
                    'route' => 'invoice-items.index',
                    'icon' => 'far fa-fw fa-circle',
                ],
            ],
        ],

        [
    'text' => 'Products',
    'icon' => 'fas fa-fw fa-box',
    'submenu' => [
        [
            'text' => 'All Products',
            'route' => 'products.index',
            'icon' => 'far fa-fw fa-circle',
        ],
        [
            'text' => 'Add Product',
            'route' => 'products.create',
            'icon' => 'far fa-fw fa-circle',
        ],
    ],
],

        [
            'text' => 'Campaigns',
            'icon' => 'fas fa-fw fa-envelope',
            'submenu' => [
                [
                    'text' => 'All Campaigns',
                    'route' => 'campaigns.index',
                    'icon' => 'far fa-fw fa-circle',
                ],
                [
                    'text' => 'Create Campaign',
                    'route' => 'campaigns.create',
                    'icon' => 'far fa-fw fa-circle',
                ],
            ],
        ],
        [
            'header' => 'ADMIN',
            'can' => 'admin-only',
        ],
        [
            'text' => 'Manage Users',
            'route' => 'admin.users.index',
            'icon' => 'fas fa-fw fa-user-shield',
            'can' => 'admin-only',
        ],
    ],

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],
    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css',
                ],
            ],
        ],

        'DatatablesPlugins' => [
            'active' => false,
            'files' => [],
        ],

        'Select2' => [
            'active' => false,
            'files' => [],
        ],

        'Chartjs' => [
            'active' => false,
            'files' => [],
        ],

        'Sweetalert2' => [
            'active' => false,
            'files' => [],
        ],

        'Pace' => [
            'active' => false,
            'files' => [],
        ],
    ],

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    'livewire' => false,
];
