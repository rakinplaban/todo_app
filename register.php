<?php 
    $pageTitle = "Register User";
    include "layout.php" 
?>

    <div class="entry bg-success">
        <div>
            <h2 style="text-align: center; color:aliceblue;">Register</h2>
        </div>

        <div class="bg-light border border-success">
            <div class="formview">
                <form action="" method="post">
                    
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input class="form-control" autofocus type="text" name="first_name" placeholder="First Name">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input class="form-control" autofocus type="text" name="last_name" placeholder="Last Name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" type="email" name="email" placeholder="Email Address">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-control" type="password" name="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="confirmation">Confirm Password</label>
                        <input class="form-control" type="password" name="confirmation" placeholder="Confirm Password">
                    </div>
                    <input class="btn btn-success" type="submit" value="Register">
                </form>
                <div style="margin-bottom: 10px;"> </div>
                <div style="margin-left: 10px;">
                    Already have an account? <a href="login.php">Log In here.</a>
                </div>
            </div>
            
        </div>
        
    </div>
    

<?php include "ending.php" ?>