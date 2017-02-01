<?php 
	class UserController implements IController{
		public function helloAction() {
	    $fc = FrontController::getInstance();
	    /* Инициализация модели */
	    $params = $fc->getParams();
	    $model = new FileModel();
	    $model->name = $params["name"];
	    $output = $model->render(USER_DEFAULT_FILE);
	    $fc->setBody($output);
	  }
		public function listAction() {
	    $fc = FrontController::getInstance();
	    /* Инициализация модели */
	    // $params = $fc->getParams();
	    $model = new FileModel();
	    $model->list = unserialize(file_get_contents(USER_DB));
	    $output = $model->render(USER_LIST_FILE);
	    $fc->setBody($output);
	  }
		public function getAction() {
	    $fc = FrontController::getInstance();
	    /* Инициализация модели */
	    $params = $fc->getParams();
	    // $params = $params['role'];
	    $model = new FileModel();
	    
	    $db = unserialize(file_get_contents(USER_DB));

	    $model->user =  $db[$params['role']];

	    $output = $model->render(USER_ROLE_FILE);

	    $fc->setBody($output);
	  }
	}
?>