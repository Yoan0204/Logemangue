function validateForm() {
  var nom = document.forms["myForm"]["nom"].value;
  var email = document.forms["myForm"]["EMail"].value;
  var age = document.forms["myForm"]["age"].value;
  if (nom == "") {
    alert("Le nom est vide");
    return false;
  }
  var atpos = email.indexOf("@");
  var dotpos = email.lastIndexOf(".");
  if (atpos < 1 || dotpos - atpos < 2) {
    alert("Veuillez entrer une adresse email correcte");
    return false;
  }
  if (age == "") {
    alert("L'âge est vide");
    return false;
  }
  if (isNaN(age)) {
    alert("L'âge doit être un nombre");
    return false;
  }
  return true;
}

validateForm();
