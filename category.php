<?php include "includes/header.php"; ?>
<?php
  $categories = oneValueQuery("SELECT", "categories", "cat_id", escape($_GET["category"]));
  $row = mysqli_fetch_assoc($categories);
  $cat_title = $row['cat_title'];
?>
<!-- Page Content -->
<div class="container">

  <div class="row">

    <!-- Blog Entries Column -->
    <div class="col-md-8">
      <h1 class="page-header">All posts from Category <small><?php echo $cat_title; ?></small></h1>
      <?php
        if (isset($_GET["category"])) {
          $cat_id = escape($_GET["category"]);

          if (isset($_GET["page"])) {
            $page_no = $_GET["page"];
          } else {
            $page_no = "";
          }

          if ($page_no == "" || $page_no == 1) {
            $page = 0;
          } else {
            $page = ($page_no * 5) - 5;
          }

          // Count query
          $query = "SELECT * FROM posts WHERE post_category_id={$cat_id} AND post_status='Published'";
          $post_count_query = mysqli_query($connection, $query);
          query_confirmation($post_count_query);
          $count = mysqli_num_rows($post_count_query);
          $count = ceil($count / 5);
          
          if($count < 1) {
            echo "<h2 class='text-center text-primary'>No posts published under this Category.</h2>";
          }
          
          $query = "SELECT posts.*, users.user_id, users.user_fname, users.user_lname ";
          $query .= "FROM posts LEFT JOIN users ON posts.post_author_id = users.user_id ";
          $query .= "WHERE post_category_id={$cat_id} AND post_status='Published' ";
          $query .= "ORDER BY post_id DESC LIMIT {$page}, 5";

          $select_all_posts_query = mysqli_query($connection, $query);
          
          query_confirmation($select_all_posts_query);

          while($row = mysqli_fetch_assoc($select_all_posts_query)) {
            $post_id = $row["post_id"];
            $post_title = $row["post_title"];
            $post_author_id = $row["post_author_id"];
            $post_cat_id = $row["post_category_id"];
            $post_date = $row["post_date"];
            $post_img = $row["post_image"];
            $post_content = substr($row["post_content"], 0, 300);
            $post_view_count = $row['post_view_count'];
            $post_author_name = "{$row['user_fname']} {$row['user_lname']}";

          ?> <!-- Closing php for entering html, but loop is still working! -->

          <h2><a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title; ?></a></h2>
          <p class="lead">
            <small>by</small> <a href="author.php?author=<?php echo $post_author_id; ?>"><?php echo $post_author_name; ?></a>
            <small>Category:</small> <a href="category.php?category=<?php echo $cat_id; ?>"><?php echo $cat_title; ?></a>
            <small><span class="glyphicon glyphicon-time"></span> Posted on</small> <?php echo $post_date; ?>
            <small>Total Post Views =</small> <?php echo $post_view_count; ?>  
          </p>
          <hr>
          <a href="post.php?p_id=<?php echo $post_id; ?>"><img class="img-responsive" src="img/<?php echo $post_img; ?>" alt=""></a>
          <hr>
          <p><?php echo $post_content; ?></p>
          <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
          <hr>

        <?php  } // Completeing the while loop
      } ?>

      <!-- Start of Pagination -->
      <div class="text-center">
        <ul class="pagination pagination-lg">
          <?php
            for ($i = 1; $i <= $count; $i++) {
              echo "<li><a href='category.php?category={$cat_id}&page={$i}'>{$i}</a></li>";
            }
          ?>
        </ul>
      </div>
      <!-- End of Paination -->

    </div>
    <!-- End of Blog Entries Column -->
      
    <!-- Sidebar file -->
    <?php include "includes/sidebar.php"; ?>

  </div>
  <!-- End of row -->

  <hr>
<!-- Footer file -->
<?php include "includes/footer.php"; ?>