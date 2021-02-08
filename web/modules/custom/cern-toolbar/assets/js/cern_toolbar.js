jQuery('#cern-toolbar').on('click', 'ul li.signin a.cern-signin', function () {
    jQuery(this).closest('li.signin').toggleClass('signin-expand');
    jQuery(this).closest('#cern-toolbar').toggleClass('signin-expand');
});

jQuery(window).click(function() {
    jQuery('#cern-toolbar').removeClass('signin-expand');
    jQuery('#cern-toolbar li.signin-expand').removeClass('signin-expand');
}); 

jQuery('#cern-toolbar').click(function(event){
    event.stopPropagation();
}); 

jQuery(window).scroll(function() {
    jQuery('#cern-toolbar').removeClass('signin-expand');
    jQuery('#cern-toolbar li.signin-expand').removeClass('signin-expand');
}); 
