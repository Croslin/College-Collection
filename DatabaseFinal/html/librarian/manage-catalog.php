<!-- Code written by Andrew Davison for CSC 362 Term Project -->
<!-- Displays the catalog for a librarian, with the options to add, remove, or update media info -->

<?php
    //Boilerplate
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
    session_start();

	$config = parse_ini_file('/home/odroid/csc362-proj-therpston-databases-inc/mysqli_librarian.ini');
    $dbname = 'TherpstonCountyLibrary';

    //Use the name of the input database to connect to that one specifically
	$conn = new mysqli(
        $config['mysqli.default_host'],
        $config['mysqli.default_user'],
        $config['mysqli.default_pw'],
        $dbname
    );

	if ($conn->connect_errno) {
 		echo "Error: Failed to make a MySQL connection, here is why: ". "<br>";
 		echo "Errno: " . $conn->connect_errno . "\n";
 		echo "Error: " . $conn->connect_error . "\n";
 		exit; // Quit this PHP script if the connection fails.
	}

    //Query for Reading Books
    $dbquery = file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/BooksRead.sql');  
    $books = $conn->query($dbquery);

    $n_books = $books -> num_rows;
    $m_books = $books -> field_count;
    $data_books = $books -> fetch_all();
    $fields_books = $books -> fetch_fields(); 

    //Query for Reading Movies
    $dbquery = file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/MoviesRead.sql');  
    $movies = $conn->query($dbquery);

    $n_movies = $movies -> num_rows;
    $m_movies = $movies -> field_count;
    $data_movies = $movies -> fetch_all();
    $fields_movies = $movies -> fetch_fields();

    //Check to see if we need to remove any media
    $needs_redirect = false;
    if (isset($_POST['RemoveMedia'])) {
        //Prep delete statement
        $del_stmt = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/MediaDelete.sql'));
        $del_stmt->bind_param('i', $media_id);

        //Look through books for checked boxes
        for ($i = 0; $i < $n_books; $i++) {
            $media_id = $data_books[$i][1];

            if (isset($_POST["checkbox$media_id"])) {
                $needs_redirect = true;
                if (!$del_stmt->execute()) {
                    echo $conn->error;
                }
            }
        }

        //Look through movies for checked boxes
        for ($i = 0; $i < $n_movies; $i++) {
            $media_id = $data_movies[$i][1];

            if (isset($_POST["checkbox$media_id"])) {
                $needs_redirect = true;
                if (!$del_stmt->execute()) {
                    echo $conn->error;
                }
            }
        }
    }

    $user_feedback = '';
    //Check if a librarian is inserting a new piece of media
    if (isset($_POST["CreateMedia"])) {
        $title = htmlspecialchars($_POST["MediaTitle"]);
        $publicationyear = htmlspecialchars($_POST["PublicationYear"]);
        $series = htmlspecialchars($_POST["MediaSeries"]);
        $genre = htmlspecialchars($_POST["MediaGenre"]);
        $description = htmlspecialchars($_POST["MediaDescription"]);

        //Safety check
        if (isset($_POST["bookcheckbox"]) && isset($_POST["moviecheckbox"])) {
            $user_feedback = "<p>Please only input information for either a book or a movie, not both.</p>";
        } else if (isset($_POST["bookcheckbox"])) {
            //Insert Book
            $book_author = explode(" ", htmlspecialchars($_POST["AuthorName"]));
            $ISBN = htmlspecialchars($_POST["ISBN"]);
            $length = htmlspecialchars($_POST["LengthInPages"]);
            $format = htmlspecialchars($_POST["BookFormat"]);
            $publisher = htmlspecialchars($_POST["BookPublisher"]);

            if (count($book_author) < 2) {
                $user_feedback = "<p>Error: Author name improperly formatted. Please provide both a first and last name separated by a space.</p>";
            } else {
                //Query to make sure that the book does not already exist in the database
                $check_book = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/CallFindBook.sql'));
                $check_book->bind_param('sss', $title, $book_author[0], $book_author[1]);
                $check_book->execute();
                $check_book->bind_result($does_book_exist);
                $check_book->fetch();
                $check_book->close();

                if ($does_book_exist != 1) {
                    $needs_redirect = true;
                    
                    //Insert book
                    $insert_book = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/CallInsertBook.sql'));
                    $insert_book->bind_param('sisssssssis', $title, $publicationyear, $series, $description, $genre, $book_author[0], $book_author[1], $ISBN, $format, $length, $publisher);
                    if (!$insert_book->execute()) {
                        echo $conn->error;
                    }
                } else {
                    $user_feedback = "<p>A book already exists under this name!</p>";
                }
            }
        } else if (isset($_POST["moviecheckbox"]))  {
            //Insert Movie
            $movie_director = explode(" ", htmlspecialchars($_POST["DirectorName"]));
            $format = htmlspecialchars($_POST["MovieFormat"]);
            $length = htmlspecialchars($_POST["LengthInMinutes"]);
            $studio = htmlspecialchars($_POST["MovieStudio"]);

            if (count($movie_director) < 2) {
                $user_feedback = "<p>Error: Director name improperly formatted. Please provide both a first and last name separated by a space.</p>";
            } else {
                $check_movie = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/CallFindMovie.sql'));
                $check_movie->bind_param('sss', $title, $movie_director[0], $movie_director[1]);
                $check_movie->execute();
                $check_movie->bind_result($does_movie_exist);
                $check_movie->fetch();
                $check_movie->close();

                if ($does_movie_exist != 1) {
                    $needs_redirect = true;

                    $insert_movie = $conn->prepare(file_get_contents('/home/odroid/csc362-proj-therpston-databases-inc/sql-files/QueryFiles/CallInsertMovie.sql'));
                    $insert_movie->bind_param('sissssssis', $title, $publicationyear, $series, $description, $genre, $movie_director[0], $movie_director[1], $format, $length, $studio);
                    if (!$insert_movie->execute()) {
                        echo $conn->error;
                    }
                } else {
                    $user_feedback = "<p>A movie already exists under this name!</p>";
                }
            }
        } else {
            $user_feedback = "<p>Please input complete information.</p>";
        }
    }

    //Use Post-Redirect-Get if needed
    if ($needs_redirect) {
        header("Location: ".$_SERVER['REQUEST_URI'], true, 303);
        exit();
    }
