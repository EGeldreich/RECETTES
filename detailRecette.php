<?php
    try {
        $mysqlClient = new PDO('mysql:host=localhost;dbname=recette_emmanuel;charset=utf8', 'root', '');
    }
    catch (Exception $e) { die('Erreur : '.$e->getMessage()); }

    $idsStatement = $mysqlClient->prepare('SELECT id_recipe FROM recipe');
    $idsStatement->execute();
    $ids = $idsStatement->fetchAll(PDO::FETCH_COLUMN, 0); // PDO FETCH_COLUMN allow to get just one column of results
    // We get [0] => int(1) [1] => int(3) [2] => int(4)
    // instead of [0]=> array(1) { ["id_recipe"]=> int(1) } [1]=> array(1) { ["id_recipe"]=> int(3) } 


    if(!isset($_GET['id']) || !in_array($_GET['id'], $ids)){
        echo "<p>Oopsie, pas de recette ici... <a href='recettes.php'>retourner sur la liste</a></p>";
    } else {
        
        // SQL QUERY FOR BASE RECIPE INFOS
        $recipesQuery = 'SELECT r.recipe_name, r.preparation_time, c.category_name
        FROM recipe r
        INNER JOIN category c
        ON r.id_category = c.id_category
        WHERE r.id_recipe = :id'; // :id to make it what we want in execute

        $recipesStatement = $mysqlClient->prepare($recipesQuery); // Prepare de sql query

        $recipesStatement->execute(["id" => $_GET['id']]); // Execute the query, with id from url
        $recipe = $recipesStatement->fetch(); // Fetch just take 1 line

        // SQL QUERY FOR INGREDIENTS
        $ingredientsQuery = 'SELECT i.ingredient_name, ri.quantity, ri.unit
            FROM ingredient i
            INNER JOIN recipe_ingredients ri
            ON i.id_ingredient = ri.id_ingredient
            WHERE ri.id_recipe = :id';

        $ingredientsStatement = $mysqlClient->prepare($ingredientsQuery);

        $ingredientsStatement->execute(["id" => $_GET['id']]);
        $ingredients = $ingredientsStatement->fetchAll();

        echo "<h2>".$recipe['recipe_name']."</h2>",
            "<p><strong>".$recipe['category_name']."</strong> - ".$recipe['preparation_time']." minutes de préparation</p>",
            "Pour cette recette, il vous faut :",
            "<ul>";

        foreach($ingredients as $ingredient){
            echo "<li>".$ingredient['quantity']." ".$ingredient['unit']." de ".$ingredient['ingredient_name']."</li>";
        }
        
        echo "</ul>",
            "<br><br>",
            "<p><a href='recettes.php'>retourner sur la liste</a></p>";

    }