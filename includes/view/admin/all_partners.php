<!DOCTYPE html>
<html>

<head>
  <style>
    table {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }
    td,
    th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }

    tr:nth-child(even) {
      background-color: #dddddd;
    }
  </style>
</head>

<body>
  <h2>UnApproved Global Partners</h2>
  <table id="user_table">
    <tr>
      <th>Sr. No</th>
      <th>Name</th>
      <th>Email</th>
      <th>Address</th>
      <th>Phone</th>
      <th>Country</th>
      <th>Approve</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
    <?php
    global $wpdb;
    $tblname = $wpdb->prefix . 'Globalpartnersmap_pl';
    $query_res = $wpdb->get_results("SELECT * FROM $tblname WHERE Status=0 ORDER BY id ASC ");
    $key = 1;
    foreach ($query_res as $result) {
      $Name = $result->Name;
      $Email = $result->Email;
      $Phone_number = $result->Phone_number;
      $Adress = $result->Adress;
      $Country = $result->Country;
    ?>
      <tr>
        <td><?php echo $key; ?></td>
        <td id="name"><?php echo $Name; ?></td>
        <td><?php echo $Email; ?></td>
        <td><?php echo $Phone_number; ?></td>
        <td><?php echo $Adress; ?></td>
        <td><?php echo $Country; ?></td>
        <td><a href="" id="approve_btn" class="approve_btn" data-id="<?php echo $result->id; ?>">Approve</a></td>
        <td><a href="" id="Edit_btn" class="Edit_btn" data-edit-id="<?php echo $result->id; ?>">Edit</a></td>
        <td><a href="" id="Delete_btn" class="Delete_btn" data-edit-id="<?php echo $result->id; ?>">Delete</a></td>
      </tr>
    <?php $key++;
    } ?>
  </table>
</body>

</html>