<?php
//echo password_hash('testandrea', PASSWORD_DEFAULT);
?>
<form action="controller/updateRecord.php?<?= $defaultParams ?>" method="post" id="updateForm" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $user['id'] ?>"></input>
    <input type="hidden" name="action" value="<?= $user['id'] ? 'store' : 'save' ?>"></input>

    <div class="form-group row">
        <label for="username" class="col-sm-2 col-form-label">Username</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="username" id="username" placeholder="username" value="<?= $user['username'] ?>" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="email" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="email" id="email" placeholder="email" value="<?= $user['email'] ?>" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="password" class="col-sm-2 col-form-label">Password</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" name="password" id="password" placeholder="password" value="">
        </div>
    </div>
    <div class="form-group row">
        <label for="roletype" class="col-sm-2 col-form-label">Roletype</label>
        <div class="col-sm-10">
            <select name="roletype" id="roletype" class="form-control">
                <?php
                foreach (getConfig('roletypes', []) as $role) :
                    $sel = $user['roletype'] === $role ? 'selected' : '';
                    echo "\n<option $sel value='$role'>$role</option>";
                endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="fiscalcode" class="col-sm-2 col-form-label">Fiscalcode</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="fiscalcode" id="fiscalcode" placeholder="fiscalcode" value="<?= $user['fiscalcode'] ?>" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="password" class="col-sm-2 col-form-label">Age</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" name="age" id="age" placeholder="age" value="<?= $user['age'] ?>" required>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Preview Avatar</label>
        <?php
        $webAvatarDir = getConfig('webAvatarDir');
        $avatarDir = getConfig('avatarDir');
        $thumbnail_width = getConfig('thumbnail_width');
        $avatarImg = file_exists($avatarDir . 'thumb_' . $user['avatar']) ? $webAvatarDir . 'thumb_' . $user['avatar'] : $webAvatarDir . 'placeholder.jpg';
        ?>
        <div class="col-sm-10">
            <img class="rounded" src="<?= $avatarImg ?>" width="<?= $thumbnail_width ?>" alt="Image preview...">
        </div>
    </div>
    <div class="form-group row">
        <label for="avatar" class="col-sm-2 col-form-label">Avatar</label>
        <div class="col-sm-10">
            <input type="hidden" name="MAX_FILE_SIZE" value="<?= getConfig('maxFileUpload') ?>" />
            <input onchange="previewFile()" type="file" class="form-control-file form-control p-1" name="avatar" id="avatar" accept="image/*">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-2"></div>
        <?php
        if (userCanUpdate()) :
        ?>
            <div class="col-auto">
                <button class="btn btn-success">
                    <i class="fa fa-pen"></i>
                    <?= $action == 'insert' ? 'INSERT' : 'UPDATE' ?>
                </button>
            </div>
        <?php
        endif;
        ?>
        <?php if ($user['id'] && userCanDelete()) : ?>
            <div class="col-auto">
                <a href="<?= $deleteUrl ?>?id=<?= $user['id'] ?>&action=delete" onclick="return confirm('DELETE USER?')" class="btn btn-danger">
                    <i class="fa fa-trash-alt"></i>
                    DELETE
                </a>
            </div>
        <?php
        endif;
        ?>
    </div>
</form>