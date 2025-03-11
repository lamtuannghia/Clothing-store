<?php
include "../config/connect.php";

$id = $_GET['id']; // Lấy ID từ URL

$sql = "SELECT c.id, c.name, ca.name as CateName, ca.id as CateId
        FROM categories c
        JOIN cate ca on c.cate_id = ca.id
        WHERE c.id = $id";
$result = mysqli_query($conn,$sql);
$row = $result->fetch_assoc();

if(isset($_POST['edit_category_name'])){
    $name = $_POST['edit_category_name'];
    $cateid = $_POST['parent_id'];

    $sql_edit = "UPDATE categories
            SET name = '$name', cate_id = '$cateid'
            WHERE id = '$id'";
    $query = mysqli_query($conn,$sql_edit);

    if($query){
        header("Location: index.php?page_layout=category");
    }else{
        echo 'Looxi';
    }
}
?>

<!-- Modal Edit Category -->
<div class="content" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" id="edit_category_id" name="category_id" value="<?php echo $row['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="edit_category_name" name="edit_category_name" value="<?php echo $row['name']?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Parent Category</label>
                        <select class="form-control" id="edit_parent_id" name="parent_id">
                            <option value="<?php echo $row['CateId']?>"><?php echo $row['CateName']?></option>
                            <?php
                            $sql = "SELECT * FROM cate";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()):
                            ?>
                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <a type="button" class="btn btn-secondary" href="index.php?page_layout=category">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>