<?php $pager->setSurroundCount(2) ?>
<nav class="mt-2" aria-label="<?= esc(lang('Pager.pageNavigation'), 'attr') ?>">
    <ul class="pagination">
        <?php if ($pager->hasPreviousPage()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= esc($pager->getFirst(), 'attr') ?>" aria-label="<?= esc(lang('Pager.first'), 'attr') ?>">
                    <span aria-hidden="true"><?= esc(lang('Pager.first')) ?></span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="<?= esc($pager->getPreviousPage(), 'attr') ?>" aria-label="<?= esc(lang('Pager.previous'), 'attr') ?>">
                    <span aria-hidden="true"><?= esc(lang('Pager.previous')) ?></span>
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li <?= $link['active'] ? 'class="active page-item"' : '' ?>>
                <a class="page-link" href="<?= esc($link['uri'], 'attr') ?>">
                    <?= esc($link['title']) ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNextPage()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= esc($pager->getNextPage(), 'attr') ?>" aria-label="<?= esc(lang('Pager.next'), 'attr') ?>">
                    <span aria-hidden="true"><?= esc(lang('Pager.next')) ?></span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="<?= esc($pager->getLast(), 'attr') ?>" aria-label="<?= esc(lang('Pager.last'), 'attr') ?>">
                    <span aria-hidden="true"><?= esc(lang('Pager.last')) ?></span>
                </a>
            </li>
        <?php endif ?>
    </ul>
</nav>