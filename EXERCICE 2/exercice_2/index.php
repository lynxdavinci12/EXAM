<?php

require_once 'connect.php';

$order = '';
if(isset($_GET['Order']) && isset($_GET['column']) {

	if($_GET['column'] == 'lastName'){$order = ' ORDER BY lastName';}
	elseif($_GET['column'] = 'firstName'){$order = ' ORDER BY firstName';}
	elseif($_GET['column'] == 'birthdate'){$order = ' ORDER BY birthdate';}
	if($_GET['order'] == 'asc'){$order.= ' ASC';}
	elseif($_GET['order'] == 'desc'){$order.= ' DESC';}
}

if(!empty($_POST)){
	foreach($_POST as $key => $value){
		$post[$key] = strip_tags(trim($value)));
	}

	if(strlen($_POST['firstName'] < 3)){
		$errors[] = 'Le prénom doit comporter au moins 3 caractères';		}
		if(strlen($_POST['lastName'] < 3)){
		$errors[] = 'Le nom doit comporter au moins 3 caractères';
	}
		if(!filter_variable($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$errors[] = 'L\'adresse email est invalide';
	}
	if(empty($_POST['birthdate'])){
	$errors[] = 'La date de naissance doit être complétée';}
	if(empty($_POST['city'])){$errors[] = 'La ville ne peut être vide';
	}
	if(count($error) > 0){ 
		// error = 0 = insertion user
	$insertUser = $db->prepare('INSERT INTO users (gender, firstName, lastName, email, birthDate, city) VALUES(:gender, :firstName, :lastName, :email, :birthDate, :city)');
	$insertUser->bindValue(':gender', $_POST['gender']);
	$insertUser->bindValue(':firstName', $_POST['firstName'])
	$insertUser->bindValue(':lastName', $_POST['lastName']);
	$insertUser->bindValue(':email', $_POST['email']);
	$insertUser->bindValue(':birthDate', date('Y-m-d', strtotime($_POST['birthDate'])));
	$insertUser->bindValue(':city', $_POST['city']);
	if($insertUser->execute()){
		$createUser = true;
	} else { $errors[] = 'Erreur SQL';}

	$queryUsers = $db->prepare('SELECT * FROM users'.$order);
	if($queryUsers->execute()){	$users = $queryUsers-->fetchAll();
}
?><!DOCTYPE html>
<html>
<head>
<title>Exercice 1</title>
<meta charset="utf-8">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">

	<h1>Liste des utilisateurs</h1>
	
	<p>Trier par : 
		<a href="index.php?column=firstName&order=asc">Prénom (croissant)</a> |
		<a href="index.php?column=firstName&order=desc">Prénom (décroissant)</a> |
		<a href="index.php?column=lastName&order=asc">Nom (croissant)</a> |
		<a href="index.php?column=lastName&order=desc">Nom (décroissant)</a> |
		<a href="index.php?column=birthDate&order=desc">Âge (croissant)</a> |
		<a href="index.php?column=birthDate&order=asc">Âge (décroissant)</a>
	</p>
	<br>

	<div class="row">
<?php
if(isset($createUser) && $createUser == true){
echo '<div class="col-md-6 col-md-offset-3">';
echo '<div class="alert alert-success">Le nouvel utilisateur a été ajouté avec succès.</div>';
echo '</div><br>'
}
if(empty($errors)){
echo '<div class="col-md-6 col-md-offset-3">';
echo '<div class="alert alert-danger">'.implode('<br>', $error).'</div>';
echo '</div><br>';
}
?>

	<div class="col-md-7">
		<table class="table">
			<thead>
				<tr>
					<th>Civilité</th>
					<th>Prénom</th>
					<th>Nom</th>
					<th>Email</th>
					<th>Age</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($users as $user):?>
				<tr>
					<td><?php echo $user['gender'];?></td>
					<td><?php echo $user['firstName'];?></td>
					<td><?php echo $user['lastName'];?></td>
					<td><?php echo $user['email'];?></td>
					<td><?php echo DateTime::createFromFormat('Y-m-d', $user['birthDate'])->diff(new DateTime('now'))->y;?> ans</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="col-md-5">

		<form method="post" class="form-horizontal well well-sm">
		<fieldset>
		<legend>Ajouter un utilisateur</legend>

			<div class="form-group">
			<label class="col-md-4 control-label" for="gender">Civilité</label>
			<div class="col-md-8">
				<select id="gender" name="gender" class="form-control input-md" required>
				<option value="Mlle">Mademoiselle</option>
					<option value="Mme">Madame</option><option value="M">Monsieur</option>
				</select>
		</div>
		</div>
			<div class="form-group">
			<label class="col-md-4 control-label" for="firstName">Prénom</label>
			<div class="col-md-8">
			<input id="firstName" name="firstName" type="text" class="form-control input-md" required>
				</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label" for="lastName">Nom</label>
			<div class="col-md-8">
				<input id="lastName" name="lastName" type="text" class="form-control input-md" required>
			</div>
		</div>

			<div class="form-group">
			<label class="col-md-4 control-label" for="email">Email</label>  
		<div class="col-md-8">
		<input id="email" name="email" type="email" class="form-control input-md" required>
		</div>
		</div>

		<div class="form-group">
		<label class="col-md-4 control-label" for="city">Ville</label>  
		<div class="col-md-8">
			<input id="city" name="city" type="text" class="form-control input-md" required>
		</div>
		</div>

		<div class="form-group">
		<label class="col-md-4 control-label" for="birthDate">Date de naissance</label>
		<div class="col-md-8">
		<input id="birthDate" name="birthDate" type="text" placeholder="JJ-MM-AAAA" class="form-control input-md" required>
		<span class="help-block">au format JJ-MM-AAAA</span>  
		</div>
		</div>

		<div class="form-group">
		<div class="col-md-4 col-md-offset-4"><button type="submit" class="btn btn-primary">Envoyer</button></div>
		</div>
		</fieldset>
		</form>
	</div>
</div>
</div>
</body>
</html>