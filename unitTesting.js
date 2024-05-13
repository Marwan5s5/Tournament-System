function validateForm(teamName, email, password) {
    const teamNameValidate = teamName.length > 2;
    const emailValidate = email.includes('@');
    const passwordValidate = password.length > 5;
    return teamNameValidate && emailValidate && passwordValidate;
}
document.addEventListener('DOMContentLoaded', function(){
    const registerForm = document.getElementById('theForm');
    registerForm.addEventListener('submit', function(event){
        event.preventDefault();

        const teamNameame = document.getElementById('teamName').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        const isValid = validateForm(teamNameame, email, password);
        if(isValid){
            console.log("The Form is valid!");
        }else{
            console.log("The Form is invalid!!!");
        }
    })
})