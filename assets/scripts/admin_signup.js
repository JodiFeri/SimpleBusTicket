const passwordInput = document.getElementById("password");
const confPasswordInput = document.getElementById("confPassword");
const messagePass = document.getElementById("passwordErr");
const messageConf = document.getElementById("confPassErr");


function validatePassword()
{
    if (passwordInput.value.length < 8) {
        messagePass.textContent = "Password should be more than 8 characterss";
        return false;
    } else if (passwordInput.value !== confPasswordInput.value) {
        messageConf.textContent = "Confirm Password and Password values should be of same length";
        return false;
    } else {
        messagePass.textContent = "";
        messageConf.textContent = "";
        return true;
    }
}

passwordInput.addEventListener("input", validatePassword);
confPasswordInput.addEventListener("input", validatePassword);