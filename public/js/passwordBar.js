var passwd = document.getElementById('passwd');
var lowercase = document.getElementById('lowercase');
var uppercase = document.getElementById('uppercase');
var numeric = document.getElementById('numeric');
var special = document.getElementById('special');
var length = document.getElementById('length');
var mood = document.getElementById('mood');
var list = document.getElementById('list');

passwd.addEventListener('input', function() {
    length.style.display = (passwd.value.length >= 8) ? 'none': 'block';
    numeric.style.display = /\d/.test(passwd.value) ? 'none': 'block';
    lowercase.style.display = /[a-z]/.test(passwd.value) ? 'none': 'block';
    uppercase.style.display = /[A-Z]/.test(passwd.value) ? 'none': 'block';
    special.style.display = /\W/.test(passwd.value) ? 'none': 'block';
    if (passwd.value.match(/^.*(?=.{8,})((?=.*[\W]){1})(?=.*\d)((?=.*[a-z]){1})((?=.*[A-Z]){1}).*$/))
    {
        mood.className = 'far fa-smile-wink fa-5x';
        mood.style.color = '#EAE7DC';
        list.style.display = 'none';
    }
    else
    {
        mood.className = 'far fa-frown fa-5x';
        mood.style.color = '#EAE7DC';
        list.style.display = 'block';
    }
})