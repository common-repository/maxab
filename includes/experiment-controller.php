<?php
switch ($_GET['action']) {
	case 'details':
		include_once 'experiment-details.php';
		break;
	case 'new':
		include_once 'experiment-new.php';
		break;
	case 'delete':
		include_once 'experiment-delete.php';
		break;
	case 'edit':
		include_once 'experiment-edit.php';
		break;
	default:
		include_once 'experiment-list.php';
		break;
}
?>