<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bet form</title>
</head>
<body>
    <form action="/processGame" method="POST">
        <h1>Start or Accept</h1>

        <label for="player_id">Player id</label>
        <input type="text" id="player_id" name="player_id" required/>

        <label for="game_id">Game id</label>
        <input type="text" id="game_id" name="game_id" required/>

        <label for="amount">Amount</label>
        <input type="number" id="amount" name="amount" required/>

        <input type="submit" value="Submit"/>
    </form>
    <a href="/showScore"> Go to score form</a>
</body>
</html>
