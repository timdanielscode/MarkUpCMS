var settingsButton = document.getElementById('settings');

settingsButton.addEventListener("click", function() { 

    var cardProgressContainer = document.querySelector('.cardProgressContainer');
    cardProgressContainer.classList.toggle('display-none')
    
    var settingsContainer = document.getElementById('settingsContainer');
    settingsContainer.classList.toggle('display-none');
}); 
