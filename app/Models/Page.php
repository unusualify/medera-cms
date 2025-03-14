<?php

namespace App\Models;

use A17\Twill\Models\Model;
use App\Repositories\PageRepository;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasFiles;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use App\Models\Behaviours\HasLocalizedRoute;
use A17\Twill\Models\Behaviors\HasTranslation;
use App\Models\Behaviours\HasMetadata;
use App\Models\Behaviours\HasTemplate;

class Page extends Model implements Sortable
{
    use HasBlocks, HasTranslation, HasSlug, HasMedias, HasFiles, HasRevisions, HasPosition, HasMetadata, HasLocalizedRoute, HasTemplate;
    public \Illuminate\Contracts\Foundation\Application|array|\Illuminate\Config\Repository|\Illuminate\Foundation\Application $metadataFallbacks = [];

    protected $fillable = [
        'published',
        'title',
        'description',
        'position',
        'schema',
        'template',
    ];

    public $translatedAttributes = [
        'title',
        'description',
        'schema',
    ];

    public $slugAttributes = [
        'title',
    ];

    public $mediasParams = [
        'library-image' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 2880 / 800,
                ],
            ],
            'thumbnail' => [
                [
                    'name' => 'default',
                    'ratio' => 730 / 440,
                ],
            ]
        ],
    ];


    public const DEFAULT_TEMPLATE = 'homepage';
    //TODO: homepage app-doublecolumnconten ten sonra Accordion with Dynamic Images gelecek
    //TODO: homepage 1 ve 2. 'app-inlinebanner' dan emin degilim o yapilmamis olabilir
    //TODO: homepage en sona library component gelece
    //TODO: faq en sona library component gelece
    public const AVAILABLE_TEMPLATES = [
        [
            'value' => 'homepage',
            'label' => 'Homepage',
            'block_selection' => ['app-heroslider', 'app-doublecolumncontent', 'app-accordionwithdynamicimages', 'app-videosection ', 'app-inlinebanner', 'app-fullscreenbanner', 'app-featureheadlineswithsphericalimages', 'app-inlinebanner', 'app-faqlistings', 'app-featuredblogs'],
        ],
        [
            'value' => 'faq',
            'label' => 'FAQ',
            'block_selection' => ['app-banner', 'app-faqlistings', 'app-featuredblogs'],
        ],

        [
            'value' => 'textonly',
            'label' => 'Text Only',
            'block_selection' => [],
        ],

        [
            'value' => 'empty',
            'label' => 'Empty',
            'block_selection' => [],
        ],
    ];


    public function __construct()
    {
        parent::__construct();
        $this->slugKey = 'page';
        $this->routeName = 'page';
    }

    public function resolveRouteBinding($slug, $field = null)
    {
        $page = app(PageRepository::class)->forSlug($slug);

        abort_if(! $page, 404);

        return $page;
    }

    // #region routekey
    public function getLocalizedRouteKey($locale)
    {
        return $this->getSlug($locale);
    }

    public static function getAvailableTemplates(): array
    {
        return static::AVAILABLE_TEMPLATES;
    }
}
