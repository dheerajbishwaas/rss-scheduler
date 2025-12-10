<?= $this->extend('layout/main') ?> <!-- extend main layout -->

<?= $this->section('content') ?>

<h2>RSS Posts</h2>

<table>
    <tr>
        <th>Image</th>
        <th>Title</th>
        <th>Char Count</th>
        <th>Priority</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>

    <tbody id="posts-table">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $p): ?>
                <tr data-id="<?= $p['id'] ?>">
                    <td><img src="<?= $p['image_url'] ?>" /></td>
                    <td><?= esc($p['title']) ?></td>
                    <td><?= $p['char_count'] ?></td>
                    <td class="priority"><?= $p['priority'] ?></td>
                    <td><?= date('d M Y H:i', strtotime($p['pub_date'])) ?></td>
                    <td>
                        <a href="javascript:void(0)" onclick="openAssignModal('<?= $p['id'] ?>', '<?= addslashes($p['title']) ?>')">Edit </a>
                        <a href="javascript:void(0)" class="delete-post" data-id="<?= $p['id'] ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align:center; padding:20px;">
                    No posts available.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>


</table>

<div class="pagination-wrapper">
    <?= $pager->links('default', 'numeric') ?>
</div>

<!-- Modal -->
<div id="assignModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
background:rgba(0,0,0,0.5); z-index:9999; padding-top:5%;">
    <div style="background:white; margin:auto; padding:20px; width:400px; border-radius:5px; position:relative;">
        <h3 id="modalTitle"></h3>
        <form id="assignForm">
            <div id="platformCheckboxes"></div>
            <br>
            <button type="submit">Save</button>
            <button type="button" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>
<script>
    var currentPage = <?= $currentPage ?>;
    var perPage = <?= $perPage ?>;
    var startOffset = (currentPage - 1) * perPage;
</script>




<?= $this->endSection() ?>