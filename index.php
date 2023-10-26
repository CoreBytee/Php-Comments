<?php

    require __DIR__ . '/vendor/autoload.php';

    $_SESSION["DBHOST"] = "localhost";
    $_SESSION["DBUSER"] = "root";
    $_SESSION["DBPASS"] = "";
    $_SESSION["DBNAME"] = "php_comments";

    $Connection = new mysqli($_SESSION["DBHOST"], $_SESSION["DBUSER"], $_SESSION["DBPASS"], $_SESSION["DBNAME"]);

?>

<?php
    // id
    // name
    // email
    // date
    // comment

    $_SESSION["error"] = "";

    if (isset($_POST)) {
        if ( !isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["comment"])) {
            $_SESSION["error"] = "Please fill in all fields";

        } elseif ( strlen($_POST["name"]) < 3 ) {
            $_SESSION["error"] = "Name must be at least 3 characters";

        } elseif ( strlen($_POST["name"]) > 50 ) {
            $_SESSION["error"] = "Name must be less than 50 characters";

        } elseif ( strlen($_POST["email"]) < 6 ) {
            $_SESSION["error"] = "Email must be at least 6 characters";

        } elseif ( strlen($_POST["email"]) > 100 ) {
            $_SESSION["error"] = "Email must be less than 100 characters";

        } elseif ( strlen($_POST["comment"]) < 3 ) {
            $_SESSION["error"] = "Comment must be at least 3 characters";

        } elseif ( strlen($_POST["comment"]) > 500 ) {
            $_SESSION["error"] = "Comment must be less than 500 characters";

        } else {
            $name = filter_var(htmlspecialchars($_POST["name"]), FILTER_SANITIZE_STRING);
            $email = filter_var(htmlspecialchars($_POST["email"]), FILTER_SANITIZE_EMAIL);
            $comment = filter_var(htmlspecialchars($_POST["comment"]), FILTER_SANITIZE_STRING);

            if ($Connection->connect_error) {
                die("Connection failed: " . $Connection->connect_error);
            }

            $sql = "INSERT INTO comments (name, email, comment) VALUES ('$name', '$email', '$comment')";

            if ($Connection->query($sql) === TRUE) {
                header("Location: /");
            } else {
                $_SESSION["error"] = "Error: " . $sql . $Connection->error;
                header("Location: /");
            }
        }

        // header("Location: /");
        // die();
    }

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Video</title>

        <link rel="stylesheet" href="./index.css">
    </head>

    <body>
        <div class="container">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/zDNaUi2cjv4?si=DjL-qYnyHVb5hxBV"
                title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen></iframe>
            <h1>Binary in 100 seconds</h1>
            <div class="commentform">
                <form action="/" method="post">
                    <input type="text" id="name" name="name" placeholder="Name" value="testname" required>
                    <input type="email" id="email" name="email" placeholder="Email" value="test@email.com" required>

                    <input type="text" name="comment" id="comment" placeholder="Comment" value="commentdata">
                    <input type="submit" value="Post comment"><a class="commenterror"><?php echo $_SESSION["error"] ?></a>
                </form>
            </div>
            <div class="comments">
                <?php
                    $sql = "SELECT * FROM comments";
                    $result = $Connection->query($sql);


                    while ($row = $result->fetch_assoc()) {
                        require __DIR__ . '/vendor/autoload.php';
                        $Avatar = new \Laravolt\Avatar\Avatar;

                        echo "<div class='comment'>";
                        echo "<img class='commentavatar' src='" . $Avatar->create($row["Name"])->toBase64() . "'></img>";
                        echo "<div class='commentcontainer'>";
                        echo "<div class='commentheader'>";
                        echo "<a>" . $row["Name"] . "</a>";
                        echo "<a>" . $row["Date"] . "</a>";
                        echo "</div>";
                        echo "<a>" . $row["Comment"] . "</a>";
                        echo "</div>";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
    </body>

</html>