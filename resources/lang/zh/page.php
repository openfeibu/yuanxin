<?php

return [
    'name'        => 'Page',
    'names'       => 'Pages',
    'options'     => [
        'view' => ['default' => 'Default', 'left' => 'Left Menu', 'right' => 'Right Menu'],
        'compile' => ['0' => 'No', '1' => 'Yes'],
        'status' => ['0' => 'Hide', '1' => 'Show'],
        'category' => ['default' => 'Default'],
    ],
    'label'       => [
        'name'             => 'Name',
        'title'            => '标题',
        'heading'          => 'Heading',
        'sub_heading'      => 'Sub heading',
        'abstract'         => 'Abstract',
        'content'          => '内容',
        'meta_title'       => 'Meta title',
        'meta_keyword'     => 'Meta keyword',
        'meta_description' => 'Meta description',
        'image'            => '封面',
        'images'           => 'Images',
        'compile'          => 'Compile',
        'view'             => 'View',
        'order'            => 'Order',
        'status'           => '显示/隐藏',
        'keyword'          => 'Keyword',
        'description'      => '描述',
        'slug'             => '标识',
        'url'              => 'Url',
        'created_at'       => 'Created at',
        'updated_at'       => 'Updated at',
        'category_id'      => 'Category',
        'home_recommend'   => '首页推荐',
        'hot_recommend'    => '头条推荐',
    ],
    'placeholder' => [
        'name'             => 'Please enter name',
        'title'            => 'Please enter title',
        'description'      => 'Please enter description',
        'heading'          => 'Please enter heading',
        'sub_heading'      => 'Please enter sub heading',
        'abstract'         => 'Please enter abstract / summary text for the page',
        'content'          => 'Please enter content',
        'meta_title'       => 'Please enter meta title',
        'meta_keyword'     => 'Please enter meta keyword',
        'meta_description' => 'Please enter meta description',
        'banner'           => 'Please enter banner',
        'images'           => 'Please enter images',
        'compile'          => 'Please select compile',
        'view'             => 'Please select view',
        'order'            => 'Please enter order',
        'status'           => 'Please select status',
        'keyword'          => 'Please enter keyword',
        'description'      => 'Please enter description',
        'slug'             => 'Please enter slug',
        'category_id'      => 'Please enter category',
    ],

    /**
     * Columns array for show hide checkbox.
     */
    'cloumns'     => [
        'name'    => ['name' => 'Name', 'data-column' => 1, 'checked' => 'checked', 'disabled' => 'disabled'],
        'title'   => ['name' => 'Title', 'data-column' => 2, 'checked' => 'checked'],
        'url'     => ['name' => 'URL', 'data-column' => 3, 'checked' => 'checked', 'disabled' => 'disabled'],
        'heading' => ['name' => 'Heading', 'data-column' => 4, 'checked' => 'checked'],
        'order'   => ['name' => 'Order', 'data-column' => 5],
    ],

    'message'     => [
        'nopage' => 'Page not found.',
    ],
    'tab'         => [
        'page'    => 'Page',
        'setting' => 'Setting',
        'meta'    => 'Meta',
        'image'   => 'Image',
    ],
    'text'        => [
        'preview' => 'Click on the below list for preview',
    ],

    'chairman' => [
        'name' => '董事长致辞'
    ],
];
