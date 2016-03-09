	<?php 

	define('DBHOST', 'mysql:host=leo-kleinschmidt.de.mysql;port=3306;');
	define('DBNAME', 'dbname=leo_kleinschmid');
	define('DBUSER', 'leo_kleinschmid');
	define('DBPASS', '4tKTZnHz');


	class Quiz
	{
		private $db;

		public function __construct(){
			$tmpdb = new DB();
			$this->db = $tmpdb->getDb();
		}

		public function getRandomQuestion(){
			$tmpding = $this->db->query("SELECT * FROM questions WHERE true ORDER BY rand() DESC LIMIT 1");
			$question = $tmpding->fetchAll(PDO::FETCH_CLASS, "Question");
			return $question[0];
		}

		public function getQuestionByID($id){
			$tmpding = $this->db->prepare("SELECT * FROM questions WHERE id=:questid");
			$tmpding->execute(array('questid' => $id));
			$question = $tmpding->fetchAll(PDO::FETCH_CLASS, "Question");
			return $question[0];
		}

		public function getCorrectAnswerIDByQuestionID($id){
			$question = $this->getQuestionByID($id);
			dumpmyvar($question);
			return $question->getCorrectAnswerID();
		}
	}

	//Question Klasse
	class Question
	{
		private $db;

		private $answers;

		private $id;
		private $question;


		public function __construct(){
			$tmpdb = new DB();
			$this->db = $tmpdb->getDb();
		}

		public function getQuestion(){
			return $this->question;
		}

		public function getAnswers(){
			$tmpAnswers = $this->db->prepare("SELECT * FROM answers WHERE question_id=:questionID");
			$tmpAnswers->execute(array('questionID' => $this->id));
			$retunClass = $tmpAnswers->fetchAll(PDO::FETCH_CLASS, "Answer");
			foreach($retunClass as $ans){
				$this->answers[] = $ans->getAnswer();
			}
			return $retunClass;
		}

		public function getID(){
			return $this->id;
		}

		public function getCorrectAnswerID(){
			foreach ($this->getAnswers() as $tmpAnswerObject){
				if ($tmpAnswerObject->getCorrect() == 1){
					return $tmpAnswerObject->getID();
				}
			}
		}

		public function getCorrectAnswer(){
			foreach ($this->getAnswers() as $tmpAnswerObject){
				if ($tmpAnswerObject->getCorrect() == 1){
					return $tmpAnswerObject->getAnswer();
				}
			}
		}

	}

	//Answer Klasse
	class Answer
	{
		private $db;
		private $id;
		private $answer;
		private $correct;
		private $question_id;

		public function __construct(){
			$db = new DB();
			$this->db = $db->getDb();
		}

		public function getAnswer(){
			return $this->answer;
		}

		public function getID(){
			return $this->id;
		}

		public function getCorrect(){
			return $this->correct;
		}

		public function getCorrectID(){
			return $this->question_id;
		}
	}

	/**
	* 
	*/
	class DB 
	{
		private $pdo;
		
		public function __construct(){
			$this->pdo= new PDO(DBHOST.DBNAME, DBUSER, DBPASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		}

		public function getDb(){
			return $this->pdo;
		}
	}

	 ?>