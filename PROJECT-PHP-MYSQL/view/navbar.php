<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">

        <form class="form-inline mt-2 mt-md-0 d-flex flex-wrap" method="get" action="<?= $pageUrl ?>" id="searchForm">
            <!-- cosi nn mi perdo la pagina, la passo come campo nascosto quando la invio -->
            <input type="hidden" name="page" id="page" value="<?= $page ?>">
            <div class="form-group">
                <label for="orderBy" class="text-white pr-1">ORDER BY</label>
                <select name="orderBy" id="orderBy" class="mr-1 form-control" onchange="document.forms.searchForm.submit()">
                    <?php
                    foreach ($orderByColumns as $val) {
                    ?>
                        <option <?= $orderBy == $val ? 'selected' : '' ?> value="<?= $val ?>"><?= $val ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="orderDir" class="text-white pr-1">ORDER</label>
                <select name="orderDir" id="orderDir" class="mr-1 form-control" onchange="document.forms.searchForm.submit()">

                    <option <?= $orderDir == 'ASC' ? 'selected' : '' ?> value="ASC">ASC</option>
                    <option <?= $orderDir == 'DESC' ? 'selected' : '' ?> value="DESC">DESC</option>

                </select>
            </div>
            <div class="form-group">
                <label for="recordsPerPage" class="text-white pr-1">RECORDS</label>
                <select name="recordsPerPage" id="recordsPerPage" class="mr-1 form-control" onchange="document.forms.searchForm.page.value=1;document.forms.searchForm.submit()">
                    <?php
                    foreach ($recordsPerPageOptions as $val) {
                    ?>
                        <option <?= $recordsPerPage == $val ? 'selected' : '' ?> value="<?= $val ?>"><?= $val ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div>
                <input class="form-control mr-sm-2" type="text" name="search" id="search" value="<?= $search ?>" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit" onclick="document.forms.searchForm.page.value=1">Search</button>
                <button class="btn btn-warning my-2 my-sm-0" type="button" onclick="location.href='<?= $pageUrl ?>'">Reset</button>
            </div>
        </form>

    </nav>
</header>