function checkPass()
{
    var pass1 = document.getElementById('pass');
    var pass2 = document.getElementById('rpass');
    var message = document.getElementById('error-pass');
    var goodColor = "#66cc66";
    var badColor = "#ff6666";
 	
    if(pass1.value.length > 7)
    {
        pass1.style.backgroundColor = goodColor;
        message.style.color = goodColor;
        message.style.display = "none";
        message.innerHTML = "";
    }
    else
    {
        pass1.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.style.display = "block";
        message.innerHTML = "Hasło powinno mieć minimum 8 znaków.";
        return;
    }
  
    if(pass1.value == pass2.value)
    {
        pass2.style.backgroundColor = goodColor;
        message.style.color = goodColor;
        message.style.display = "none";
        message.innerHTML = "";
    }
	else
    {
        pass2.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.style.display = "block";
        message.innerHTML = " Hasła się nie zgadzają.";
    }
}  

function checkPhone(){
    var phoneNo = document.getElementById('phoneInput');
    var message = document.getElementById('error-phone');
    var goodColor = "#66cc66";
    var badColor = "#ff6666";

    if(phoneNo.value.length == 12 && phoneNo.value.includes("+") )
    {
        phoneNo.style.backgroundColor = goodColor;
        message.style.color = goodColor;
         message.style.display = "none";
        message.innerHTML = "";
    }
    else
    {
        phoneNo.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.style.display = "block";
        message.innerHTML = "Numer telefonu powinien mieć maksymalnie 12 znaków i zaczynać się numerem kierunkowym np. +XX.";
        return;
    }
}

function checkPostal(){
    var phoneNo = document.getElementById('postalCodeInput');
    var message = document.getElementById('error-postal');
    var goodColor = "#66cc66";
    var badColor = "#ff6666";

    if(phoneNo.value.length == 6 && phoneNo.value.includes("-") )
    {
        phoneNo.style.backgroundColor = goodColor;
        message.style.color = goodColor;
        message.style.display = "none";
        message.innerHTML = "";
    }
    else
    {
        phoneNo.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.style.display = "block";
        message.innerHTML = "Kod pocztowy powinien być zapisany w formacie XX-XXX";
        return;
    }
}