<?php

    if(!isset($_GET['id'])){
        echo "<p>Oopsie, pas de recette ici... <a href='recettes.php'>retourner sur la liste</a></p>";
    } else {

        try {
            $mysqlClient = new PDO('mysql:host=localhost;dbname=recette_emmanuel;charset=utf8', 'root', '');
        }
        catch (Exception $e) { die('Erreur : '.$e->getMessage()); }

        $recipesQuery = 'SELECT r.recipe_name, r.preparation_time, c.category_name, i.ingredient_name, ri.quantity, ri.unit
        FROM recipe r
        INNER JOIN category c
        ON r.id_category = c.id_category
        INNER JOIN recipe_ingredients ri
        ON r.id_recipe = ri.id_recipe
        INNER JOIN ingredient i
        ON ri.id_ingredient = i.id_ingredient
        WHERE r.id_recipe = '.$_GET['id'];

        $recipesStatement = $mysqlClient->prepare($recipesQuery);

        $recipesStatement->execute();
        $recipes = $recipesStatement->fetchAll();

        $recipe = $recipes[1];
        // var_dump($recipes);
        
        echo "<h2>".$recipe['recipe_name']."</h2>",
            "<p><strong>".$recipe['category_name']."</strong> - ".$recipe['preparation_time']." minutes de préparation</p>",
            "Pour cette recette, il vous faut :",
            "<ul>";

        foreach($recipes as $recipe){
            echo "<li>".$recipe['quantity']." ".$recipe['unit']." de ".$recipe['ingredient_name']."</li>";
        }
        
        echo "</ul>",
            "<br><br>",
            "<p><a href='recettes.php'>retourner sur la liste</a></p>";




        // echo "<table>", // create a table
        // "<thead>",
        //     "<tr>",
        //         "<th>Recipe Name</th>",
        //         "<th>Category</th>",
        //         "<th>Preparation time</th>",
        //         "<th>Ingredient Name</th>",
        //         "<th>Quantity</th>",
        //         "<th>Unit</th>",
        //     "</tr>",
        // "</thead>",
        // "<tbody>",
        // foreach ($recipes as $recipe) {
        //     echo "<tr>",
        //         "<td><a href='detailRecette.php?id=".$recipe["id_recipe"]."'>".$recipe["recipe_name"]."</a><td>",
        //         "<td>".$recipe["category_name"]."<td>",
        //         "<td>".$recipe["preparation_time"]."<td>",
        //         "</tr>";
        // }
        // echo "</tbody>",
        //     "</table>";
    }