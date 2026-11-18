<?php
/**
 * Seo: Gestión centralizada de metadatos, Canonical, Open Graph, Twitter Cards y JSON-LD (SEO + GEO).
 */

declare(strict_types=1);

namespace App\Core;

use App\Models\Setting;

class Seo
{
    private string $title = '';
    private string $description = '';
    private string $canonical = '';
    private string $ogImage = '';
    private string $ogType = 'website';
    private bool $noindex = false;
    private array $breadcrumbs = [];
    private array $schemas = [];

    public function __construct()
    {
        $settings = Setting::allKeyValue();
        $siteName = $settings['nombre_sitio'] ?? 'Carnelli Studio';
        $tagline  = $settings['tagline'] ?? 'Diseñamos el crecimiento digital de tu pyme';
        $this->title = $siteName . ' — ' . $tagline;
        $this->description = $settings['meta_description_default']
            ?? 'Agencia de transformación digital para pymes en Uruguay. Gestión de redes sociales, sitios web, tiendas online con Mercado Pago, sistemas a medida e IA.';
        $this->ogImage = View::asset('assets/brand/og-default.png');
    }

    public function setTitle(string $title, bool $appendBrand = true): self
    {
        $this->title = $appendBrand && !str_contains($title, 'Carnelli Studio')
            ? $title . ' | Carnelli Studio'
            : $title;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = trim($description);
        return $this;
    }

    public function setCanonical(string $path): self
    {
        $this->canonical = View::url($path);
        return $this;
    }

    public function setOgImage(string $assetOrUrl): self
    {
        $this->ogImage = str_starts_with($assetOrUrl, 'http')
            ? $assetOrUrl
            : View::asset($assetOrUrl);
        return $this;
    }

    public function setOgType(string $type): self
    {
        $this->ogType = $type;
        return $this;
    }

    public function setNoIndex(bool $noindex = true): self
    {
        $this->noindex = $noindex;
        return $this;
    }

    public function setBreadcrumbs(array $crumbs): self
    {
        $this->breadcrumbs = $crumbs;
        return $this;
    }

    public function getBreadcrumbs(): array
    {
        return $this->breadcrumbs;
    }

    public function addSchema(array $schema): self
    {
        $this->schemas[] = $schema;
        return $this;
    }

    public function getSchemas(): array
    {
        return $this->schemas;
    }

    public function renderMetaTags(): string
    {
        $title = View::e($this->title);
        $desc  = View::e($this->description);
        $canon = View::e($this->canonical ?: View::currentUrl());
        $ogImg = View::e($this->ogImage);
        $ogType = View::e($this->ogType);
        $robots = $this->noindex
            ? 'noindex, nofollow'
            : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';

        return <<<HTML
    <title>{$title}</title>
    <meta name="description" content="{$desc}">
    <meta name="robots" content="{$robots}">
    <link rel="canonical" href="{$canon}">
    <link rel="alternate" hreflang="es-UY" href="{$canon}">
    <link rel="alternate" hreflang="es" href="{$canon}">
    <link rel="alternate" hreflang="x-default" href="{$canon}">

    <!-- Open Graph -->
    <meta property="og:locale" content="es_UY">
    <meta property="og:type" content="{$ogType}">
    <meta property="og:site_name" content="Carnelli Studio">
    <meta property="og:title" content="{$title}">
    <meta property="og:description" content="{$desc}">
    <meta property="og:url" content="{$canon}">
    <meta property="og:image" content="{$ogImg}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{$title}">
    <meta name="twitter:description" content="{$desc}">
    <meta name="twitter:image" content="{$ogImg}">
HTML;
    }
}

