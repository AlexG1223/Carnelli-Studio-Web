<?php
/**
 * Partial: Breadcrumbs semánticos y accesibles para páginas internas.
 */

use App\Core\View;

$crumbs = $seo->getBreadcrumbs();
if (empty($crumbs)) {
    return;
}
?>
<nav class="breadcrumbs" aria-label="Migas de pan">
    <div class="container">
        <ol class="breadcrumbs__list">
            <li class="breadcrumbs__item">
                <a href="<?= View::url('') ?>" class="breadcrumbs__link">Inicio</a>
                <span class="breadcrumbs__sep" aria-hidden="true">/</span>
            </li>
            <?php foreach ($crumbs as $idx => $crumb): ?>
                <?php $isLast = ($idx === count($crumbs) - 1); ?>
                <li class="breadcrumbs__item" <?= $isLast ? 'aria-current="page"' : '' ?>>
                    <?php if (!$isLast && !empty($crumb['url'])): ?>
                        <a href="<?= View::url($crumb['url']) ?>" class="breadcrumbs__link"><?= View::e($crumb['label']) ?></a>
                        <span class="breadcrumbs__sep" aria-hidden="true">/</span>
                    <?php else: ?>
                        <span class="breadcrumbs__current"><?= View::e($crumb['label']) ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</nav>

