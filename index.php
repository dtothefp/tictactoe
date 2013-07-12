<?php 
/*	Tic-tac-toe
 *
 *	2013 David
 */

session_start();
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', TRUE);


// Ability to assign X & Y to cell/player
$token = array(0 => "X", 1 => "O");


// Choose the beginning player and alternate players upon form submission
if (isset($_POST['current_player'])) {
	$Player = $_POST['current_player'];
} else {
	$Player = rand(0,1);
}


// Set initial conditions for determining winner
$win = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	//  Clear the board upon win/draw
	if (isset($_POST['clearBoard'])) {
		$_SESSION['board'] = array();
		$message = "Board Cleared";
	}

	// Place the token in the appropriate cell upon form submission
	if (isset($_POST['cell'])) {
		$parts = explode('-', $_POST['cell']);

		$row = $parts[0];
		$column = $parts[1];

		if (!isset($_SESSION['board'][$row][$column])) {
			$_SESSION['board'][$row][$column] = $token[$Player];
		}


		// Check for Draw 
		$results = count($_SESSION['board'][0]) +
					count($_SESSION['board'][1]) +
					count($_SESSION['board'][2]);

		if ($results == 9 && !$win) {
			$message = '<strong>Game is a Draw</strong><br>Clear Board and Play Again<br>
		<input type="submit" name="clearBoard" value="Clear Board">';
		}


		// Check Rows for Win 
		for ($row=0; $row < 3; $row++) { 
			if ($_SESSION['board'][$row][0] ==  
				$_SESSION['board'][$row][1] ==  
				$_SESSION['board'][$row][2] ==   
				) {
				$win = true;
			}
		}


		// Check Columns for Win
		for ($column=0; $column < 3; $column++) { 
			if ($_SESSION['board'][0][$column] ==  $token[$Player] &&
				$_SESSION['board'][1][$column] ==  $token[$Player] &&
				$_SESSION['board'][2][$column] ==  $token[$Player] 
				) {
				$win = true;
			}
		}

		// Check Diagonals for Win
		if ($_SESSION['board'][0][0] ==  $token[$Player] &&
			$_SESSION['board'][1][1] ==  $token[$Player] &&
			$_SESSION['board'][2][2] ==  $token[$Player] 
			) {
				$win = true;
			}
		if ($_SESSION['board'][0][2] ==  $token[$Player] &&
			$_SESSION['board'][1][1] ==  $token[$Player] &&
			$_SESSION['board'][2][0] ==  $token[$Player] 
			){
				$win = true;
		}

		if ($win) {
			$message = '<strong>'.$token[$Player].' WINS</strong><br>Clear Board and Play Again<br>
		<input type="submit" name="clearBoard" value="Clear Board">';
		}

		// Alternates between Players
		$Player = 1 - $Player;

	}
}
	
?>

<html>
<head>
	<title>Tic Tac Toe Practice</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

		<!- allows for submission of current player value for setting of tokens in board array ->
		<input type="hidden" name="current_player" value="<?php echo $Player; ?>">
		
		<?php 
		// echos current column or the winner
		if (isset($message)) {
				echo $message;
				echo "<br>";
			}

		// echos current player and gives reset board option upon winning
		if (!$win && $results != 9) {
			echo '<strong>Player '.$token[$Player].' Move</strong>';
		} 
		
		?>
		<table>
		
<?php 
// first create the board
$classes = array('top_row', 'middle_row', 'bottom_row');

for ($r=0; $r < 3; $r++) { 
	echo '<tr class="'.$classes[$r].'">';

		for ($c=0; $c < 3; $c++) {

			$current_cell = $r.'-'.$c;

			// echos the player token in the board
			if (isset($_SESSION['board'][$r][$c])) {
				echo '<td>' . $_SESSION['board'][$r][$c] . '</td>';
			} 

			// creates initial board when no move has been submitted
			elseif (!$win) {
				echo '<td><input type="submit" name="cell" value="'.$current_cell.'"></td>';
			} 

			// removes submit buttons so no move can be played upon winning
			else {
				echo '<td>&nbsp;</td>';
			}
		}
		echo '</tr>';
}
 ?>
 			</table>
 		</form>
 	</body>
 </html>

