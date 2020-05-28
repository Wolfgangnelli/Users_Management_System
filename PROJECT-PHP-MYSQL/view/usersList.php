<?php
$orderDirClass = $orderDir;

$orderDir = $orderDir === 'ASC' ? 'DESC' : 'ASC';
require_once 'navbar.php';
?>
<h2 class="text-center font-weight-bolder">USERS LIST</h2>
<table class="table table-striped table-dark table-bordered">
    <thead>
        <tr>
            <th colspan="7" class="text-center">
                TOTAL USERS <?= $totalUsers ?>. Page <?= $page ?> of <?= $numPages ?>
            </th>
        </tr>
        <tr>
            <th class="<?= $orderBy === 'id' ? $orderDirClass : '' ?>"><a href="<?= $pageUrl ?>?<?= $orderByQueryString ?>&orderBy=id&orderDir=<?= $orderDir ?>">ID</a></th>
            <th class="<?= $orderBy === 'username' ? $orderDirClass : '' ?>"><a href="<?= $pageUrl ?>?<?= $orderByQueryString ?>&orderBy=username&orderDir=<?= $orderDir ?>">NAME</a></th>
            <th class="<?= $orderBy === 'roletype' ? $orderDirClass : '' ?>"><a href="<?= $pageUrl ?>?<?= $orderByQueryString ?>&orderBy=roletype&orderDir=<?= $orderDir ?>">ROLETYPE</a></th>
            <th class="text-primary">AVATAR</th>
            <th class="<?= $orderBy === 'fiscalcode' ? $orderDirClass : '' ?>"><a href="<?= $pageUrl ?>?<?= $orderByQueryString ?>&orderBy=fiscalcode&orderDir=<?= $orderDir ?>">FISCAL CODE</a></th>
            <th class="<?= $orderBy === 'email' ? $orderDirClass : '' ?>"><a href="<?= $pageUrl ?>?<?= $orderByQueryString ?>&orderBy=email&orderDir=<?= $orderDir ?>">EMAIL</a></th>
            <th class="<?= $orderBy === 'age' ? $orderDirClass : '' ?>"><a href="<?= $pageUrl ?>?<?= $orderByQueryString ?>&orderBy=age&orderDir=<?= $orderDir ?>">AGE</a></th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($users) {

            $webAvatarDir = getConfig('webAvatarDir');
            $avatarDir = getConfig('avatarDir');
            $thumbnail_width = getConfig('thumbnail_width');
            $previewimg_width = getConfig('previewimg_width');

            foreach ($users as $user) {
                $avatarThumbImg = $user['avatar'] && file_exists($avatarDir . 'thumb_' . $user['avatar']) ? $webAvatarDir . 'thumb_' . $user['avatar'] : $webAvatarDir . 'placeholder.jpg';
                $avatarPreviewImg = $user['avatar'] && file_exists($avatarDir . 'preview_' . $user['avatar']) ? $webAvatarDir . 'preview_' . $user['avatar'] : '';
                $avatarBigImg = $user['avatar'] && file_exists($avatarDir . $user['avatar']) ? $webAvatarDir . $user['avatar'] : '';
        ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['roletype'] ?></td>
                    <td>
                        <?php
                        if ($avatarBigImg) : ?>
                            <a href="<?= $avatarBigImg ?>" target="_blank" class="thumbnail">
                                <img class="rounded-lg" src="<?= $avatarThumbImg ?>" alt="" width="<?= $thumbnail_width ?>">
                                <?php if ($avatarPreviewImg) : ?>
                                    <span>
                                        <img class="rounded-lg" src="<?= $avatarPreviewImg ?>" alt="" width="<?= $previewimg_width ?>">
                                    </span>
                                <?php endif; ?>
                            </a>
                        <?php else : ?>
                            <img class="rounded-lg" src="<?= $avatarThumbImg ?>" alt="">
                        <?php endif; ?>
                    </td>
                    <td><?= $user['fiscalcode'] ?></td>
                    <td><a href="mailto:<?= $user['email'] ?>"><?= $user['email'] ?></a></td>
                    <td><?= $user['age'] ?></td>
                    <td class="d-flex flex-wrap">
                        <div class="row d-flex flex-wrap flex-column">
                            <?php
                            if (userCanUpdate()) :
                            ?>
                                <div class="col-4 p-1">
                                    <a class="btn btn-success" href="<?= $updateUrl ?>?<?= $orderByNavQueryString ?>&page=<?= $page ?>&id=<?= $user['id'] ?>&action=update">
                                        <i class="fa fa-pen"></i>
                                        UPDATE
                                    </a>
                                </div>
                            <?php
                            endif;
                            ?>
                            <?php
                            if (userCanDelete()) :
                            ?>
                                <div class="col-4 p-1">
                                    <a onclick="return confirm('Delete user?')" class="btn btn-danger" href="<?= $deleteUrl ?>?<?= $orderByNavQueryString ?>&page=<?= $page ?>&id=<?= $user['id'] ?>&action=delete">
                                        <i class="fa fa-trash-alt"></i>
                                        DELETE
                                    </a>
                                </div>
                            <?php
                            endif;
                            ?>
                        </div>
                    </td>
                </tr>
            <?php
            }
            ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="8" class="text-center">
                <?php
                require_once 'navigation.php';
                ?>
            </td>
        </tr>
    </tfoot>
<?php
        } else {
?>
    <tr>
        <td colspan="6" class="text-center">No Records found</td>
    </tr>
<?php
        }
?>
</table>