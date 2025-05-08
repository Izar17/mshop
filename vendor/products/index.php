<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<style>
	.product-img{
		width: calc(100%);
		height: auto;
		max-width: 5em;
		object-fit:scale-down;
		object-position:center center;
	}
</style>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Products</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" class="btn btn-flat btn-primary" id="create_new"><span class="fas fa-plus"></span>  Create New</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="10%">
					<col width="25%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Product Code</th>
						<th>Image</th>
						<th>Name</th>
						<th>Cost</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT * from `product_list` where delete_flag = 0 and `vendor_id` = '{$_settings->userdata('id')}' order by `name` asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td> 
							<td>000<?php echo $row['id']?></td>
							<td class="text-center"><img src="<?= validate_image($row['image_path']) ?>" alt="Product Image" class="border border-gray img-thumbnail product-img"></td>
							<td><?php echo $row['name'] ?></td>
							<td class="text-right"><?php echo '₱' . number_format($row['price'], 2); ?></td>
							<td class="text-center">
                                <?php if($row['status'] == 1): ?>
                                    <span class="badge badge-success bg-gradient-success px-3 rounded-pill">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger bg-gradient-danger px-3 rounded-pill">Inactive</span>
                                <?php endif; ?>
                            </td>
							<td align="center">
				                    <a class="view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> </a>
				                    &nbsp;
				                    <a class="edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> </a>
				                    &nbsp;
				                    <a class="delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> </a>
				                    &nbsp;
				                    <a class="manage_attribute" href="<?php echo base_url ?>vendor/?page=products/manage_attribute&id=<?php echo $row['id'] ?>"><span class="fa fa-plus text-primary"></span> </a>
				               
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
	$(document).ready(function(){
		$('#create_new').click(function(){
			uni_modal('Add New Product',"products/manage_product.php",'large')
		})
		$('.view_data').click(function(){
			uni_modal('View Product Details',"products/view_product.php?id="+$(this).attr('data-id'),'large')
		})
		$('.edit_data').click(function(){
			uni_modal('Update Product',"products/manage_product.php?id="+$(this).attr('data-id'),'large')
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this product permanently?","delete_product",[$(this).attr('data-id')])
		})
		$('table th,table td').addClass('align-middle px-2 py-1')
		$('.table').dataTable();
	})
	function delete_product($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_product",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>