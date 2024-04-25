document.addEventListener('DOMContentLoaded', function() {
    
    createFloatingButton();
    
});
function createFloatingButton(){
    var floatingButtonContainer = document.querySelector('.floating-button-div');
    if (floatingButtonContainer){
        floatingButtonContainer.addEventListener('click',function(){
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        });
    }
}