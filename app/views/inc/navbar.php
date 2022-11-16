<nav class="navbar navbar-expand-lg <?= (AREA === 'User') ? 'bg-light' : 'bg-secondary';  ?>">
  <div class="container">

    <a class="navbar-brand" href="<?= URL_ROOT ?>"><?= SITE_NAME; ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link" href="<?= URL_ROOT; ?>">Home</a>
        <a class="nav-link" href="<?= URL_ROOT; ?>/pages/about">About</a>
      </div>

      <div class="navbar-nav ms-auto">
        <small class="nav-link text-dark font-weight-bold border-end"><?= AREA; ?></small>
        <a class="nav-link" href="<?= URL_ROOT; ?>/users/login">Login</a>
        <a class="nav-link" href="<?= URL_ROOT; ?>/users/register">Register</a>
      </div>
    </div>
  </div>
</nav>