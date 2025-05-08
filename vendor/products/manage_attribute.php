<?php
require_once('./../config.php');

if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `product_list` where id = '{$_GET['id']}' and delete_flag = 0 ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }else{
?>
		<center>Unknown Shop Type</center>
		<style>
			#uni_modal .modal-footer{
				display:none
			}
		</style>
		<div class="text-right">
			<button class="btn btndefault bg-gradient-dark btn-flat" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
		</div>
<?php
		exit;
	}
}

// Handle form submission for adding/updating product attributes
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["title"])) {
    foreach ($_POST["title"] as $index => $title) {
        $description = $_POST["description"][$index];
        $quantity = intval($_POST["quantity"][$index]);
        $amount = intval($_POST["amount"][$index]);
        $id = $_POST["id"];
        $attr_id = isset($_POST["attr_id"][$index]) ? intval($_POST["attr_id"][$index]) : null;

        if ($attr_id) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE product_attribute SET title=?, description=?, quantity=?, amount=? WHERE id=?");
            $stmt->bind_param("ssiii", $title, $description, $quantity, $amount, $attr_id);
        } else {
            // Insert new record
            $stmt = $conn->prepare("INSERT INTO product_attribute (product_id, title, description, quantity, amount) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issii", $id, $title, $description, $quantity, $amount);
        }

        if (!$stmt->execute()) {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }
}

// Handle delete request
if (isset($_POST["delete_id"])) {
    $delete_id = intval($_POST["delete_id"]);
    $conn->query("DELETE FROM product_attribute WHERE id = $delete_id");
}

// Fetch existing records
$records = $conn->query("SELECT * FROM product_attribute where product_id = $id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Attributes</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .actions {
            display: flex;
            gap: 5px;
        }
        button {
            padding: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Product Attributes</h2>
    <br>
	<h1><?php echo isset($name) ? $name : ''; ?></h1>
    <div class="row">
        <div class="col-md-8">
            <form method="POST" id="productForm">
                <input type="hidden" value="<?php echo isset($id) ? $id : ''; ?>" name="id"/>
                <button type="button" onclick="addRow()" style="float:right;margin-bottom:10px;">➕ Add</button>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th width="20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productTable">
                        <?php while ($row = $records->fetch_assoc()) : ?>
                            <tr data-id="<?= $row['id'] ?>">
                                <td><input type="hidden" name="attr_id[]" value="<?= $row['id'] ?>">
                                    <input type="text" name="title[]" value="<?= htmlspecialchars($row["title"]) ?>" readonly>
                                </td>
                                <td><input type="text" name="description[]" value="<?= htmlspecialchars($row["description"]) ?>" readonly></td>
                                <td><input type="number" name="quantity[]" value="<?= $row["quantity"] ?>" readonly></td>
                                <td><input type="number" name="amount[]" value="<?= $row["amount"] ?>" readonly></td>
                                <td class="actions">
                                    <button type="button" onclick="editRow(this)">✏️</button>
                                    <button type="button" onclick="deleteRow(this, <?= $row['id'] ?>)">❌</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <button type="submit" style="float:right;margin-top:10px;">💾 Save All</button>
            </form>
        </div>
    </div>

    <script>
		window.onload = function() {
			let inputs = document.querySelectorAll("input");

			inputs.forEach(input => {
				input.style.border = "none"; 
				input.style.background = "none"; // Removes borders on load
			});
		};
        function addRow() {
            let table = document.getElementById("productTable");
            let row = table.insertRow();
            row.innerHTML = `
                <td><input type="text" name="title[]"></td>
                <td><input type="text" name="description[]"></td>
                <td><input type="number" name="quantity[]"></td>
                <td><input type="number" name="amount[]"></td>
                <td class="actions">
                    <button type="button" onclick="deleteRow(this, null)">❌</button>
                </td>
            `;
        }

        function editRow(button) {
            let row = button.parentElement.parentElement;
            let inputs = row.querySelectorAll("input");
            
            inputs.forEach(input => {
                input.removeAttribute("readonly");
                input.style.border = "1px solid #000";
				input.style.background = "#fff"; 
            });

            row.querySelector(".actions").innerHTML += `
                <button type="button" onclick="saveEdit(this)">💾</button>
            `;
        }

        function saveEdit(button) {
            let row = button.parentElement.parentElement;
            let id = row.dataset.id;
            let inputs = row.querySelectorAll("input");
            
            let data = {
                id: id,
                title: inputs[1].value,
                description: inputs[2].value,
                quantity: inputs[3].value,
                amount: inputs[4].value
            };

            fetch("", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams(data),
            }).then(response => response.text())
            .then(result => {
                inputs.forEach(input => {
					input.setAttribute("readonly", true);
					input.style.border = "none";
				});
                button.remove();
            }).catch(error => console.error("Error:", error));
        }

        function deleteRow(button, id) {
            if (id) {
                fetch("", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams({ delete_id: id }),
                }).then(response => response.text())
                .then(() => button.parentElement.parentElement.remove())
                .catch(error => console.error("Error:", error));
            } else {
                button.parentElement.parentElement.remove();
            }
        }
    </script>
</body>
</html>