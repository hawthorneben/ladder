<!DOCTYPE html>
<html>
    <?php include('../resources/header.php'); ?>
    <header class="w3-container theme-color bgimage w3-center" style="padding:128px 16px; margin-bottom: -65px;">
        <h5 id="header" class="accent-color w3-margin w3-jumbo">
    <?php
        $username = htmlspecialchars($_POST["username"]);
        $password = htmlspecialchars($_POST["password"]);
        $name = htmlspecialchars($_POST["name"]);
        $email = htmlspecialchars($_POST["email"]);
        $phone = htmlspecialchars($_POST["phone"]);

        // Connecting, selecting database
        include('../resources/db_connect.php');
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $statement = $connection->prepare("select username from player where username=:user");
        $statement->execute(array(":user"=>$username));

        if ($statement->rowCount() > 0)
        {
            echo "Error.</h5>";
            echo "<h3 class='accent-color'>Username is already taken</h3>";
            echo "<a href='user/register'><button class='card card__one theme-color w3-button
                     w3-padding-large w3-large w3-margin-top w3-round'>Try again</button></a>";
            exit();
        }

        try 
        {
            // See if there is anybody in the database
            $result = $connection->query("select count(name) from player");
            if ($result->fetch(PDO::FETCH_ASSOC)['count'] == 0)
            {
                $statement = $connection->prepare("insert into player (name, email, rank,
                                            username, phone, password) values (:name, :email,
                                            :rank, :username, :phone, :password)");

                $statement->execute(array(":name"=>$name, ":email"=>$email,
                                ":rank"=>1, ":username"=>$username, ":phone"=>$phone,
                                ":password"=>$hashedPassword));
            }
            else
            {
                // Prepare an insert query
                $statement = $connection->prepare("insert into player (name, email, rank, 
                                           username, phone, password) 
                                           select :name, :email, max(rank)+1, :username, 
                                           :phone, :password from player");
             
                // Execute the query
                $statement->execute(array(":name"=>$name, ":email"=>$email, 
                                          ":username"=>$username, ":phone"=>$phone, 
                                          ":password"=>$hashedPassword));
            }
        }
        catch (PDOException $e) 
        {
            echo "Query error";
            die();
        }

        echo "Welcome, ".$name;

        // Free connection
        $connection = null;
    ?>!</h5>
        <button id="viewLadder" class="card card__one theme-color w3-button w3-padding-large w3-large w3-margin-top w3-round"
            onclick="document.getElementById('id01').style.display='block'">Login</button>
    </header>
  </body>
</html>
