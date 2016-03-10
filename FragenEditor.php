<!--HTML Grundgerüst-->
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	<title>Fragen erstellen</title>

</head>
<body>
	
<div id="wrapper" class="well">
	<h1>Der Fragen Editor</h1>
<?php


//Wenn der Frage Speichern Button gedrückt wurde
if(isset($_POST['SaveQuest']))
{

	$answerError=0;
	$questionError=0;

	if(strlen($_POST['answer1'])<1){
		$answerError=1;
	}
	if(strlen($_POST['answer2'])<1){
		$answerError=1;
	}
	if(strlen($_POST['answer3'])<1){
		$answerError=1;
	}
	if(strlen($_POST['Frage'])<1){
		$questionError=1;
	}

	//var_dump($questionError);

	if($answerError==0 and $questionError==0)
	{
		$QuestObject = new Question();
		$AnswerObject = new Answer();

		$tmpID = $QuestObject->saveQuestion($_POST['Frage']);

		echo 'Frage gespeichert mit ID: '. $tmpID;

		$Answer1 = $_POST['answer1'];
		$Answer2 = $_POST['answer2'];
		$Answer3 = $_POST['answer3'];
		$AnswerObject->saveAnswer($Answer1, true, $tmpID);
		$AnswerObject->saveAnswer($Answer2, false, $tmpID);
		$AnswerObject->saveAnswer($Answer3, false, $tmpID);
	} else {
		?>
			
		<?php
	}
}

//Question Klasse
class Question
{
	private $question;
	private $answers = [];
	private $pdo;

	public function __construct(){
		$this->pdo = new PDO('mysql:host=localhost;port=8889;dbname=leo_kleinschmid', 'root', 'root',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	}

	// Speichern eines Fragestrings in der datenbank
	public function saveQuestion($question) 
	{
		$this->setQuestion($question);
		$statement = $this->pdo->prepare("INSERT INTO questions (question) VALUES (:question)");
		$statement->execute(array('question' => $this->question));

		//ID holen
		$id = $this->pdo->query("SELECT id FROM questions WHERE true ORDER BY id DESC LIMIT 1");
		$result = $id->fetchAll();

		// returns the new question id
		return $result[0]['id'];
	}

	private function setQuestion($question) {
		$this->question = $question;
	}


}

//Answer Klasse
class Answer
{

	private $answer;
	private $pdo;

	public function __construct(){
		$this->pdo= new PDO('mysql:host=localhost;port=8889;dbname=leo_kleinschmid', 'root', 'root',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	}


	//Antwortstring speichern
	public function saveAnswer($answer, $correct, $id)
	{
		$this->setAnswer($answer);
		$statement = $this->pdo->prepare("INSERT INTO answers (answer, correct, question_id) VALUES (:answer, :correct, :question_id)");
		$statement->execute(	
							array(	'answer' => $this->answer,
									'correct' => $correct,
									'question_id' => $id
								)
							);
		//var_dump($statement->errorInfo());
	}

	private function setAnswer($answer){
		$this->answer = $answer;
	}
}


?>

<!--<script src="js/bootstrap.js">

</script>-->

<!--Input Form-->
<form method="POST">
	<div class="form-group">
	   	<label for="questionbox">Frage:</label>
	    <textarea class="form-control" rows="4" id="questionbox" placeholder="Frage" name="Frage"></textarea>
	   	<br>
	   	<div class="form-group has-success">
	    	<input type="text" class="form-control" placeholder="Antwort 1" name ="answer1" id="answer1">
	   	</div>
	   	<div class="form-group has-error">
	    	<input type="text" class="form-control" placeholder="Antwort 2" name="answer2" id="answer2">
	    </div>
	    <div class="form group has-error">
	    	<input type="text" class="form-control" placeholder="Antwort 3" name="answer3" id="answer3">
	    </div>
	    <br/>
	    <div class="form group">
	    	<input class="btn btn-default pull-right" type="submit" value="Frage eintragen" name="SaveQuest">
	    </div>
	    <br>
	  	</div>
	</form>
	<br />
	<?php  

	if($answerError==1 and $questionError==0){
		?>
			<div class="alert alert-danger alert-dismissible fade in" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
				<h4>Fehler!</h4> 
				<p>3 Antworten du genius.</p> 
			</div>
		<?php
	}
	elseif($answerError==0 and $questionError==1){
		?>
			<div class="alert alert-danger alert-dismissible fade in" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
				<h4>Fehler!</h4> 
				<p>Du musst schon eine Frage angeben du genius.</p> 
			</div>
		<?php
	}
	elseif($answerError==1 and $questionError==1){
		?>
			<div class="alert alert-danger alert-dismissible fade in" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 
				<h4>Fehler!</h4> 
				<p>Was fehlt? Die Frage und die Antworten. Trottel.</p> 
			</div>
		<?php
	}

	?>

	<div id="errors">
		
	</div>

</div>

</body>
</html>