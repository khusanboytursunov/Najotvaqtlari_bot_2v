<?php require 'layouts/header.php' ?>
<?php
// Database
include 'database.php';
//foydalanuvchilar
$users = $db->query("SELECT * FROM users")->fetch_all(1);

// foydalanuvchilar soni
$sql = "SELECT COUNT(*) FROM users";
$count = $db->query($sql);
$row = $count->fetch_assoc();
$num = $row["COUNT(*)"];

?>
      <main class="content">
          <h4 class="mt-4">Foydalanuvchilar soni: <span class="badge bg-info pt-1 pb-1"><?=$num?></span></h4>
          <div class="card border-0 shadow mb-4">
              <div class="card-body">
                  <div class="table-responsive">
                      <table class="table table-centered table-nowrap mb-0 rounded">
                          <thead class="thead-light">
                          <tr>
                              <th class="border-0 rounded-start">id</th>
                              <th class="border-0">Name</th>
                              <th class="border-0">Username</th>
                              <th class="border-0">Chat_id</th>
                              <th class="border-0">Joined at</th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php
                          foreach ($users as $id=>$user){ ?>
                             <tr>
                              <td><?=$id?></td>
                              <td><b><?=$user['name']?></b></td>
                              <td><b><?=$user['username']?></b></td>
                              <td><b><?=$user['chat_id']?></b></td>
                              <td><b><?=$user['joined_at']?></b></td>
                             </tr>
                          <?php } ?>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </main>
<?php require 'layouts/footer.php' ?>
