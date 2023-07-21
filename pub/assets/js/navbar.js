var profileIcon = document.getElementById('profileIcon');

profileIcon.onclick = function(){
    
    var body = document.querySelector('html');
    body.classList.toggle('dark-layer');
    var profileDropdown = document.getElementById('profileDropdown');
    profileDropdown.classList.toggle('show-dropdown')
};