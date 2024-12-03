<?php

    try {
        $mysqlClient = new PDO('mysql:host=localhost;dbname=recette_emmanuel;charset=utf8', 'root', '');
    }
    catch (Exception $e) { die('Erreur : '.$e->getMessage()); }

    $recipesQuery = 'SELECT recipe.id_recipe, recipe.recipe_name, recipe.preparation_time, category.category_name
        FROM recipe
        INNER JOIN category
        ON recipe.id_category = category.id_category';


    $recipesStatement = $mysqlClient->prepare($recipesQuery);


    $recipesStatement->execute();
    $recipes = $recipesStatement->fetchAll();


    echo "<table>", // create a table
        "<thead>",
            "<tr>",
                "<th>Recipe Name</th>",
                "<th>Category</th>",
                "<th>Preparation time</th>",
            "</tr>",
        "</thead>",
        "<tbody>";
    foreach ($recipes as $recipe) {
        echo "<tr>",
            "<td><a href='detailRecette.php?id=".$recipe['id_recipe']."'>".$recipe["recipe_name"]."</a><td>",
            "<td>".$recipe['category_name']."<td>",
            "<td>".$recipe['preparation_time']."<td>",
            "</tr>";
    }
    echo "</tbody>",
        "</table>";

?>