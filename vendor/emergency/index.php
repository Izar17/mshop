<div class="content py-3">
    <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
            <h5 class="card-title"><b>Customers in Emergency</b></h5>
        </div>
        <div class="card-body">
            <div class="w-100 overflow-auto">
            <table>
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="20%">
                    <col width="20%">
                    <col width="20%">
                    <col width="20%">
                </colgroup>
                <thead>
                    <tr> 
                        <th class="p1 text-center">#</th>
                        <th class="p1 text-center">Date</th>
                        <th class="p1 text-center">Name</th>
                        <th class="p1 text-center">Contact</th>
                        <th class="p1 text-center">lat</th>
                        <th class="p1 text-center">lng</th>
                        <th class="p1 text-center">Status</th>
                        <th class="p1 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $orders = $conn->query("SELECT * from emergency where vendor_id = '{$_settings->userdata('id')}' ");

                    while($row = $orders->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="px-2 py-1 align-middle text-center"><?= $i++; ?></td>
                        <td class="px-2 py-1 align-middle"><?= date("Y-m-d H:i", strtotime($row['created_date'])) ?></td>
                        <td class="px-2 py-1 align-middle"><?= $row['name'] ?></td>
                        <td class="px-2 py-1 align-middle"><?= $row['contact'] ?></td>
                        <td class="px-2 py-1 align-middle text-right"><?= format_num($row['lat']) ?></td>
                        <td class="px-2 py-1 align-middle text-right"><?= $row['lng'] ?></td>
                        <td class="px-2 py-1 align-middle text-center">
                            <?php 
                                switch($row['status']){
                                    case 0:
                                        echo '<span class="badge badge-danger bg-gradient-danger px-3 rounded-pill">Pending</span>';
                                        break;
                                    case 1:
                                        echo '<span class="badge badge-primary bg-gradient-green px-3 rounded-pill">Accepted</span>';
                                        break;
                                    case 2:
                                        echo '<span class="badge badge-info bg-gradient-warning px-3 rounded-pill">Declined</span>';
                                        break;
                                    case 3:
                                        echo '<span class="badge badge-warning bg-gradient-success px-3 rounded-pill">Fixed</span>';
                                        break;
                                    case 4:
                                        echo '<span class="badge badge-success bg-gradient-danger px-3 rounded-pill">Cancelled</span>';
                                        break;
                                    default:
                                        echo '<span class="badge badge-light bg-gradient-light border px-3 rounded-pill">N/A</span>';
                                        break;
                                }
                            ?>
                        </td>
                        <td class="px-2 py-1 align-middle text-center">
                            
                                <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?= $row['id'] ?>"><span class="fa fa-eye text-dark"></span> </a>
                            
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.view_data').click(function(){
            uni_modal("Emergency Details","emergency/view_emergency.php?id="+$(this).attr('data-id'),'mid-large')
        })
        $('table').dataTable();
    })
</script>