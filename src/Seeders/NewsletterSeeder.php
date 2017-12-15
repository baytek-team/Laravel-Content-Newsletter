<?php
namespace Baytek\Laravel\Content\Types\Newsletterletter\Seeders;

use Baytek\Laravel\Content\Seeder;

class NewsletterSeeder extends Seeder
{
    private $data = [
        [
            'key' => 'newsletter',
            'title' => 'Newsletter',
            'content' => \Baytek\Laravel\Content\Types\Newsletter\Models\Newsletter::class,
            'relations' => [
                ['parent-id', 'content-type']
            ]
        ],
        [
            'key' => 'newsletter-menu',
            'title' => 'Newsletter Navigation Menu',
            'content' => '',
            'relations' => [
                ['content-type', 'menu'],
                ['parent-id', 'admin-menu'],
            ]
        ],
        [
            'key' => 'newsletter-index',
            'title' => 'Newsletter',
            'content' => 'newsletter.index',
            'meta' => [
                'type' => 'route',
                'class' => 'item',
                'append' => '</span>',
                'prepend' => '<i class="mail left icon"></i><span class="collapseable-text">',
                'permission' => 'View Newsletter'
            ],
            'relations' => [
                ['content-type', 'menu-item'],
                ['parent-id', 'newsletter-menu'],
            ]
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedStructure($this->data);
    }
}
