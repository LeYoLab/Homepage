<?php
require 'flight/Flight.php';
require 'classes.php';

$tmpRandomQuestionObject;


Flight::route('/getQuestion', function(){

	//Quiz
	$quizObject = new Quiz();

	//Zufällige Frage holen
	$randomQuestionObject = $quizObject->getRandomQuestion();
	$tmpRandomQuestionObject = $randomQuestionObject;

	//Die dazugehörigen Antworten holen
	$answerObjectArray = $randomQuestionObject->getAnswers();

	//Antworten shufflen
	shuffle($answerObjectArray);

	$lululu = array('q1' => $randomQuestionObject->getQuestion(), 'id' => $randomQuestionObject->getID());
	$i = 0;
	foreach($answerObjectArray as $ans){
		$lululu['a'.$i] = $ans->getAnswer();
		$i++;
	}

	echo json_encode($lululu);

});

Flight::route('/checkQuestion/@answer', function($answer){

	$iscorrect=false;
	$tmpding = explode("::", $answer);
	$answer=$tmpding[0];
	$id=$tmpding[1];
	$tmpQuizObject=new Quiz();
	$tmpQuestionObject=$tmpQuizObject->getQuestionByID($id);
	$tmpAnswerString = $tmpQuestionObject->getCorrectAnswer();
	if ($answer == $tmpAnswerString){
		$iscorrect=true;
	}
	echo json_encode($iscorrect);
});

Flight::start();
?>
 