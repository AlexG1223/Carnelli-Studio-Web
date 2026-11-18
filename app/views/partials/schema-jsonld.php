<?php
/**
 * Partial: Ecosistema completo de datos estructurados JSON-LD (Schema.org) para Google y motores de IA (GEO).
 * Incluye Organization + ProfessionalService + Person (Alex Carnelli) + WebSite + BreadcrumbList + Schemas por página.
 */

use App\Core\View;

$siteUrl   = rtrim(BASE_URL, '/');
$instaUrl  = trim((string)($settings['instagram_url'] ?? ''));
$fbUrl     = trim((string)($settings['facebook_url'] ?? ''));
$liUrl     = trim((string)($settings['linkedin_url'] ?? ''));

// Regla: Las redes que estén vacías NO se incluyen en el sameAs del schema
$sameAs = array_values(array_filter([$instaUrl, $fbUrl, $liUrl], fn($u) => $u !== '' && !str_contains($u, '[COMPLETAR')));

$whatsappPhone = '+' . preg_replace('/[^0-9]/', '', (string)($settings['whatsapp_intl'] ?? '59892473724'));
$emailContacto = (string)($settings['email_contacto'] ?? 'carnellistudio2026@gmail.com');

$graph = [
    [
        '@type' => ['Organization', 'ProfessionalService'],
        '@id'   => $siteUrl . '/#organization',
        'name'  => 'Carnelli Studio',
        'alternateName' => 'Carnelli Studio Uruguay',
        'slogan' => 'Diseñamos el crecimiento digital de tu pyme',
        'url'   => $siteUrl . '/',
        'logo'  => [
            '@type'  => 'ImageObject',
            'url'    => View::asset('assets/brand/carnelli-imagotipo-color-transparente.png', false),
            'width'  => 1530,
            'height' => 395,
        ],
        'image' => View::asset('assets/brand/og-default.png', false),
        'description' => 'Agencia de transformación digital para pymes uruguayas: gestión de redes sociales, sitios web, tiendas online con pasarela de pago, sistemas web a medida y automatización con inteligencia artificial.',
        'telephone' => $whatsappPhone,
        'email' => $emailContacto,
        'priceRange' => '$$',
        'founder' => [
            '@id' => $siteUrl . '/#founder'
        ],
        'areaServed' => [
            [
                '@type' => 'City',
                'name'  => 'Ciudad de la Costa',
                'containedInPlace' => [
                    '@type' => 'AdministrativeArea',
                    'name'  => 'Canelones'
                ]
            ],
            [
                '@type' => 'Country',
                'name'  => 'Uruguay'
            ]
        ],
        'knowsAbout' => [
            'Marketing Digital para Pymes',
            'Gestión de Redes Sociales',
            'Diseño y Desarrollo Web en PHP y JavaScript',
            'Tiendas Online E-commerce con Mercado Pago',
            'Sistemas Web a Medida',
            'Automatización de Procesos e Inteligencia Artificial Aplicada',
            'SEO Técnico y GEO (Generative Engine Optimization)',
            'Google Maps y Perfil de Empresa en Google'
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => $whatsappPhone,
            'email' => $emailContacto,
            'contactType' => 'customer service',
            'areaServed' => 'UY',
            'availableLanguage' => ['es']
        ],
    ],
    [
        '@type' => 'Person',
        '@id'   => $siteUrl . '/#founder',
        'name'  => 'Alex Carnelli',
        'jobTitle' => 'Fundador y Desarrollador Full-Stack',
        'url'   => View::url('quien-soy'),
        'image' => View::asset('assets/brand/alex-carnelli.webp', false),
        'worksFor' => [
            '@id' => $siteUrl . '/#organization'
        ],
        'knowsAbout' => ['PHP', 'JavaScript', 'MySQL', 'Desarrollo Web', 'Sistemas a Medida', 'Marketing Digital', 'Inteligencia Artificial'],
    ],
    [
        '@type' => 'WebSite',
        '@id'   => $siteUrl . '/#website',
        'url'   => $siteUrl . '/',
        'name'  => 'Carnelli Studio',
        'inLanguage' => 'es-UY',
        'publisher' => [
            '@id' => $siteUrl . '/#organization'
        ],
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => View::url('servicios') . '?q={search_term_string}',
            'query-input' => 'required name=search_term_string'
        ]
    ]
];

if (!empty($sameAs)) {
    $graph[0]['sameAs'] = $sameAs;
}

$crumbs = $seo->getBreadcrumbs();
if (!empty($crumbs)) {
    $itemList = [
        [
            '@type'    => 'ListItem',
            'position' => 1,
            'name'     => 'Inicio',
            'item'     => View::url('')
        ]
    ];
    $pos = 2;
    foreach ($crumbs as $crumb) {
        $itemList[] = [
            '@type'    => 'ListItem',
            'position' => $pos++,
            'name'     => $crumb['label'],
            'item'     => View::url($crumb['url'] ?? '')
        ];
    }
    $graph[] = [
        '@type' => 'BreadcrumbList',
        'itemListElement' => $itemList
    ];
}

foreach ($seo->getSchemas() as $extraSchema) {
    $graph[] = $extraSchema;
}

$jsonLd = [
    '@context' => 'https://schema.org',
    '@graph'   => $graph
];
?>
<script type="application/ld+json">
<?= json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>

