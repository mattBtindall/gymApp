    </div>
<?php
    $currentMethod = getUrl()[2];
    if (file_exists(PUB_ROOT . '/js/' . $currentMethod . '.js')) {
        echo '<script src="' . URL_ROOT_BASE . '/js/' . $currentMethod . '.js"></script>';
    }
?>
<script src="<?= URL_ROOT_BASE ?>/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
