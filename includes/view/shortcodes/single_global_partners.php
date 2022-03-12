<!DOCTYPE html>
<html>
<head>
  <style>
    table {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }
    td, th {
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
  <table>
    <tr>
     <th>Sr. No</th>
     <th>Name</th>
     <th>Email</th>
     <th>Address</th>
     <th>Phone</th>
     <th>Country</th>
   </tr>
   <?php
   global $wpdb;
   $country = $_COOKIE['Country'];
   $tblname = $wpdb->prefix.'Globalpartnersmap_pl';
   $query_res = $wpdb->get_results( "SELECT * FROM $tblname WHERE country = '" . $country . "'");
			  //echo "<pre>";print_r($query_res);
   $key = 1;
   foreach ($query_res as $result)
   {
     $Name = $result->Name;
     $Email = $result->Email;
     $Phone_number = $result->Phone_number;
     $Adress = $result->Adress;
     $Country = $result->Country;
     ?>
     <tr>
      <td><?php echo $key;?></td>
      <td><?php echo $Name;?></td>
      <td><?php echo $Email;?></td>
      <td><?php echo $Phone_number;?></td>
      <td><?php echo $Adress;?></td>
      <td><?php echo $Country;?></td>
    </tr>
    <?php $key++; }?>
  </table>
</body>
</html>

