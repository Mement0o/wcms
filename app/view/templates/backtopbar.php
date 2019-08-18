<header id="topbar">

<span id="search">
<form action="<?= $this->url('search') ?>" method="post">
<input type="text" name="id" id="id" placeholder="page id" required>
<input type="submit" name="action" value="read">
<?= $user->iseditor() ? '<input type="submit" name="action" value="edit">' : '' ?>
</form>
</span>



<?php if($user->iseditor()) { ?>

<span id="menu">
<a href="<?= $this->url('home') ?>" <?= $tab == 'home' ? 'class="actualpage"' : '' ?>><img src="<?= Model::iconpath() ?>home.png" alt="" class="icon">home</a>
<a href="<?= $this->url('media') ?>" <?= $tab == 'media' ? 'class="actualpage"' : '' ?>><img src="<?= Model::iconpath() ?>media.png" alt="" class="icon">media</a>
<a href="<?= $this->url('font') ?>" <?= $tab == 'font' ? 'class="actualpage"' : '' ?>><img src="<?= Model::iconpath() ?>font.png" alt="" class="icon">font</a>
<?php
if($user->isadmin()) {
?>
<a href="<?= $this->url('admin') ?>" <?= $tab == 'admin' ? 'class="actualpage"' : '' ?>><img src="<?= Model::iconpath() ?>admin.png" alt="" class="icon">admin</a>
<?php
}
?>
<a href="<?= $this->url('info') ?>"  <?= $tab == 'info' ? 'class="actualpage"' : '' ?>><img src="<?= Model::iconpath() ?>info.png" alt="" class="icon">info</a>
</span>





<?php } ?>



<span id="user">

<?php if($user->isvisitor()) { ?>


<form action="<?= $this->url('log') ?>" method="post" id="connect">
<input type="password" name="pass" id="loginpass" placeholder="password">
<input type="hidden" name="route" value="home">
<input type="submit" name="log" value="login">
</form>


<?php } else { ?>  

<span>
<a href="<?= $this->url('timeline') ?>" <?= $tab == 'timeline' ? 'class="actualpage"' : '' ?>><img src="<?= Model::iconpath() ?>timeline.png" alt="" class="icon">timeline</a>
<a href="<?= $this->url('user') ?>" <?= $tab == 'user' ? 'class="actualpage"' : '' ?>><img src="<?= Model::iconpath() ?>user.png" alt="" class="icon"><?= $user->id() ?></a> <i><?= $user->level() ?></i>
</span>


<form action="<?= $this->url('log') ?>" method="post" id="connect">
<input type="submit" name="log" value="logout">
</form>



</span>




<?php } ?>

</header>