?>

<style>
    th {border-style: outset;}
</style>

<!-- List of pages for librarian -->
<p>
    <a href="http://rajah.centre.edu/librarian/manage-patrons.php">Manage Patrons</a><t>
    <a href="http://rajah.centre.edu/librarian/manage-clubs.php">Manage Clubs</a>
</p>


<!-- Header for the catalog -->
<h1> <u>Therpston County Library Catalog</u> </h1>
<?php echo isset($_SESSION['user_name']) ? "<p><b>Hello ".$_SESSION['user_name']."!</b></p>" : "<p><b>Librarian View</b></p>"; ?>
<?php echo $user_feedback; ?>

<h3><u>Current Books in the Library</u></h3>

<!-- Display current media -->
<form action='manage-catalog.php', METHOD='POST'>
    <table>
        <thead>
            <tr>
                <?php echo "<th>"."Delete from Records?"."</th>";
                    for($j = 0; $j < $m_books; $j++){
                        echo "<th>".$fields_books[$j]->name ."</th>";   
                    }   
                ?>
            </tr>    
        </thead>
        <tbody>
            <?php for ($i = 0; $i < $n_books; $i++) { ?>
                <tr> 
                    <td> <input type="checkbox", name="checkbox<?php echo $data_books[$i][1]; ?>"/> </td>
                    <td> <a href="http://rajah.centre.edu/librarian/manage-media.php?media_id=<?php echo $data_books[$i][1]; ?>"><?php echo $data_books[$i][0]; ?></a> </td>
                    <?php for($j = 1; $j < $m_books; $j++) { ?>
                        <td> <?php echo $data_books[$i][$j]; ?> </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br>
    <input type="submit", value="Remove Book", name="RemoveMedia", method='POST'/>
</form>

<h3><u>Current Movies in the Library</u></h3>

<form action='manage-catalog.php', METHOD='POST'>
    <table>
        <thead>
            <tr>
                <?php echo "<th>"."Delete from Records?"."</th>";
                    for($j = 0; $j < $m_movies; $j++){
                        echo "<th>".$fields_movies[$j]->name ."</th>";   
                    }   
                ?>
            </tr>    
        </thead>
        <tbody>
            <?php for ($i = 0; $i < $n_movies; $i++) { ?>
                <tr> 
                    <td> <input type="checkbox", name="checkbox<?php echo $data_movies[$i][1]; ?>"/> </td>
                    <td> <a href="http://rajah.centre.edu/librarian/manage-media.php?media_id=<?php echo $data_movies[$i][1]; ?>"><?php echo $data_movies[$i][0]; ?></a> </td>
                    <?php for($j = 1; $j < $m_movies; $j++) { ?>
                        <td> <?php echo $data_movies[$i][$j]; ?> </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br>
    <input type="submit", value="Remove Movie", name="RemoveMedia", method='POST'/>
</form>

<h3><u>Insert New Media</u></h3>

<!-- Form for inserting new media -->
<form action="manage-catalog.php", method='POST'>
    <!-- Input -->
    <table>
        <tbody>
            <tr> 
                <td>Enter Media Title:</td>
                <td><input type="text", id="MediaTitle", name="MediaTitle"/></td>
            </tr>
            <tr>
                <td>Enter Media Publication Year:</td>
                <td><input type="number", id="PublicationYear", name="PublicationYear"/></td>
            </tr>
            <tr>
                <td>Enter Media Series:</td>
                <td><input type="text", id="MediaSeries", name="MediaSeries"/></td>
            </tr>
            <tr>
                <td>Enter Media Genre:</td>
                <td><input type="text", id="MediaGenre", name="MediaGenre"/></td>
            </tr>
            <tr>
                <td>Enter a Brief Media Description:</td>
                <td><textarea id="MediaDescription" name="MediaDescription" rows="4" cols="50"?></textarea></td>
            </tr>
        </tbody>            
    </table>

    <h4><u>Book Information</u></h4>

    <table>
        <tbody>
            <tr>
                <td>Is this media a book?</td>
                <td> <input type="checkbox", name="bookcheckbox"/> </td>
            </tr>
            <tr> 
                <td>Enter Book ISBN:</td>
                <td><input type="number", id="ISBN", name="ISBN"/></td>
            </tr>
            <tr>
                <td>Enter Book Author Name:</td>
                <td><input type="text", id="AuthorName", name="AuthorName"/></td>
            </tr>
            <tr>
                <td>Enter Book Format:</td>
                <td><input type="text", id="BookFormat", name="BookFormat"/></td>
            </tr>
            <tr>
                <td>Enter Book Length in Pages:</td>
                <td><input type="text", id="LengthInPages", name="LengthInPages"/></td>
            </tr>
            <tr>
                <td>Enter the Book Publisher:</td>
                <td><input type="text", id="BookPublisher", name="BookPublisher"/></td>
            </tr>
        </tbody>            
    </table>

    <h4><u>Movie Information</u></h4>

    <table>
        <tbody>
            <tr>
                <td>Is this media a movie?</td>
                <td> <input type="checkbox", name="moviecheckbox"/> </td>
            </tr>
            <tr>
                <td>Enter Movie Director Name:</td>
                <td><input type="text", id="DirectorName", name="DirectorName"/></td>
            </tr>
            <tr>
                <td>Enter Movie Format:</td>
                <td><input type="text", id="MovieFormat", name="MovieFormat"/></td>
            </tr>
            <tr>
                <td>Enter Movie Length in Minutes:</td>
                <td><input type="text", id="LengthInMinutes", name="LengthInMinutes"/></td>
            </tr>
            <tr>
                <td>Enter the Movie Studio:</td>
                <td><input type="text", id="MovieStudio", name="MovieStudio"/></td>
            </tr>
        </tbody>            
    </table>

    <!-- Submit! -->
    <input type="submit", value="Insert Media", name="CreateMedia", method='POST'/>
</form>
