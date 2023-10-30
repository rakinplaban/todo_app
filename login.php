<?php 
    $pageTitle = "Login User";
    include "layout.php" 
?>
    <div class="entry bg-success">
        <div>
            <h2 style="text-align: center; color:aliceblue;">Login</h2>
        </div>
    
        <!-- {% if message %}
            <div>{{ message }}</div>
        {% endif %} -->
        <div class="bg-light border border-success">
            <div class="formview">
                <form action="" method="post">
                    <!-- {% csrf_token %} -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input autofocus class="form-control" type="email" name="email" placeholder="Email Id" require>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-control" type="password" name="password" placeholder="Password" require>
                    </div>
                    <input class="btn btn-success" type="submit" value="Login">
                </form>
            </div>
            <div style="margin-bottom: 10px;"> </div>
            <div style="margin-left: 10px;">
                Don't have an account? <a href="register.php">Register here.</a>
            </div>
            
        </div>
        
    </div>
<?php include "ending.php" ?>