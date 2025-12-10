<?= $this->extend('layout/main') ?> <!-- extend main layout -->

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color:red"><?= session()->getFlashdata('error') ?></p>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color:green"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>

<form method="post" action="/rss/fetch">
    <label>RSS Feed URL</label><br>
    <input type="text" name="rss_url" style="width:300px" required><br><br>

    <label>Sort Mode</label><br>
    <select name="sort_mode" required>
        <option value="asc">ASC (Oldest → Newest)</option>
        <option value="desc">DESC (Newest → Oldest)</option>
    </select><br><br>

    <button type="submit">Import Feed</button>
</form>

<?= $this->endSection() ?>