<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update score</title>
</head>
<body>

<form action="/updateScore" method="POST">
    <h1>Update score</h1>

    <label for="player_id">Player id</label>
    <input type="text" id="player_id" name="player_id"/>
    <br>

    <label for="bet_id">Bet id</label>
    <input type="text" id="bet_id" name="bet_id"/>
    <br>

    <label for="score">Score</label>
    <input type="number" id="score" name="score"/>
    <br>

    <input type="submit" value="Submit"/>
</form>

<a href="/"> Got to bet form</a>
</body>
</html>