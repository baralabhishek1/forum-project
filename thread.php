<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <link rel="stylesheet" href="css/style.css">
    <title>Welcome to iDiscuss - Coding Forums</title>
    <style>
    #ques {
        min-height: 455px;
    }
    </style>
</head>

<body>
    <?php  include 'partials/_dbconnect.php'; ?>
    <?php  include 'partials/_header.php'; ?>
    <?php 
    $id= $_GET['threadid'];
    $sql= "SELECT * FROM `threads` WHERE thread_id= $id";
    $result= mysqli_query($conn, $sql);
    $noResult= true;
    while($row= mysqli_fetch_assoc( $result)) {
        $noResult= false;
        $title= $row['thread_title'];
        $desc= $row['thread_desc'];
        $thread_user_id= $row['thread_user_id'];

        $sql2= "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
        $result2= mysqli_query($conn, $sql2);
        $row2= mysqli_fetch_assoc($result2);
        $posted_by= $row2['user_email'];
    }
    ?>

    <?php
      $method= $_SERVER['REQUEST_METHOD'];
      $showAlert= false;
      if($method== 'POST'){
        //   insert into comment db
          $comment= $_POST['comment'];
          $comment= str_replace("<", "&lt;", $comment);
          $comment= str_replace(">", "&gt;", $comment);

          $sno= $_POST['sno'];
          $sql= "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`, `comment_time`) VALUES ('$comment', '$id', '$sno', current_timestamp());";
          $result= mysqli_query($conn ,$sql);
          $showAlert= true;
          if($showAlert){
              echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Your comment has been added successfully. 
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
          }
      }
    ?>


    <div class="jumbotron">
        <h1 class="display-4"> <?php echo $title; ?></h1>
        <p class="lead"><?php echo $desc; ?></p>
        <hr class="my-4">
        <p>Posted by - <em><?php echo $posted_by ?></em></p>
        <p>This is a peer to peer forum .No Spam / Advertising / Self-promote in the forums. ...
            Do not post copyright-infringing material. ...
            Do not post “offensive” posts, links or images. ...
            Do not cross post questions. ...
            Do not PM users asking for help. ...
            Remain respectful of other members at all times.
        </p>

    </div>

    <?php 
      if(isset($_SESSION['loggedin']) && ($_SESSION['loggedin'])== true){
       echo '<div class="container">

       <form action="' . $_SERVER['REQUEST_URI'] .'" method="post">
           <h1 class="py-2">Post a comment .</h1>

           <div class="form-group">
               <label for="comment">Type your comment.</label>
               <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
               <input type="hidden" name="sno" value="'.$_SESSION["sno"] .'">
           </div>

           <button type="submit" class="btn btn-success">POST COMMENT</button>
       </form>
   </div>';
      }
      else {
          echo '<div class="container">
          <h1 class="py-2">Start a Discussion.</h1>
          <p class="lead">You are not logged in. Please Login to start a discussion</p>
        </div>';
      }

    ?>


    <div class="container">



        <!-- <?php 
            $id= $_GET['threadid'];
            $sql= "SELECT * FROM `threads` WHERE thread_id=$id";
            $result= mysqli_query($conn, $sql);
            while($row= mysqli_fetch_assoc( $result)) {
                $title= $row['thread_title'];
                $desc= $row['thread_desc'];
            
   
                echo '<div class="media my-4">
                    <img src="img/user-img.png" class="mr-3" width="40px" alt="...">
                    <div class="media-body">
                        <h5 class="mt-0"><a class="text-dark" href="thread.php?threadid='. $id .'">'. $title . '</a></h5>
                            '. $desc.'
                    </div>
                </div>';
            }
        ?> -->



    </div>

    <div class="container" id="ques">
        <h3 class="py-2">Comments</h3>
        <?php 
            $id= $_GET['threadid'];
            $sql= "SELECT * FROM `comments` WHERE thread_id=$id";
            $result= mysqli_query($conn, $sql);
            $noResult= true;
            while($row= mysqli_fetch_assoc( $result)) {
                $noResult= false;
                $id= $row['comment_id'];
                $content= $row['comment_content'];
                $thread_user_id= $row['comment_by'];

                $sql2= "SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
                $result2= mysqli_query($conn, $sql2);
                $row2= mysqli_fetch_assoc( $result2);
            
                $d= date("l jS \of F Y h:i:s A");
                echo '<div class="media my-4">
                    <img src="img/user-img.png" class="mr-3" width="35px" alt="...">
                        <div class="media-body">
                            <p class=" my-0"><b>'. $row2['user_email'] .' at: </b>'. $d .'</p> 
                                '. $content.' 
                        </div>
                    </div>';
            }

            // // echo var_dump($noResult);
            if($noResult){
            echo '<div class="jumbotron jumbotron-fluid">
                <div class="container">
                 <p class="display-4">No Comments Found</p>
                    <p class="lead">Be the first person to comment.
                    </p>
                </div>
            </div>';
            }
        ?>




    </div>

    <?php  include 'partials/_footer.php'; ?>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
</body>

</html>