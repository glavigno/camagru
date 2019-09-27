<div class="grid">
    <?php foreach($pictures as $pic): ?>
    <div class="card">
        <img src="<?= $pic['source'] ?>">
        <div class="info">
            <div class="info_pic">
                <a href="<?= URL ?>?url=post&id=<?= strval($pic['id']) ?>">See details</a>
                <i class="fas fa-heart"></i><?= strval($pic['likes']) ?>
                <i class="fas fa-comment"></i><?= strval($pic['comments']) ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<div class="pages">
    <?php for ($page = 1; $page <= $nb_pages; $page++): ?>
        <?php if (!isset($_GET['page']) && $page == 1): ?>
            <a id="active_page" href="<?= URL ?>?url=gallery&page=<?= $page ?>"><?= $page ?></a>
        <?php elseif (isset($_GET['page']) && $page == $_GET['page']): ?>
            <a id="active_page" href="<?= URL ?>?url=gallery&page=<?= $page ?>"><?= $page ?></a>
        <?php else: ?>
           <a href="<?= URL ?>?url=gallery&page=<?= $page ?>"><?= $page ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</div>