<?= $this->extend('layout/main') ?> <!-- extend main layout -->

<?= $this->section('content') ?>

<form method="get">
    <select name="platform" onchange="this.form.submit()">
        <option value="">All Platforms</option> <!-- New option -->
        <?php foreach($platforms as $p): ?>
            <option value="<?= $p['id'] ?>" <?= $selectedPlatform==$p['id']?'selected':'' ?>>
                <?= ucfirst($p['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<?php if($posts): ?>
<table border="1" cellpadding="10">
    <tr>
        <th>Image</th>
        <th>Title</th>
        <th>Char Count</th>
        <th>Priority</th>
        <th>Date</th>
        <th>Assigned Platforms</th>
    </tr>
    <?php foreach($posts as $p): ?>
    <tr>
        <td><img src="<?= $p['image_url'] ?>" width="80"/></td>
        <td><?= esc($p['title']) ?></td>
        <td><?= $p['char_count'] ?></td>
        <td><?= $p['priority'] ?></td>
        <td><?= date('d M Y H:i', strtotime($p['pub_date'])) ?></td>
        <td>
            <?php
            if(isset($assignedPlatforms[$p['id']])) {
                foreach($assignedPlatforms[$p['id']] as $platformName) {
                    echo "<span style='background:#eee; padding:3px 7px; margin:2px; display:inline-block; border-radius:4px;'>"
                        . ucfirst($platformName) . "</span>";
                }
            }
    ?>
</td>
    </tr>
    <?php endforeach; ?>
</table>

<div>
    <?= $pager ? $pager->links('default', 'numeric') : '' ?>
</div>
<?php else: ?>
    <?php if($selectedPlatform): ?>
        <p>No posts assigned to this platform.</p>
    <?php else: ?>
        <p>Select a platform to see assigned posts.</p>
    <?php endif; ?>
<?php endif; ?>


<?= $this->endSection() ?>