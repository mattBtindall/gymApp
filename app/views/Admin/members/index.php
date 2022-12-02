<?php require APP_ROOT . '/views/inc/header.php'; ?>
    <div class="container">
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Membership</th>
                <th>Expiry Date</th>
            </tr>
            <?php foreach($data as $row): ?>
                <tr>
                    <?php foreach($row as $key => $rowData): ?>
                        <td>
                            <?php
                                if ($key === 'name') {
                                    echo '<div class="img-container"><img src="'. $row['img_url'] .'"></div>';
                                }

                                if ($key !== 'img_url') {
                                    echo $rowData;
                                }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>
