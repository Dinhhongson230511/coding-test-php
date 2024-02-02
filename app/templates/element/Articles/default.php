<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <a href="/" class="side-nav-item article-manage">Home page</a>
            <a href="javascript:void(0)" class="side-nav-item article-manage">List Articles</a>
            <a href="javascript:void(0)" class="side-nav-item add-article-management">Add Article</a>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <?= $this->element('Articles/index') ?>
        <?= $this->element('Articles/add') ?>
        <?= $this->element('Articles/edit') ?>
        <?= $this->element('Articles/view') ?>
    </div>
</div>
