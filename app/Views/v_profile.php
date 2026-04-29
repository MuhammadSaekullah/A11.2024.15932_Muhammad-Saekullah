<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="pagetitle">
  <h1>Profile</h1>
</div>

<section class="section">
  <div class="card">
    <div class="card-body">

      <h5 class="card-title">Profile User</h5>

      <table class="table table-bordered">
        <tr>
          <th>Username</th>
          <td><?= session()->get('username') ?></td>
        </tr>
        <tr>
          <th>Role</th>
          <td><?= session()->get('role') ?></td>
        </tr>
        <tr>
          <th>Email</th>
          <td><?= session()->get('email') ?></td>
        </tr>
        <tr>
          <th>Login Time</th>
          <td><?= session()->get('login_time') ?></td>
        </tr>
        <tr>
          <th>Status</th>
          <td>
    <?php if (session()->get('isLoggedIn')): ?>
        <span class="badge bg-success">Aktif</span>
    <?php else: ?>
        <span class="badge bg-danger">Tidak Aktif</span>
    <?php endif; ?>
</td>
      </table>

    </div>
  </div>
</section>

<?= $this->endSection() ?>