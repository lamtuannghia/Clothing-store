<?php
include '../config/connect.php'; // Kết nối database

// Lấy danh sách danh mục
$sql = "SELECT c.id, c.name, ca.name as CateName
        FROM categories c
        JOIN cate ca on c.cate_id = ca.id
        ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

$sql_cate = "SELECT * 
        FROM cate 
        ORDER BY id ASC";
$result_cate = mysqli_query($conn, $sql_cate);

if(isset($_POST['category_name'])){
    $name = $_POST['category_name'];
    $id = $_POST['parent_id'];

    $newcate = "INSERT INTO categories (cate_id,name)
                VALUE ('$id','$name')";
    $query = mysqli_query($conn,$newcate);

    if($query){
        header("Location: index.php?page_layout=category");
        exit();
    }else{
        echo "Looxi";
    }
}elseif(isset($_POST['parent_category_name'])){
    $name = $_POST['parent_category_name'];

    $newcate = "INSERT INTO cate (name)
                VALUE ('$name')";
    $query = mysqli_query($conn,$newcate);

    if($query){
        header("Location: index.php?page_layout=category");
        exit();
    }else{
        echo "Looxi";
    }
}

$stt = 1;
?>

<!-- Content -->
<div class="content">
    <h2>Categories</h2>
    <div class="d-flex justify-content-between mb-3">
        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCategoryModal">New Category</a>
        <input type="text" id="search" class="form-control w-25" placeholder="Search Products">
    </div>

    <!-- Bảng danh sách danh mục -->
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>STT</th>
                <th>Category</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="categoryList">
            <?php while ($category = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $stt++ ?></td>
                <td><?= $category['CateName'] ?></td>
                <td><?= $category['name'] ?></td>
                <td>
                    <a href="index.php?page_layout=editcategory&id=<?php echo $category['id'];?>" class="btn btn-sm btn-outline-primary">✏️</a>
                    <?php if ($_SESSION['admin_role'] == 'admin') {?>
                      <a href="deletecategory.php?id=<?= $category['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">🗑️</a>
                    <?php }?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal Thêm Danh Mục (Child Category) -->
<div class="modal fade" id="newCategoryModal" tabindex="-1" aria-labelledby="newCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="newCategoryModalLabel">Add New Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Tên danh mục -->
          <div class="mb-3">
            <label for="categoryName" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="categoryName" name="category_name" required>
          </div>
          <!-- Danh mục cha -->
          <div class="mb-3">
            <label for="parentCategory" class="form-label">Parent Category</label>
            <select class="form-select" id="parentCategory" name="parent_id" required>
              <option value="" disabled selected>-- Select Parent Category --</option>
              <?php while($cate = mysqli_fetch_assoc($result_cate)): ?>
                <option value="<?= $cate['id'] ?>"><?= $cate['name'] ?></option>
              <?php endwhile; ?>
            </select>
            <small>
              Or <a href="#" data-bs-toggle="modal" data-bs-target="#newParentCategoryModal" data-bs-dismiss="modal">Add Parent Category</a>
            </small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add Category</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Thêm Danh Mục Cha (Parent Category) -->
<div class="modal fade" id="newParentCategoryModal" tabindex="-1" aria-labelledby="newParentCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="newParentCategoryModalLabel">Add New Parent Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Tên danh mục cha -->
          <div class="mb-3">
            <label for="parentCategoryName" class="form-label">Parent Category Name</label>
            <input type="text" class="form-control" id="parentCategoryName" name="parent_category_name" required>
          </div>
          <!-- Nếu cần, bạn có thể thêm các trường khác -->
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add Parent Category</button>
          <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#newCategoryModal" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- <script src="assets/bootstrap.bundle.min.js"></script> -->
<script src="../assets/js/admin.js"></script>
