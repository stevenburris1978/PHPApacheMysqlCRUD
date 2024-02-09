<?php
// POST Data
$newCity = filter_input(INPUT_POST, "newCity", FILTER_UNSAFE_RAW);
$countryCode = filter_input(INPUT_POST, "countryCode", FILTER_UNSAFE_RAW);
$district = filter_input(INPUT_POST, "district", FILTER_UNSAFE_RAW);
$population = filter_input(INPUT_POST, "population", FILTER_UNSAFE_RAW);

// GET Data
$city = filter_input(INPUT_GET, "city", FILTER_UNSAFE_RAW)
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Week 4</title>
</head>

<body>
    <main>
        <header>
            <h1>Week 4 Connecting to Database</h1>
        </header>
        <?php
        if (isset($deleted)) {
            echo "Record has been Deleted!";
        } else if (isset($updated)) {
            echo "Record has been updated!";
        }
        ?>
        <?php if (!$city && !$newCity) { ?>
            <section>
                <h2>Select Data / Read Data</h2>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="GET">
                    <label for="city">City Name:</label>
                    <input type="text" id="city" name="city" required>
                    <button>Submit</button>
                </form>
            </section>
            <section>
                <h2>Insert Data / Create Data</h2>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                    <label for="newCity">New City:</label>
                    <input type="text" id="newCity" name="newCity" required>
                    <label for="countryCode">Country Code:</label>
                    <input type="text" id="countryCode" name="countryCode" required>
                    <label for="district">District:</label>
                    <input type="text" id="district" name="district" required>
                    <label for="population">Population:</label>
                    <input type="text" id="population" name="population" required>
                    <button>Submit</button>
                </form>
            </section>
        <?php } else { ?>
            <?php include("database.php") ?>
            <?php
            if ($newCity) {
                $query = 'INSERT INTO city
                            (Name, CountryCode, District, Population)
                            VALUES 
                            (:newCity, :countryCode, :district, :population)';
                $statement = $db->prepare($query);
                $statement->bindValue(':newCity', $newCity);
                $statement->bindValue(':countryCode', $countryCode);
                $statement->bindValue(':district', $district);
                $statement->bindValue(':population', $population);
                $statement->execute();
                $statement->closeCursor();
            }
            ?>

            <?php if ($city || $newCity) {
                $query = 'SELECT * FROM city
                            WHERE Name= :city 
                                ORDER BY Population DESC';
                $statement = $db->prepare($query);
                if ($city) {
                    $statement->bindValue(":city", $city);
                } else {
                    $statement->bindValue(":city", $newCity);
                }
                $statement->execute();
                $results = $statement->fetchAll();
                $statement->closeCursor();
            } ?>
            <?php if (!empty($results)) { ?>
                <section>
                    <h2>Update or Delete</h2>
                </section>
                <?php
                foreach ($results as $result) {
                    $id = $result["ID"];
                    $city = $result["Name"];
                    $countryCode = $result["CountryCode"];
                    $district = $result["District"];
                    $population = $result["Population"];
                ?>
                    <form action="update_record.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $id ?>">

                        <label for="city<?php echo $city ?>">City Name:</label>
                        <input type="text" name="city" value="<?php echo $city ?>">

                        <label for="city<?php echo $countryCode ?>">countryCode:</label>
                        <input type="text" name="countryCode" value="<?php echo $countryCode ?>">

                        <label for="district<?php echo $district ?>">District:</label>
                        <input type="text" name="district" value="<?php echo $district ?>">

                        <label for="population<?php echo $population ?>">Population:</label>
                        <input type="text" name="population" value="<?php echo $population ?>">


                        <button>Update</button>
                    </form>
                    <form action="delete_record.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <button>Delete</button>
                    </form>
                <?php } ?>
            <?php } else { ?>
                <p>Sorry No Results!</p>
            <?php } ?>
            <a href="<?php echo $_SERVER['PHP_SELF'] ?>">GO To Request Form</a>
        <?php } ?>
    </main>
</body>

</html>