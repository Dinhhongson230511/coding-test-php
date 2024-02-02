<div class="articles form content admin-article-view hidden-block">
    <fieldset>
        <legend><?= __('View Article') ?></legend>
        <?php
            echo $this->Form->control('title', ['disabled' => true]);
            echo $this->Form->control('body', ['disabled' => true]);
        ?>
        <div class="input datetime required">
            <label for="created-at">Created At</label>
            <input type="datetime-local" disabled name="created_at" required="required" data-validity-message="This field cannot be left empty" oninvalid="this.setCustomValidity(''); if (!this.value) this.setCustomValidity(this.dataset.validityMessage)" oninput="this.setCustomValidity('')" id="created-at" aria-required="true" step="1" value="">
        </div>
        <div class="input datetime required">
            <label for="updated-at">Updated At</label>
            <input type="datetime-local" disabled name="updated_at" required="required" data-validity-message="This field cannot be left empty" oninvalid="this.setCustomValidity(''); if (!this.value) this.setCustomValidity(this.dataset.validityMessage)" oninput="this.setCustomValidity('')" id="updated-at" aria-required="true" step="1" value="">
        </div>
    </fieldset>
</div>
