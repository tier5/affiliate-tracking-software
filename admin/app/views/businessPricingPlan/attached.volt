<div class="portlet light bordered">
    <div class="alert alert-error">There are agencies attached to this pricing plan, so you cannot edit it!</div>
    <table class="table">
    <thead>
    <tr><th>Details</th></tr>
    </thead>
        <tbody>
        <?php if($attached_agencies) foreach($attached_agencies as $agency){ ?>
        <tr><td><a href="/"><?=$agency->name; ?></a></td></tr>
    <?php } ?>
        </body>
        </table>
</div>
